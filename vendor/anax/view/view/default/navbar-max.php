<!-- menu wrapper -->
<div class="rm-navbar-max rm-navbar rm-max rm-swipe-right">

    <!-- memu click button -->
    <div class="rm-small-wrapper">
        <ul class="rm-small">
            <li><a id="rm-menu-button" class="rm-button" href="#">
                <i class="fa fa-bars rm-button-face-1"></i>
                <i class="fa fa-times rm-button-face-2"></i>
            </a></li>
        </ul>
    </div>

    <!-- main menu -->
    <?= $this->di->get("navbar")->create("navbarMax") ?>

</div>
