<?php
/**
 * Routes to ease development and debugging.
 */
return [
    // Path where to mount the routes, is added to each route path.
    "mount" => "dev",

    // All routes in order
    "routes" => [
        [
            "info" => "Development and debugging information.",
            "path" => "",
            "handler" => function ($di) {
                echo <<<EOD
<h1>Anax development utilities</h1>

<p>Here is a set of utilities to use when learning, developing, testing and debugging Anax.</p>

<ul>
    <li><a href="di">DI (show loaded services in \$di)</a></li>
    <li><a href="request">Request (show details on current request)</a></li>
    <li><a href="router">Router (show loaded routes)</a></li>
    <li><a href="session">Session (show session data)</a></li>
</ul>
EOD;
                return true;
            },
        ],
        [
            "info" => "DI (show loaded services in \$di).",
            "path" => "di",
            "handler" => function ($di) {
                $services       = $di->getServices();
                $activeServices = $di->getActiveServices();
                $items = "";
                foreach ($services as $service) {
                    $active = in_array($service, $activeServices);
                    $boldStart = $active ? "<b>" : null;
                    $boldEnd = $active ? "<b>" : null;
                    $items .= "<li>${boldStart}${service}${boldEnd}</li>";
                };

                echo <<<EOD
<h1>DI and services</h1>

<p>These services are loaded into \$di, bold services are activated.

<ul>
$items
</ul>
EOD;
                return true;
            },
        ],
        [
            "info" => "Request (show details on current request)",
            "path" => "request",
            "handler" => function ($di) {
                $request = $di->get("request");
                $routeParts = "[ " . implode(", ", $request->getRouteParts()) . " ]";

                echo <<<EOD
<h1>Request</h1>

<p>Here are details on the current request.</p>

<table>
    <tr>
        <th>Method</th>
        <th>Result</th>
    </tr>
    <tr>
        <td><code>getCurrentUrl()</code></td>
        <td><code>{$request->getCurrentUrl()}</code></td>
    </tr>
    <tr>
        <td><code>getMethod()</code></td>
        <td><code>{$request->getMethod()}</code></td>
    </tr>
    <tr>
        <td><code>getSiteUrl()</code></td>
        <td><code>{$request->getSiteUrl()}</code></td>
    </tr>
    <tr>
        <td><code>getBaseUrl()</code></td>
        <td><code>{$request->getBaseUrl()}</code></td>
    </tr>
    <tr>
        <td><code>getScriptName()</code></td>
        <td><code>{$request->getScriptName()}</code></td>
    </tr>
    <tr>
        <td><code>getRoute()</code></td>
        <td><code>{$request->getRoute()}</code></td>
    </tr>
    <tr>
        <td><code>getRouteParts()</code></td>
        <td><code>$routeParts</code></td>
    </tr>
</table>
EOD;
                return true;
            },
        ],
        [
            "info" => "Router (show loaded routes)",
            "path" => "router",
            "handler" => function ($di) {
                $router = $di->get("router");

                $routes = "";
                foreach ($router->getAll() as $route) {
                    $routes .= <<<EOD
<tr>
    <td><code>"{$route->getAbsolutePath()}"</code></td>
    <td><code>{$route->getRequestMethod()}</code></td>
    <td>{$route->getInfo()}</td>
</tr>
EOD;
                }

                $internal = "";
                foreach ($router->getInternal() as $route) {
                    $internal .= <<<EOD
<tr>
    <td><code>"{$route->getAbsolutePath()}"</code></td>
    <td>{$route->getInfo()}</td>
</tr>
EOD;
                }

                echo <<<EOD
<h1>Router</h1>

<p>The following routes are loaded:</p>

<table>
    <tr><th>Path</th><th>Method</th><th>Description</th></tr>
$routes
</table>

<p>The following internal routes are loaded:</p>

<table>
    <tr><th>Path</th><th>Description</th></tr>
$internal
</table>
EOD;
                return true;
            },
        ],
        [
            "info" => "Session (show session data).",
            "path" => "session",
            "handler" => function ($di) {
                $mount = "session/";
                $session = $di->get("session");
                $data = print_r($session->__debugInfo(), 1);

                echo <<<EOD
<h1>Session</h1>

<p>The session contains the following data.</p>

<pre>$data</pre>

<p>
    <a href="${mount}increment">Add to session and increment<a> |
    <a href="${mount}destroy">Destroy session<a>
</p>
EOD;
                return true;
            },
        ],
        [
            "info" => "Add +1 to session.",
            "path" => "session/increment",
            "handler" => function ($di) {
                echo "<h1>Session increment</h1>\n";
                $session = $di->get("session");
                $number = $session->get("number", 0);
                $session->set("number", $number + 1);
                var_dump($session);
                return "Reload page to increment 'number' in the session.";
            },
        ],
        [
            "info" => "Destroy the session.",
            "path" => "session/destroy",
            "handler" => function ($di) {
                echo "<h1>Session destroy</h1>\n";
                $session = $di->get("session");
                var_dump($session);
                $session->destroy();
                var_dump($session);
                return "The session was destroyed.";
            },
        ],
    ]
];
