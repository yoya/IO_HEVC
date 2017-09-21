<?php

/*
  (c) 2017/09/21 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
*/

require_once 'IO/Bit.php';
require_once 'IO/HEVC/Dump.php';

class IO_HEVC_NAL_PPS {
    // Table 7.3.2.3.1  (T-REC-H.265-201612)
    // pic_parameter_set_rbsp
    function __construct() {
        $this->dump = new IO_HEVC_Dump();
    }
    function parse($bit) {
        ;
    }
    function dump() {
        ;
    }
}
