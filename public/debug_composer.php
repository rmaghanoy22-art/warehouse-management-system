<?php
echo "=== Debugging Composer Autoload ===\n";

$psr4 = require __DIR__ . '/../vendor/composer/autoload_psr4.php';
echo "PSR-4 Keys:\n";
print_r(array_keys($psr4));

$classmap = require __DIR__ . '/../vendor/composer/autoload_classmap.php';
echo "Classmap Count: " . count($classmap) . "\n";

$googleClasses = [];
foreach ($classmap as $class => $path) {
    if (stripos($class, 'google') !== false) {
        $googleClasses[$class] = $path;
    }
}

echo "Google Classes in Classmap:\n";
print_r($googleClasses);

echo "Class exists Google\\Client: " . (class_exists('Google\\Client') ? 'Yes' : 'No') . "\n";
echo "Class exists Google\\Service\\Oauth2: " . (class_exists('Google\\Service\\Oauth2') ? 'Yes' : 'No') . "\n";
