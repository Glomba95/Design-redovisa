<?php
/**
 * Renders a ul li view based on a general links array.
 */



?><ul>

    <?php foreach ($links as $link) :
        $url  = $this->url($link["url"]);
        $text = $link["text"];
        $title = isset($link["title"])
            ? " title=\"${link["title"]}\""
            : null;
        ?>
    <li><a href="<?= $url ?>"<?= $title ?>><?= $text ?></a></li>
    <?php endforeach; ?>

</ul>
