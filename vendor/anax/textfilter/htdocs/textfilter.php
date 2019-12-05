<?php
require __DIR__ . "/config.php";

use \Anax\TextFilter\TextFilter;

// Prepare the content
$text = <<<'EOD'
My Article
=================================

This is an example on writing text and filtering it to become HTML.



Markdown used
---------------------------------

The class uses *markdown* and a external PHP class [php-markdown](http://daringfireball.net/projects/markdown/).



Clickable
---------------------------------

Som links can become clickable links, such as this to http://example.com/.



BBCode
---------------------------------

BBCode [i]is supported[/i] with some [b]limited tags[/b], but quite easy to extend.



ShortCode
---------------------------------

These are own *shortcodes* such as this image with a caption, wrapped in a `<figure>` element.

[FIGURE src="https://www.gravatar.com/avatar/67aaf77308040cd57f0eba43e9f5404a?s=200" caption="Me with a caption."]



Create mailto: links
---------------------------------

```text
<mailto:user@example.com>
<user@example.com>
<a href="mailto:user@example.com">mail</a>
```

<mailto:user@example.com>
<user@example.com>
<a href="mailto:user@example.com">mail</a>



Source code syntax highlight
---------------------------------



###Text

```text
$php = true;
if (isset($php)) {
    echo "Yey";
}
```



###PHP

```php
$php = true;
if (isset($php)) {
    echo "Yey";
}
```



###HTML

```html
<!doctype html>
<title>Demo of gridsystem</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/grid_12-60-20-960px.css">
```



###JavaScript

```javascript
function initHighlight(block, cls) {
  for (var i = 0 / 2; i < classes.length; i++) {
    if (checkCondition(classes[i]) === undefined)
      console.log('undefined');
  }
}

export  initHighlight;
```



###CSS

```css
@font-face {
  font-family: Chunkfive; src: url('Chunkfive.otf');
}

body, .usertext {
  color: #F0F0F0; background: #600;
  font-family: Chunkfive, sans;
}

@import url(print.css);
@media print {
  a[href^=http]::after {
    content: attr(href)
  }
}
```



###LESS

```less
@import "fruits";

@rhythm: 1.5em;

@media screen and (min-resolution: 2dppx) {
    body {font-size: 125%}
}

section > .foo + #bar:hover [href*="less"] {
    margin:     @rhythm 0 0 @rhythm;
    padding:    calc(5% + 20px);
    background: #f00ba7 url(http://placehold.alpha-centauri/42.png) no-repeat;
    background-image: linear-gradient(-135deg, wheat, fuchsia) !important ;
    background-blend-mode: multiply;
}

@font-face {
    font-family: /* ? */ 'Omega';
    src: url('../fonts/omega-webfont.woff?v=2.0.2');
}

.icon-baz::before {
    display:     inline-block;
    font-family: "Omega", Alpha, sans-serif;
    content:     "\f085";
    color:       rgba(98, 76 /* or 54 */, 231, .75);
}
```

EOD;



// Filter the content
$filter = new TextFilter();
$document = $filter->parse($text, ["shortcode", "markdown", "clickable", "bbcode"]);
?>

<!doctype html>
<meta charset="utf-8">
<!-- <link rel="stylesheet" href="css/geshi.css"> -->
<link rel="stylesheet" href="css/default.css">
<title>Example on TextFilter</title>
<?=$document->text?>
