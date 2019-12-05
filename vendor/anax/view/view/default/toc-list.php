<?php
/**
 * Renders a ul li view based on a toc array.
 */



?><ul>

    <?php foreach ($toc as $route => $link) :
        $url  = $this->url($route);
        $text = $link["title"]; // Should be text?
        $title = null; // Missmatch with $link["title"]
        /*
        $title = isset($link["title"])
            ? " title=\"${link["title"]}\""
            : null; */
        ?>
    <li><a href="<?= $url ?>"<?= $title ?>><?= $text ?></a></li>
    <?php endforeach; ?>

</ul>
