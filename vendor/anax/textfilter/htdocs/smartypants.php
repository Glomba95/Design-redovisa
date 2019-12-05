<?php
require __DIR__ . "/config.php";

use \Anax\TextFilter\TextFilter;

// Prepare the content
$text = <<<EOD
Typography using SmartyPants
=================================

This is an example on typographical elements using SmartyPants.

| What          | Source    | SmartyPants |
|---------------|-----------|---------|
| Curly quotes  | \"curly\" | "curly" |
| Curly quotes  | \'curly\' | 'curly' |
| Em-dash       | \-\--     | ---     |
| En-dash       | \--       | --      |
| Ellipsis      | \...      | ...     |

EOD;



// Filter the content
$filter = new TextFilter();
$page = $filter->parse($text, ["jsonfrontmatter", "markdown"]);
?>

<!doctype html>
<meta charset="utf-8">
<style>
table {
    font-size: 2em;
    width: 100%;
}
th {
    text-align: left;
}
</style>
<title>Example on TextFilter</title>
<?= $page->text ?>
