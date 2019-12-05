Anax View
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/view/v/stable)](https://packagist.org/packages/anax/view)
[![Join the chat at https://gitter.im/mosbth/anax](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/canax?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/canax/view.svg?branch=master)](https://travis-ci.org/canax/view)
[![CircleCI](https://circleci.com/gh/canax/view.svg?style=svg)](https://circleci.com/gh/canax/view)

[![Build Status](https://scrutinizer-ci.com/g/canax/view/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/view/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/view/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/view/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/view/?branch=master)

[![Maintainability](https://api.codeclimate.com/v1/badges/b1b2a5f4b93ba4c630e4/maintainability)](https://codeclimate.com/github/canax/view/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a55bf4c3892e4ae79a18ce86dd5e0507)](https://www.codacy.com/app/mosbth/view?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=canax/view&amp;utm_campaign=Badge_Grade)

Anax View module to supply a structure of views to a Anax website.



About views, terminology and structure
------------------

The views, also known as template files, are pure PHP files and their purpose is to render content onto a html structure to create a web page.

It works like this:

1. The content of the webpage is gathered as a collection of views.
1. The view collection is created in the router callbacks and controllers.
1. A view is a combination of variables (content), commonly known as `$data`, which is supplied to a template file, which is executed and renders the data onto a html structure.
1. Each view is a small part of html and content and rendered onto the web page.

You can see a sample of views in the directory [`view/anax/v2`](view/anax/v2).



Views are rendered in a layout
------------------

The views are rendered in an orderd fashion by a structured layout. This layout is in itself a view (template file and data), it is just a view which renders more views.

You can see a sample of an layout view in [`view/anax/v2/layout`](view/anax/v2/layout).

The layout views you above have organised each part of the web page as regions. Each view is rendered in a region.

This code shows how a general region is setup in the layout file, and how it renders the views belonging to this region.

```html
<!-- flash -->
<?php if (regionHasContent("flash")) : ?>
<div class="outer-wrap outer-wrap-flash">
    <div class="inner-wrap inner-wrap-flash">
        <div class="row">
            <div class="region-flash">
                <?php renderRegion("flash") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
```

You can optionally add a region, and a sort value, when you add the views to the collection. This is what decides where (region), and in what order the view is rendered.



A template file for the view
------------------

A template file is an ordinary PHP-file. Here is the template file [`anax/v2/article/default`](view/anax/v2/article/default.php).

```php
<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}


?><article <?= classList($classes) ?>>
<?= $content ?>
</article>
```

The namespace makes it possible to access a list of built in view helper functions, for example helper functions to escape output or create urls into the framework or assets.

```php
namespace Anax\View;
```

The commented section can be uncommented for debugging what helper functions and what variables that are available.

```php
// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());
```

The general idea is then to further prepare content through incoming variables.

```php
// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}
```

Then finally render the output.

```php
<article <?= classList($classes) ?>>
<?= $content ?>
</article>
```

That is the general procedures of a template file.



View helpers
------------------

The file [`View/ViewHelperFunctions`](src/View/ViewHelperFunctions.php) holds the helper functions that eases work in the template files.

Here are some essential helpers.

| Function | Details |
|----------|---------|
| `asset($url)` | Create a link to a static asset (css, img, php-file, pdf). |
| `e($str)`     | A shortcut for `htmlentities(). |
| `url($url)`   | Create an url within the framework, for example `""` (home route) or `"doc/about"`. |
| `redirect($url)` | Redirect to another url (within the framework), for example `"game/init"`. |

Review the helper files for details.



Dumb views or fat views
------------------

A view can be "dumb" and only recive input from the framework, and render it with the html code.

A view can also be "fat", or perhaps not so "smart", and make calls back into the framework.

The general recomendation is to have dumb views and not make calls back into the framwork.



Type of views
------------------

A view can generally render any type of result and is not limited to html only.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2013 - 2019 Mikael Roos, mos@dbwebb.se
```
