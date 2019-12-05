<?php

namespace Anax\View;

/**
 * Test for a view rendering a second view.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

$items = [1, 2, 3, 4];


?><h1>A list</h1>

<ul>
<?php foreach ($items as $item) : ?>
    <?= renderView("test/render-view/view2", ["item" => $item]); ?>
<?php endforeach; ?>
</ul>
