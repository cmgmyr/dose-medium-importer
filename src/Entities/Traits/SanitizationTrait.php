<?php

namespace Med\Entities\Traits;

trait SanitizationTrait
{
    /**
     * Removes properties from data that aren't supposed to be set.
     */
    protected function sanitizeData()
    {
        foreach ($this->data as $key => $value) {
            $this->checkForUnexpectedType($key);
        }
    }

    /**
     * Removes property from data that isn't supposed to be set.
     *
     * @param $key
     */
    protected function checkForUnexpectedType($key)
    {
        if (!$this->getPropertyRuleByKey($key)) {
            unset($this->data[$key]);
        }
    }
}
