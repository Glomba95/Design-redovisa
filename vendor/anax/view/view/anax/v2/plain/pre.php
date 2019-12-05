<?php

namespace Anax\View;

/**
 * Render plain content.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

// Prepare classes
$classes[] = "article";
if (isset($class)) {
    $classes[] = $class;
}


?><pre <?= classList($classes) ?>><?= $content ?></pre>
