<!doctype html>
<meta charset="utf-8">
<title>Example on Mos\TextFilter</title>
<p>Checking for installed extensions and utilities.</p>
<p>JSON is <?= (function_exists("json_encode") ? "" : "NOT ") ?> available</p>
<p>YAML is <?= (function_exists("yaml_parse") ? "" : "NOT ") ?> available</p>
