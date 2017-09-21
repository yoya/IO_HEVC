<?php

class IO_HEVC_Dump {
    function printf($obj, $format) {
        preg_match_all('/(\S+:[^%]*%\S+|\s+)/', $format, $matches);
        foreach ($matches[1] as $match) {
            if (preg_match('/(\S+):([^%]*)(%\S+)/', $match , $m)) {
                $k = $m[1];
                $f = $m[3];
                if ($f === "%h") {
                    printf($m[1].":".$m[2]);
                    foreach (str_split($obj[$m[1]]) as $c) {
                        printf(" %02x", ord($c));
                    }
                } else {
                    $v = is_array($obj)?$obj[$k]:$obj->$k;
                    printf($m[1].":".$m[2].$f, $v);
                }
            } else {
                echo $match;
            }
        }
    }
}
