<?php
// Script para convertir referencias en Blade views a route() y asset().
// Uso: php scripts/convert_views.php

$baseDir = realpath(__DIR__ . '/..');
$viewsDir = $baseDir . '/resources/views';
$routesOut = $baseDir . '/routes/views_generated.php';

$viewFiles = glob($viewsDir . '/*.blade.php');

$routeEntries = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n";

// Build a set of available view basenames
$viewsSet = [];
foreach ($viewFiles as $vf) {
    $name = basename($vf, '.blade.php');
    $viewsSet[$name] = $vf;
}

foreach ($viewFiles as $file) {
    $basename = basename($file, '.blade.php');
    $routeEntries .= "Route::view('/$basename', '$basename')->name('$basename');\n";

    $content = file_get_contents($file);

    // 1) Replace href="something.html" when something corresponds to a view
    $content = preg_replace_callback('/href\s*=\s*"([^"]+?)\.html"/i', function($m) use ($viewsSet) {
        $target = basename($m[1]);
        if (isset($viewsSet[$target])) {
            return 'href="{{ route(\'' . $target . '\') }}"';
        }
        return $m[0];
    }, $content);

    // 2) Replace href="something.blade.php"
    $content = preg_replace_callback('/href\s*=\s*"([^"]+?)\.blade\.php"/i', function($m) use ($viewsSet) {
        $target = basename($m[1]);
        if (isset($viewsSet[$target])) {
            return 'href="{{ route(\'' . $target . '\') }}"';
        }
        return $m[0];
    }, $content);

    // 3) Replace simple relative hrefs that match a view basename (href="contacto")
    $content = preg_replace_callback('/href\s*=\s*"([a-zA-Z0-9_\-]+)"/i', function($m) use ($viewsSet) {
        $target = $m[1];
        if (isset($viewsSet[$target])) {
            return 'href="{{ route(\'' . $target . '\') }}"';
        }
        return $m[0];
    }, $content);

    // 4) Replace src="..." for local assets (not starting with http, //, /, or {{ )
    $content = preg_replace_callback('/src\s*=\s*"(?!https?:|\/\/|\/|\{\{)([^"]+)"/i', function($m) {
        $path = $m[1];
        return 'src="{{ asset(\'' . $path . '\') }}"';
    }, $content);

    // 5) Replace link href to css for local assets
    $content = preg_replace_callback('/href\s*=\s*"(?!https?:|\/\/|\/|\{\{)([^"]+\.css)"/i', function($m) {
        $path = $m[1];
        return 'href="{{ asset(\'' . $path . '\') }}"';
    }, $content);

    // 6) Replace script src local
    $content = preg_replace_callback('/<script([^>]*)src\s*=\s*"(?!https?:|\/\/|\/|\{\{)([^"]+)"/i', function($m) {
        $attrs = $m[1];
        $path = $m[2];
        return '<script' . $attrs . 'src="{{ asset(\'' . $path . '\') }}"';
    }, $content);

    // 7) For safety: do not alter external links (mailto:, tel:) — handled by negative lookaheads

    // Write back file only if changed
    if ($content !== file_get_contents($file)) {
        file_put_contents($file, $content);
        echo "Updated: $basename\n";
    }
}

// Write routes file
file_put_contents($routesOut, $routeEntries);
echo "Routes written to routes/views_generated.php\n";

echo "Done. Revisa los cambios y prueba el servidor: php artisan serve\n";

?>