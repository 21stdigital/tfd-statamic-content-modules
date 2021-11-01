<?php

namespace TFD\ContentModules\ViewModels\Traits;

use TFD\ContentModules\ContentModules\ContentModule;

trait Content
{
    public function data(): array
    {
        return $this->sanitizeContentModules();
    }

    private function sanitizeContentModules(): array
    {
        if (!$this->cascade || !$modules = $this->cascade->get($this->field_handle)) {
            return [];
        }

        if (is_object($modules)) {
            $modules = collect($modules->value())->map(function ($module_data) {
                $type = $module_data['type'];
                if (!$type) {
                    return [];
                }

                $class = 'App\ContentModules\\' . ContentModule::toCamelCase($type);
                $module = class_exists($class) ? new $class($module_data) : new ContentModule($module_data);

                return $module->isValid() ? $module->toArray() : null;
            })->filter()->toArray();
        } else {
            $modules = null;
        }

        return [$this->field_handle => $modules];
    }
}
