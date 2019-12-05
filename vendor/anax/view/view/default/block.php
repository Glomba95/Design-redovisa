<?php
// Prepare classes
$classes[] = "block";
if (isset($class)) {
    $classes[] = $class;
}

// Load content details from route
if (isset($contentRoute)) {
    extract($this->getContentForRoute($contentRoute));
}

//var_dump(get_defined_vars());

// Prepare title
$title = isset($title) && !empty($title)? $title : null;
$header = isset($header) ? $header : $title;

// Prepare content into text
$content = isset($content) ? $content : null;
$text = isset($text) ? $text : $content;



?><div <?= $this->classList($classes) ?>>

    <?php if (isset($header)) : ?>
        <h4><?= $header ?></h4>
    <?php endif; ?>

    <?php if (isset($text)) : ?>
        <?= $text ?>
    <?php endif; ?>

    <?php if (isset($links)) :
        $this->renderView("default/link-list", [
            "links" => $links
        ]);
    endif; ?>

    <?php if (isset($toc)) :
        $this->renderView("default/toc-list", [
            "toc" => $toc
        ]);
    endif; ?>

</div>
