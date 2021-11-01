<?php

namespace TFD\ContentModules\ContentModules;

class ContentModule
{

    public $defaults = [];
    public $required = [];
    public $data = [];
    public $type = null;
    public $name = null;

    public function __construct(array $data = [])
    {
        $this->init($data);
        $this->data = array_merge($this->data, $this->data());
    }

    protected function init($data = [], $withDefaults = true)
    {
        $this->data = $withDefaults ? array_merge($this->defaults, $data) : $data;

        $this->type = $data['type'];
        $this->name = self::toCamelCase($this->type);
    }

    public function data(): array
    {
        return $this->data;
    }

    public function isValid(): bool
    {
        foreach ($this->required as $field) {
            if ($this->data[$field] === null) {
                return false;
            }
        }

        return true;
    }

    public function toArray()
    {
        $data = $this->data;

        if ($this->type) {
            $data['type'] = $this->type;
        }
        if ($this->name) {
            $data['name'] = $this->name;
        }

        return $data;
    }

    public function get(string $key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function getWithPrefix(string $prefix, string $key)
    {
        return $this->get($prefix . $key);
    }

    public function getValue(string $key)
    {
        $value = $this->get($key);
        if (is_object($value) && get_class($value) === 'Statamic\Fields\Value') {
            return $value->value();
        }

        return $value;
    }

    public static function toCamelCase($str, $capitalizeFirstCharacter = true)
    {
        $str = str_replace('-', '', ucwords($str, '-'));
        $str = str_replace('_', '', ucwords($str, '_'));

        return !$capitalizeFirstCharacter ? lcfirst($str) : $str;
    }
}
