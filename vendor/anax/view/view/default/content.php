<?php
// Prepare classes
$classes[] = "content";
if (isset($class)) {
    $classes[] = $class;
}

?><div <?= $this->classList($classes) ?>>
<?= $content ?>
</div>
