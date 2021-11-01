# Content Modules

> Content Modules is a Statamic addon that allows you to easily modify and add data to custom set view templates for replicator or bard fields.

## How to install

Run the following command from your project root:

```bash
composer require tfd/statamic-content-modules
```

## How to use

In [bard](https://statamic.dev/fieldtypes/bard) and [replicator](https://statamic.dev/fieldtypes/replicator) fields you can dynamically piece together content sets in whatever order and arrangement you imagine. To better control the data and appearance of the content sets, this addon allows you to write custom PHP classes and view templates for these sets. General information can be found on https://statamic.dev/view-models.

### 1. Create view model class

Your first step is to create a view model class that extends the addon's ViewModel.

```php
<?php
// app/ViewModels/Page.php

namespace App\ViewModels;

use TFD\ContentModules\ViewModels\General;

class Page extends General
{
}
```

---

### 2. Inject view model class into

This class now has to be injected into your collection's view, e. g.:

```yaml
# content/collections/pages.yaml

title: Pages
structure:
  root: true
route: "{parent_uri}/{slug}"
inject:
  view_model: App\ViewModels\Page
```

---

### 3. Adapt default template file

In the template view, that renders your content (`default.antlers.html` by default), all sets are included as an array. To iterate over each set you have to change this template file, e. g.:

```html
<!-- resources/views/templates/default.antlers.html -->
<div class="modules">
  {{ content }} {{ partial src="modules/{type}" }} {{ /content }}
</div>
```

> The `content` variable has to match the handle of your bard or replicator field.

If you use different handles for different collections, you can create a new view model class for every collection and set a custom field handle:

```php
<?php
// app/ViewModels/News.php

use TFD\ContentModules\ViewModels\General;

class Page extends General
{
    protected $field_handle = 'my_custom_handle';
}
```

> Do not forget to change the template variable inside your template view: it has to match your custom field handle.

---

### 4. Create a view for your custom set

As you can see the example in section 3. includes a partial from `modules/{type}`. The `type` variable automatically resolves to the handle of a set (You define your sets when creating the content field inside your blueprint. It has to be a bard or replicator field type.)

If you have a set with the handle `gallery` you have to create a `modules/gallery.antlers.html` template to render its contents.

You have to repeat this step for all of your sets.

---

### 5. (optional) Create a custom content modules PHP class for every set

If you want to modify data for a content module, you can create a new PHP class, e. g. for your `gallery` set:

```php
<?php
// app/ContentModules/Gallery.php

namespace App\ContentModules;

use TFD\ContentModules\ContentModules\ContentModule;

class Gallery extends ContentModule
{
    public function data(): array
    {
        $data = $this->data;

        $data['foo'] = 'bar';

        return $data;
    }
}
```

The `data()` method will be called automatically. Any modified or added data is then available in your content module's template:

```html
<!-- resources/views/modules/gallery.antlers.html -->

<p>{{ foo }}</p>

<!-- Will be rendered as: <p>bar</p> -->
```

If you do not create custom PHP classes for your sets you always can customize each view template for your sets with custom markup.
