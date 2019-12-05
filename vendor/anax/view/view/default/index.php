<!doctype html>
<html <?= $this->classList($htmlClass) ?> lang="<?= $lang ?>">
<head>

    <meta charset="<?= $charset ?>">
    <title><?= $title . $title_append ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if (isset($favicon)) : ?>
    <link rel="icon" href="<?= $this->asset($favicon) ?>">
    <?php endif; ?>

    <?php foreach ($stylesheets as $stylesheet) : ?>
    <link rel="stylesheet" type="text/css" href="<?= $this->asset($stylesheet) ?>">
    <?php endforeach; ?>

    <?php if (isset($style)) : ?>
    <style><?= $style ?></style>
    <?php endif; ?>

</head>

<body <?= $this->classList($bodyClass, $currentRoute) ?>>

<!-- wrapper around all items on page -->
<div class="wrap-all">



<!-- siteheader -->
<?php if ($this->regionHasContent("header")) : ?>
<div class="outer-wrap outer-wrap-header">
    <div class="inner-wrap inner-wrap-header">
        <div class="row">
            <header class="site-header" role="banner">
                <?php $this->renderRegion("header") ?>
            </header>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- navbar -->
<?php if ($this->regionHasContent("navbar1")) : ?>
<div class="outer-wrap outer-wrap-navbar">
    <div class="inner-wrap inner-wrap-navbar">
        <div class="row">
            <nav class="navbar1" role="navigation">
                <?php $this->renderRegion("navbar1")?>
            </nav>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- flash -->
<?php if ($this->regionHasContent("flash")) : ?>
<div class="outer-wrap outer-wrap-flash">
    <div class="inner-wrap inner-wrap-flash">
        <div class="row">
            <div class="flash-wrap">
                <?php $this->renderRegion("flash")?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- breadcrumb -->
<?php if ($this->regionHasContent("breadcrumb")) : ?>
<div class="outer-wrap outer-wrap-breadcrumb">
    <div class="inner-wrap inner-wrap-breadcrumb">
        <div class="row">
            <div class="breadcrumb-wrap">
                <nav class="breadcrumb-list" role="navigation">
                    <?php $this->renderRegion("breadcrumb")?>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- columns-above -->
<?php if ($this->regionHasContent("columns-above")) : ?>
<div class="outer-wrap outer-wrap-columns-above">
    <div class="inner-wrap inner-wrap-columns-above">
        <div class="row">
            <div class="columns-above">
                <?php $this->renderRegion("columns-above")?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- main -->
<div class="outer-wrap outer-wrap-main">
    <div class="inner-wrap inner-wrap-main">
        <div class="row">

<?php
$sidebarLeft  = $this->regionHasContent("sidebar-left");
$sidebarRight = $this->regionHasContent("sidebar-right");
$class = "";
$class .= $sidebarLeft  ? "has-sidebar-left "  : "";
$class .= $sidebarRight ? "has-sidebar-right " : "";
$class .= empty($class) ? "" : "has-sidebar";
?>

            <?php if ($sidebarLeft) : ?>
            <div class="sidebar sidebar-left <?= $class ?>" role="complementary">
                <?php $this->renderRegion("sidebar-left")?>
            </div>
            <?php endif; ?>

            <?php if ($this->regionHasContent("main")) : ?>
            <main class="main <?= $class ?>" role="main">
                <?php $this->renderRegion("main")?>
            </main>
            <?php endif; ?>

            <?php if ($sidebarRight) : ?>
            <div class="sidebar sidebar-right <?= $class ?>" role="complementary">
                <?php $this->renderRegion("sidebar-right")?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>



<!-- after-main -->
<?php if ($this->regionHasContent("after-main")) : ?>
<div class="outer-wrap outer-wrap-after-main">
    <div class="inner-wrap inner-wrap-after-main">
        <div class="row">
            <div class="after-main">
                <?php $this->renderRegion("after-main")?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- columns-below -->
<?php if ($this->regionHasContent("columns-below")) : ?>
<div class="outer-wrap outer-wrap-columns-below">
    <div class="inner-wrap inner-wrap-columns-below">
        <div class="row">
            <div class="columns-below">
                <?php $this->renderRegion("columns-below")?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



<!-- sitefooter -->
<?php if ($this->regionHasContent("footer")) : ?>
<div class="outer-wrap outer-wrap-footer" role="contentinfo">
    <div class="inner-wrap inner-wrap-footer">
        <div class="row">
            <div class="footer">
                <?php $this->renderRegion("footer")?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>



</div> <!-- end of wrapper -->



<!-- render javascripts -->
<?php if (isset($javascripts)) :
    foreach ($javascripts as $javascript) : ?>
<script async src="<?=$this->asset($javascript)?>"></script>
        <?php
    endforeach;
endif; ?>



<!-- useful for inline javascripts such as google analytics-->
<?php if ($this->regionHasContent("body-end")) : ?>
    <?php $this->renderRegion("body-end")?>
<?php endif; ?>

</body>
</html>
