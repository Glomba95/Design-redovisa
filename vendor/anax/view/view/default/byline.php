<?php
// Prepare classes
$classes[] = "author-byline";
if (isset($class)) {
    $classes[] = $class;
}


foreach ($author as $val) :
    $byline = null;
    extract($val);
    if (!isset($byline)) {
        continue;
    }
    ?><div <?= $this->classList($classes) ?>>
    <?= $byline ?>
</div>
<?php endforeach;
