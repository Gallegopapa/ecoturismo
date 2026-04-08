<?php

$dirs = ['app', 'config', 'routes', 'database', 'public'];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            
            $changed = false;
            
            // Remove BOM
            if (strpos($content, "\xEF\xBB\xBF") === 0) {
                echo "BOM removed: $path\n";
                $content = substr($content, 3);
                $changed = true;
            }
            
            // Remove leading whitespace before <?php
            if (preg_match('/^\s+<\?php/', $content)) {
                echo "Whitespace removed: $path\n";
                $content = preg_replace('/^\s+<\?php/', "<?php", $content);
                $changed = true;
            }
            
            if ($changed) {
                file_put_contents($path, $content);
            }
        }
    }
}
echo "Done scanning and fixing.\n";
