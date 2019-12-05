<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?><navbar>
    <a href="<?= url("") ?>">Home</a> |
    <a href="<?= url("dev") ?>">Development</a>
</navbar>
