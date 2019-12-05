<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Create urls for navigation
$urlToViewBooks = url("book");



?><h1>Delete a book</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewBooks ?>">View all</a>
</p>
