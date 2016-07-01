<?php

namespace Med\Entities;

class Video extends BaseEntity
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
            'code'              => 'required|string',
            'source_url'        => 'required|string',
            'thumbnail_url'     => 'required|string',
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
        $html = '';

        if ($this->headline != '') {
            $html .= '<h2>' . $this->headline . '</h2>';
        }

        $html .= '<figure>' . $this->code . '</figure>';

        if ($this->caption_link_text != '') {
            $html .= '<blockquote>';

            if ($this->caption_link_url != '') {
                $html .= '<a href="' . $this->caption_link_url . '">' . $this->caption_link_text . '</a>';
            } else {
                $html .= $this->caption_link_text;
            }

            $html .= '</blockquote>';
        }

        $html .= $this->caption;

        return $html;
    }
}
