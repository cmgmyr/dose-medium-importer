<?php

namespace Med\Entities;

use Exception;
use Illuminate\Support\Collection;

class Article extends BaseEntity
{
    /**
     * @var Collection
     */
    public $content;

    /**
     * @var Collection
     */
    public $tags;

    /**
     * @var Collection
     */
    public $categories;

    public function __construct(array $data = [])
    {
        $content = array_get($data, 'content', []);

        parent::__construct($data);

        $this->setContent($content);
        $this->setTags();
        $this->setCategories();
    }

    /**
     * Sets the content as a collection.
     *
     * @param array $content
     */
    public function setContent(array $content = [])
    {
        $contentEntities = [];

        foreach ($content as $item) {
            try {
                $contentEntities[] = $this->buildContentEntity($item);
            } catch (Exception $e) {
                //
            }
        }

        $this->content = Collection::make($contentEntities);
    }

    /**
     * Builds out new entities based on the given item type.
     *
     * @param $entity
     * @return mixed
     * @throws Exception
     */
    protected function buildContentEntity($entity)
    {
        $entityName = 'Med\Entities\\' . $entity['type'];

        if (!class_exists($entityName)) {
            throw new Exception('Content entity "' . $entityName . '" does not exist.');
        }

        return new $entityName($entity);
    }

    /**
     * Required array keys and types to populate an entity.
     *
     * @return array
     */
    public function getPropertyRules()
    {
        return [
            'id'                     => 'required|integer',
            'page_title'             => 'required|string',
            'created_date'           => 'required|integer',

            'author_name'            => 'string',
            'categories'             => 'array',
            'description'            => 'string',
            'scrape_source'          => 'string',
            'source_display_text'    => 'string',
            'source_display_url'     => 'string',
            'edited_date'            => 'integer',
            'post_date'              => 'integer',
            'enable_for_feeds'       => 'boolean',
            'active'                 => 'boolean',
            'test_start'             => 'integer',
            'test_complete'          => 'integer',
            'promo_img'              => 'string',
            'tags'                   => 'array',

            'included_content_types' => 'none',
        ];
    }

    /**
     * Sets each tag to be a Tag entity.
     */
    private function setTags()
    {
        $this->tags = Collection::make($this->getData()['tags'])->map(function ($tag) {
            return new Tag($tag);
        });
    }

    /**
     * Sets each category to be a Category entity.
     */
    private function setCategories()
    {
        $this->categories = Collection::make($this->getData()['categories'])->map(function ($category) {
            return new Category($category);
        });
    }

    /**
     * Returns the HTML content for the entity.
     *
     * @return string
     */
    public function renderHtml()
    {
        $html = '<h1>' . $this->page_title . ' (Dose ID: ' . $this->id . ')</h1>';

        if ($this->description != '') {
            $html .= '<p>' . $this->description . '</p>';
        }

        $this->content->each(function ($item) use (&$html) {
            $html .= $item->renderHtml();
        });

        return $html;
    }
}
