<?php

namespace Anax\View;

/**
 * A layout rendering views in various regions.
 */

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <?php foreach ($stylesheets as $stylesheet) : ?>
    <link rel="stylesheet" type="text/css" href="<?= asset($stylesheet) ?>">
    <?php endforeach; ?>

</head>
<body>

<?php if (regionHasContent("header")) : ?>
<div class="header-wrap">
    <?php renderRegion("header") ?>
</div>
<?php endif; ?>

<?php if (regionHasContent("navbar")) : ?>
<div class="navbar-wrap">
    <?php renderRegion("navbar") ?>
</div>
<?php endif; ?>

<?php if (regionHasContent("main")) : ?>
<div class="main-wrap">
    <?php renderRegion("main") ?>
</div>
<?php endif; ?>

<?php if (regionHasContent("footer")) : ?>
<div class="footer-wrap">
    <?php renderRegion("footer") ?>
</div>
<?php endif; ?>

</body>
</html>
