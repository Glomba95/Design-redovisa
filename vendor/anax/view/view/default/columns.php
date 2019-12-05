<?php
// Prepare the classes and allow $column to add own $class.
$outerClass = isset($class) ? $class : null;
$class = null;

$classes = isset($classes) ? $classes : null;



?><div <?= $this->classList("columns $outerClass-wrapper", $classes) ?>>

<?php if (isset($title)) : ?>
    <h2><?= $title ?></h2>
<?php endif; ?>


<?php $i = 1; foreach ($columns as $column) :
    $template = isset($column["template"])
        ? $column["template"]
        : "default/block";
    ?>
    <div <?= $this->classList("column $outerClass") ?>>

        <?php
        $column["classes"] = ["$outerClass-x", "$outerClass-$i"];
        $data = isset($column["data"])
            ? $column["data"]
            : $column;
        $this->renderView($template, $data);
        ?>

    </div>
    <?php $i++;
endforeach; ?>

</div>
