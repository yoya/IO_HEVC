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
    function getUIBitsEG() { // 0-th order Exp-Golomb
        $e = 0;
        while ($this->getUIBit() === 0) { $e++ ; }
        return pow(2, $e) + parent::getUIBits($e) - 1;
    }
    function getSIBitsEG() {
        $value = $this->getUIBitsEG();
        if ($value & 1) {
            return ($value + 1) / 2; // odd number => positive
        }
        return - $value / 2; // even number => negative
    }
}
