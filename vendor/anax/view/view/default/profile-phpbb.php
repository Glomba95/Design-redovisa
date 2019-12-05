<?php

//var_dump(get_defined_vars());
$gravatar = md5($user_email);
$gravatarHome  = "//www.gravatar.com/$gravatar";
$gravatarImage = "//www.gravatar.com/avatar/$gravatar?r=pg&amp;s=40&amp;d=wavatar";


?><span class="profile-phpbb">
    <?php if ($is_anonymous) : ?>
        <span class="login">
            <a href="/forum/ucp.php?mode=login">
                <span class="text">Login</span>
                <i class="icon fa fa-sign-in"></i>
            </a>
        </span>
    <?php else : ?>
<!--
        <span class="logout"><a href="/forum/ucp.php?mode=logout">Logout</a></span>

        <span class="ucp"><a href="/forum/ucp.php"><i class="fa fa-user fa-2x"></i></a></span>
-->
        <span class="gravatar"><a href="/forum/ucp.php"><img src="<?= $gravatarImage ?>" alt=""></a></span>
    <?php endif; ?>
</span>
