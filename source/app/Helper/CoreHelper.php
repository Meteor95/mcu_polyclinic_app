<?php

foreach (glob(__DIR__ . '/*.php') as $file) {
    if (basename($file) !== 'CoreHelper.php') {
        require_once $file;
    }
}
