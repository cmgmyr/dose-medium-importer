<?php

namespace Med\Entities;

class Image extends BaseEntity
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
            'image'             => 'required|string',
            'width'             => 'required|integer',
            'height'            => 'required|integer',
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

        $html .= '<figure><img src="' . $this->image . '">';

        if ($this->caption_link_text != '') {
            $html .= '<figcaption>';

            if ($this->caption_link_url != '') {
                $html .= '<a href="' . $this->caption_link_url . '">' . $this->caption_link_text . '</a>';
            } else {
                $html .= $this->caption_link_text;
            }

            $html .= '</figcaption>';
        }

        $html .= $this->caption;

        $html .= '</figure>';

        return $html;
    }
}
