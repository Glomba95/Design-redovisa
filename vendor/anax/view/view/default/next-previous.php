<?php
// Prepare classes
$classes[] = "next-previous";
if (isset($class)) {
    $classes[] = $class;
}


// Prepare titles
$nextTitle = $next["title"];
$prevTitle = $previous["title"];

if (isset($nextPreviousTitle) && $nextPreviousTitle === false) {
    $nextTitle = t("Next");
    $prevTitle = t("Previous");
}



?><div <?= $this->classList($classes) ?>>
    <?php if (isset($next)) : ?>
    <div class="next"><a href="<?= $this->url($next["route"]) ?>"><?= $nextTitle ?></a> »</div>
    <?php endif; ?>

    <?php if (isset($previous)) : ?>
    <div class="previous">« <a href="<?= $this->url($previous["route"]) ?>"><?= $prevTitle ?></a></div>
    <?php endif; ?>
</div>
