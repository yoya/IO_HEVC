<?php

require_once('IO/HEVC.php');

$options = getopt("f:t:");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    fprintf(STDERR, "Usage: php hevcnalu.php -f <hevc_file> -t [unittype]]\n");
    fprintf(STDERR, "ex) php hevcdump.php -f test.heic -t 19\n");
    exit(1);
}

$filename = $options['f'];
$hevcdata = file_get_contents($filename);

$opts = array();

$t = intval($options['t'], 10);

$hevc = new IO_HEVC();
try {
    $hevc->parse($hevcdata, $opts);
} catch (Exception $e) {
    echo "ERROR: hevcdump: $filename:".PHP_EOL;
    echo $e->getMessage()." file:".$e->getFile()." line:".$e->getLine().PHP_EOL;
    echo $e->getTraceAsString().PHP_EOL;
    exit (1);
}

echo $hevc->getNALRawDataByType($t);
