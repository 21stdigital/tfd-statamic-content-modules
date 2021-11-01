<?php

namespace TFD\ContentModules\ViewModels;

use Statamic\View\ViewModel;
use TFD\ContentModules\ViewModels\Traits\Content;

class General extends ViewModel
{
    use Content {
        data as protected contentData;
    }

    protected $field_handle = 'content';

    public function data(): array
    {
        $data = $this->contentData();

        return $data;
    }
}
