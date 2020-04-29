<?php
/**
 * Created by gurikin
 * At 29.04.2020 10:32
 */
$prefix = 'src';
$appDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $prefix;
$dirIt = new \RecursiveDirectoryIterator($appDir,RecursiveDirectoryIterator::SKIP_DOTS);
$dirIt->rewind();
$notUsingDirs = ['scripts', 'css'];
while($dirIt->valid()) {
    if ($dirIt->isFile() || in_array($dirIt->getSubPathname(), $notUsingDirs)) {
        $dirIt->next();
        continue;
    }
    set_include_path(get_include_path()
        . PATH_SEPARATOR . $prefix . DIRECTORY_SEPARATOR . $dirIt->getSubPathname());
    $dirIt->next();
}