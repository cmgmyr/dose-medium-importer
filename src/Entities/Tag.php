<?php

namespace Med\Entities;

class Tag extends BaseEntity
{
    /**
     * Required array keys and types to populate an entity.
     *
     * @return array
     */
    public function getPropertyRules()
    {
        return [
            'tag' => 'required|string',
        ];
    }
}
