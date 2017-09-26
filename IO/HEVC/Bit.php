
<?php

/*
  IO_HEVC class
  (c) 2017/09/14 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
 */

    
require_once 'IO/Bit.php';
require_once 'IO/HEVC/NAL.php';

class IO_HEVC_Bit extends IO_Bit {
    private function skipEulationPrevention() {
        if (($this->_bit_offset === 0) && (2 <= $this->_byte_offset) &&
            (substr($this->_data, $this->_byte_offset - 2, 3) === "\0\0\3")) {
            $this->_byte_offset++;
        }
    }        
    function getUI8() {
        $this->skipEulationPrevention();
        return parent::getUI8();
    }
    function getUIBit() {
        $this->skipEulationPrevention();
        return parent::getUIBit();
    }
}


