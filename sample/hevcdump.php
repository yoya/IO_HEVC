<?php

require_once('IO/HEVC.php');

$options = getopt("f:hvtd");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    fprintf(STDERR, "Usage: php hevcdump.php -f <hevc_file> [-htvd]\n");
    fprintf(STDERR, "ex) php hevcdump.php -f test.heic -h \n");
    fprintf(STDERR, "ex) php hevcdump.php -f test.heic -t \n");
    exit(1);
}

$filename = $options['f'];
$hevcdata = file_get_contents($filename);

$opts = array();

if (isset($options['h'])) {
    $opts['hexdump'] = true;
}
if (isset($options['t'])) {
    $opts['typeonly'] = true;
}
if (isset($options['v'])) {
    $opts['verbose'] = true;
}
if (isset($options['d'])) {
    $opts['debug'] = true;
}

$hevc = new IO_HEVC();
try {
    $hevc->parse($hevcdata, $opts);
} catch (Exception $e) {
    echo "ERROR: hevcdump: $filename:".PHP_EOL;
    echo $e->getMessage()." file:".$e->getFile()." line:".$e->getLine().PHP_EOL;
    echo $e->getTraceAsString().PHP_EOL;
    exit (1);
}



$hevc->dump($opts);
