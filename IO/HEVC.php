<?php

/*
  IO_HEVC class
  (c) 2017/09/14 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
 */

    
require_once 'IO/HEVC/Bit.php';
require_once 'IO/HEVC/NAL.php';

class IO_HEVC {
    var $_nalList = null;
    var $_hevcdata = null;

    function parse($hevcdata, $opts = array()) {
        $bit = new IO_Bit();
        $bit_hevc = new IO_HEVC_Bit();
        $bit->input($hevcdata);
        $bit_hevc->input($hevcdata);
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
            list($baseOffset, $dummy) = $bit->getOffset();
            $bit_hevc->setOffset($baseOffset, 0);
            $nal->_offset = $baseOffset;
            $nal->parse($bit_hevc);
            list($offset, $dummy) = $bit->getOffset();
            $bit->setOffset($offset, 0);
            $bit->byteAlign();
            while ($bit->hasNextData(3) && $bit->getUIBits(24) !== 0x000001) {
                if ($bit->hasNextData(4)) {
                    if ($bit->getUI32BE() !== 0x00000001) {
                        $bit->incrementOffset(-4, 0);
                    } else {
                        $bit->incrementOffset(-1, 0);
                        break;
                    }
                }
                $bit->incrementOffset(-2, 0);
            }
            $bit->incrementOffset(-3, 0);
            list($nextOffset, $dummy) = $bit->getOffset();
            $nal->_length = $nextOffset - $baseOffset;
            $this->nalList[] = $nal;
        }
    }

    function getNALByType($type) {
        foreach ($this->nalList as $nal) {
            $header = $nal->header;
            if ($header["nal_unit_type"] === $type) {
                return $nal;
            }
        }
    }

    function getNALRawDataByType($type) {
        foreach ($this->nalList as $nal) {
            $header = $nal->header;
            if ($header["nal_unit_type"] === $type) {
                return substr($this->_hevcdata, $nal->_offset, $nal->_length);
            }
        }
    }

    function dump() {
        foreach ($this->nalList as $nal) {
            $nal->dump();
        }
    }
}
