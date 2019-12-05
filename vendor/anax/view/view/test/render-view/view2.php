<?php

namespace Anax\View;

/**
 * Test for a view rendering a second view.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

$item = isset($item) ? $item : null;

?>

<li><?= $item ?></li>
