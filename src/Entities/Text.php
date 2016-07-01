<?php

namespace Med\Entities;

class Text extends BaseEntity
{
    /**
     * Required array keys and types to populate an entity.
     *
     * @return array
     */
    public function getPropertyRules()
    {
        return [
            'id'                => 'required|integer',
            'story'             => 'required|string',
            'type'              => 'required|string',

            'headline'          => 'string',
            'caption'           => 'string',
            'caption_link_text' => 'string',
            'caption_link_url'  => 'string',
            'added'             => 'integer',
        ];
    }

    public function renderHtml()
    {
        return '<h2>' . $this->headline . '</h2>' . $this->story;
    }
}
