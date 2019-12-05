<?php
// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}

?><article <?= $this->classList($classes) ?>>
<?= $content ?>
</article>
