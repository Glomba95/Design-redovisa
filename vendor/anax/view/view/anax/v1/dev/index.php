<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Anax development utilities</h1>

<p>Here is a set of utilities to use when learning, developing, testing and debugging Anax.</p>

<ul>
    <li><a href="<?= url("dev/di") ?>">DI (show loaded services in $di)</a></li>
    <li><a href="<?= url("dev/request") ?>">Request (show details on current request)</a></li>
    <li><a href="<?= url("dev/route") ?>">Route (show loaded routes)</a></li>
    <li><a href="<?= url("dev/session") ?>">Session (show session data)</a></li>
    <li><a href="<?= url("dev/view") ?>">View (debug and inspect views)</a></li>
</ul>
