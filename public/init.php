<?php
$zip      = new ZipArchive();
$rootPath = '../';
$envFile  = $rootPath . '.env';

/**
 * @param $path
 */
function changeMods($path)
{
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

    foreach ($iterator as $item) {
        @chmod($item, 0775);
    }
}

if (!file_exists($envFile)) {
    // begin installation
    copy($envFile . '.example', $envFile);

    @changeMods(realpath($rootPath . '/storage/framework/'));
    @changeMods(realpath($rootPath . '/storage/logs/'));
    @changeMods(realpath($rootPath . '/bootstrap/cache/'));

    // generate APP_ENV and API_KEY
    $file = file_get_contents($envFile);
    $file = preg_replace('/APP_KEY=.*/', 'APP_KEY=base64:' . base64_encode(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 32)), $file, 1);
    $file = preg_replace('/API_KEY=.*/', 'API_KEY=' . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 32), $file, 1);
    file_put_contents($envFile, $file);
}

header('Location: install');
exit();
