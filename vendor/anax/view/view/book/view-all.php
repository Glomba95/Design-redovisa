<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$books = isset($books) ? $books : null;

// Create urls for navigation
$urlToCreate = url("book/create");
$urlToDelete = url("book/delete");



?><h1>View all books</h1>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> | 
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$books) : ?>
    <p>There are no books to show.</p>
    <?php return;
endif;
?>

<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Author</th>
    </tr>
    <?php foreach ($books as $book) : ?>
    <tr>
        <td>
            <a href="<?= url("book/update/{$book->id}"); ?>"><?= $book->id ?></a>
        </td>
        <td><?= $book->title ?></td>
        <td><?= $book->author ?></td>
    </tr>
    <?php endforeach; ?>
</table>
