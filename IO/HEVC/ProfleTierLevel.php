<?php

require_once 'IO/Bit.php';

class IO_HEVC_ProfileTierLevel {
    function parse($bit, $profilePresentFlag, $maxnumSubLayersMinus1) {
        $this->profilePresentFlag = $profilePresentFlag;
        $this->maxnumSubLayersMinus1 = $maxnumSubLayersMinus1;
        $profile = [];
        if ($profilePresentFlag) {
            
        }
        $maxnumSubLayersMinus1;
        return [];
    }
    function dump() {
        ;
    }
}
