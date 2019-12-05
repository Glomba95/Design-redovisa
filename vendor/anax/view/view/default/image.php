<?php
$class = isset($class)
    ? $class
    : null;

$alt = isset($alt)
    ? " alt=\"$alt\""
    : null;

$href = isset($href)
    ? " href=\"$href\""
    : null;

$title = isset($title)
    ? " title=\"$title\""
    : null;

$hrefStart = null;
$hrefEnd = null;
if (isset($href)) {
    $hrefStart = "<a $href $title>";
    $hrefEnd = "</a>";
}

?><?= $hrefStart ?>
<img <?= $this->classList($class) ?> src="<?= $this->asset($src) ?>"<?= $alt ?>>
<?= $hrefEnd ?>
