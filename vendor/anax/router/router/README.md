Routes as programming style
===================

Files in this directory are included and processed to be able to add routes by programming style, instead of adding routes by configuration files.

You should use programming style OR configuration files for adding your routes.

The routes defined by the configuration files are always loaded before the programming style routes.

The following code can be used to list all routes defined, and show in what order they will be processed. It could be useful for debugging purpose, when you for some reason fail to hit the correct route handler.

List all routes with their details.

```php
// Show all routes
echo "ALL ROUTES\n";
foreach ($router->getAll() as $route) {
    echo $route->getAbsolutePath() . " : ";
    echo $route->getRequestMethod() . " : ";
    echo $route->getInfo() . "\n";
}
```

List all internal routes with their details.

```php
// Show all internal routes
echo "INTERNAL ROUTES\n";
foreach ($router->getInternal() as $route) {
    echo $route->getAbsolutePath() . " : ";
    echo $route->getInfo() . "\n";
}
```
