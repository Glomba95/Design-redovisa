<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$book = isset($book) ? $book : null;

// Create urls for navigation
$urlToViewBooks = url("book");



?><h1>Update a book</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewBooks ?>">View all</a>
</p>
