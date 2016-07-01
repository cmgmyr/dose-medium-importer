<?php

namespace Med\Entities;

class Html extends BaseEntity
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
            'html'              => 'required|string',
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

        $html .= $this->html;

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
