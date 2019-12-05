<?php
/**
 * OBSOLETE?, this view may be partly merged with default/block.
 * Its the next-prev part that is different.
 */

// Prepare classes
$classes[] = "block blog-toc";
if (isset($class)) {
    $classes[] = $class;
}

// Prepare title
$title = isset($title) && !empty($title)
    ? $title
    : t("Current posts");



?><div <?= $this->classList($classes) ?>>

    <h4><?= $title ?></h4>
    
    <ul class="toc">
        <?php foreach ($toc as $route => $item) : ?>
        <li><a href="<?= $this->url($route) ?>"><?= $item["title"] ?></a></li>
        <?php endforeach; ?>
    </ul>

    <footer>
        <?php
        $this->renderView("default/blog-toc-next-prev-page", [
            "meta" => $meta,
        ]);
        ?>
    </footer>

</div>
