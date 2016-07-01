<?php

namespace Med\Entities;

class Category extends BaseEntity
{
    /**
     * Required array keys and types to populate an entity.
     *
     * @return array
     */
    public function getPropertyRules()
    {
        return [
            'name' => 'required|string',
            'rank' => 'required|integer',
        ];
    }
}
