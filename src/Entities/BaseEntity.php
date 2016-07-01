<?php

namespace Med\Entities;

use Exception;
use Med\Entities\Traits\SanitizationTrait;

abstract class BaseEntity
{
    use SanitizationTrait;

    /**
     * Required array keys and types to populate an entity.
     *
     * @return array
     */
    abstract public function getPropertyRules();

    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;

        $this->cleanData();
        $this->setProperties();
    }

    /**
     * Returns the raw data array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sanitizes and validates the data given to the entity.
     */
    protected function cleanData()
    {
        $this->sanitizeData();
    }

    /**
     * Sets all of the data properties after validation
     * is successful.
     */
    protected function setProperties()
    {
        foreach ($this->data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Returns a key's value from all of the object's properties.
     *
     * @param $key
     * @return mixed
     */
    protected function getPropertyRuleByKey($key)
    {
        return array_get($this->getPropertyRules(), $key, false);
    }

    /**
     * Get any properties that aren't specified in the entity rules
     * and haven't been specifically set.
     *
     * @param $name string
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->getData())) {
            return $this->getData()[$name];
        }

        throw new Exception('"' . $name . '" was not found within ' . get_called_class());
    }

    /**
     * If we're going to be using an internal data array, then we need to update it.
     *
     * @param string $name  The name of the property to set
     * @param mixed $value The value to set
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
        $this->$name = $value;
    }

    /**
     * Returns the HTML content for the entity.
     */
    public function renderHtml()
    {
        return;
    }
}
