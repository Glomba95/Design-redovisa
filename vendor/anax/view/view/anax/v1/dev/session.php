<?php

namespace Anax\View;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$session = $di->get("session");



?><h1>Session</h1>

<p>The session contains the following data.</p>

<pre><?= var_dump($session) ?></pre>
