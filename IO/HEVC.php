<?php

/*
  IO_HEVC class
  (c) 2017/09/14 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
 */

    
require_once 'IO/Bit.php';
require_once 'IO/HEVC/NAL.php';

class IO_HEVC {
    var $_nalList = null;
    var $_hevcdata = null;

    function parse($hevcdata, $opts = array()) {
        $bit = new IO_Bit();
        $bit->input($hevcdata);
        $this->_hevcdata = $hevcdata;
        $this->nalList = [];
        while ($bit->hasNextData(3)) {
            // start code prefix
            $bit->byteAlign();
            if ($bit->getUIBits(24) !== 0x000001) {
                $bit->incrementOffset(-2, 0);
                continue;
            }
            $nal = new IO_HEVC_NAL();
            $nal->parse($bit);
            $bit->byteAlign();
            while ($bit->hasNextData(3) && $bit->getUIBits(24) !== 0x000001) {
                $bit->incrementOffset(-2, 0);
            }
            $bit->incrementOffset(-3, 0);
            $this->nalList[] = $nal;
        }
    }

    function dump() {
        foreach ($this->nalList as $nal) {
            $header = $nal->header;
            $unit = $nal->unit;
            $type = $header["nal_unit_type"];
            $typeStr = $nal->getUnitTypeString($type);
            echo "$type($typeStr)\n";
        }
    }
}
