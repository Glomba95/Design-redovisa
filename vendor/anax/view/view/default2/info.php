<?php
$router = $di->get("router");

$services       = $di->getServices();
$activeServices = $di->getActiveServices();



?><h1>Anax info</h1>

<h2>Routes loaded</h2>

<p>The following routes are loaded:</p>
<table>
    <tr><th>Path</th><th>Method</th><th>Description</th></tr>
<?php foreach ($router->getAll() as $route) : ?>
    <tr>
        <td><code>"<?= $route->getRule() ?>"</code></td>
        <td><code><?= $route->getRequestMethod() ?></code></td>
        <td><?= $route->getInfo() ?></td>
    </tr>
<?php endforeach; ?>
</table>

<p>The following internal routes are loaded:</p>
<ul>
<?php foreach ($router->getInternal() as $route) : ?>
    <li><?= $route->getRule() ?></li>
<?php endforeach; ?>
</ul>



<h2>DI and services</h2>

<p>These services are loaded into DI and bold are currently activated.
<ul>
<?php foreach ($services as $service) :
    $active = in_array($service, $activeServices); ?>
    <li><?= $active ? "<b>" : null ?><?= $service ?><?= $active ? "</b>" : null ?></li>
<?php endforeach; ?>
</ul>
