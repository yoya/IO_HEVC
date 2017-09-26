<?php

require_once 'IO/Bit.php';

class IO_HEVC_ProfileTierLevel {
    // 7.3.3 profile_tier_level
    function __construct() {
        $this->dump = new IO_HEVC_Dump();
    }
    function parse($bit, $profilePresentFlag, $maxnumSubLayersMinus1) {
        $this->profilePresentFlag = $profilePresentFlag;
        $this->maxnumSubLayersMinus1 = $maxnumSubLayersMinus1;
        $profile = [];
        if ($profilePresentFlag) {
            $this->general_profile_space = $bit->getUIBits(2);
            $this->general_tier_flag = $bit->getUIBit();
            $this->general_profile_idc = $bit->getUIBits(5);
            $general_profile_compatibility_flag = array();
            for ($i = 0 ; $i < 32 ; $i++) {
                $general_profile_compatibility_flag []= $bit->getUIBit();
            }
            $this->general_profile_compatibility_flag = $general_profile_compatibility_flag;
        }
    }
    function dump() {
        echo "    profile_tier_level:".PHP_EOL;
        $this->dump->printf($this, "        profilePresentFlag:%d maxnumSubLayersMinus1:%d".PHP_EOL);
        if ($this->profilePresentFlag) {
            $this->dump->printf($this, "        general_profile_space:%d general_tier_flag:%d general_profile_idc:%d".PHP_EOL);
            echo "        general_profile_compatibility_flag:";
            foreach ($this->general_profile_compatibility_flag as $flag) {
                echo " ".$flag;
            }
            echo PHP_EOL;
        }
    }
}
