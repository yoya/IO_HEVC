<?php

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
            $general_profile_idc = $bit->getUIBits(5);
            $this->general_profile_idc = $general_profile_idc;
            $general_profile_compatibility_flag = array();
            for ($i = 0 ; $i < 32 ; $i++) {
                $general_profile_compatibility_flag []= $bit->getUIBit();
            }
            $this->general_profile_compatibility_flag = $general_profile_compatibility_flag;
            $this->general_progressive_source_flag = $bit->getUIBit();
            $this->general_interlaced_source_flag = $bit->getUIBit();
            $this->general_non_packed_constraint_flag = $bit->getUIBit();
            $this->general_frame_only_constraint_flag = $bit->getUIBit();
            if (($general_profile_idc === 4) || ($general_profile_compatibility_flag[4]) ||
                ($general_profile_idc === 5) || ($general_profile_compatibility_flag[5]) ||
                ($general_profile_idc === 6) || ($general_profile_compatibility_flag[6]) ||
                ($general_profile_idc === 7) || ($general_profile_compatibility_flag[7]) ||
                ($general_profile_idc === 8) || ($general_profile_compatibility_flag[8]) ||
                ($general_profile_idc === 9) || ($general_profile_compatibility_flag[9]) ||
                ($general_profile_idc === 10) || ($general_profile_compatibility_flag[10])) {
                $this->general_max_12bit_constraint_flag = $bit->getUIBit();
                $this->general_max_10bit_constraint_flag = $bit->getUIBit();
                $this->general_max_8bit_constraint_flag = $bit->getUIBit();
                $this->general_max_422chroma_constraint_flag = $bit->getUIBit();
                $this->general_max_420chroma_constraint_flag = $bit->getUIBit();
                $this->general_max_monochrome_constraint_flag = $bit->getUIBit();
                $this->general_intra_constraint_flag = $bit->getUIBit();
                $this->general_one_picture_only_constraint_flag = $bit->getUIBit();
                $this->general_lower_bit_rate_constraint_flag = $bit->getUIBit();
                if (($general_profile_idc === 5) || ($general_profile_compatibility_flag[5]) ||
                    ($general_profile_idc === 9) || ($general_profile_compatibility_flag[9]) ||
                    ($general_profile_idc === 10) || ($general_profile_compatibility_flag[10])) {
                    $this->general_max_14bit_constraint_flag = $bit->getUIBit();
                    $general_reserved_zero_33bits = $bit->getUIBits(33);
                    if ($general_reserved_zero_33bits !== 0) {
                        throw new Exception("ERROR: general_reserved_zero_33bits:$general_reserved_zero_33bits");
                    }
                } else {
                    $general_reserved_zero_34bits = $bit->getUIBits(34);
                    if ($general_reserved_zero_34bits !== 0) {
                        throw new Exception("ERROR: general_reserved_zero_34bits:$general_reserved_zero_34bits");
                    }
                }
            } else {
                $general_reserved_zero_43bits = $bit->getUIBits(34);
                    if ($general_reserved_zero_43bits !== 0) {
                        throw new Exception("ERROR: general_reserved_zero_43bits:$general_reserved_zero_43bits");
                    }
            }
            if (((1 <= $general_profile_idc) && ($general_profile_idc <= 5)) ||
                ($general_profile_idc === 9) ||
                $general_profile_compatibility_flag[1] ||
                $general_profile_compatibility_flag[2] ||
                $general_profile_compatibility_flag[3] ||
                $general_profile_compatibility_flag[4] ||
                $general_profile_compatibility_flag[5] ||
                $general_profile_compatibility_flag[9]) {
                $this->general_inbld_flag = $bit->getUIBit();
            } else {
                $this->general_reserved_zero_bit = $bit->getUIBit();
                if ($general_reserved_zero_bit !== 0) {
                    throw new Exception("ERROR: general_reserved_zero_bit:$general_reserved_zero_bit");
                }
            }
            $this->general_level_idc = $bit->getUIBits(8);
        }
    }
    function dump() {
        echo "    profile_tier_level:".PHP_EOL;
        $this->dump->printf($this, "        ( profilePresentFlag:%d maxnumSubLayersMinus1:%d )".PHP_EOL);
        if ($this->profilePresentFlag) {
            $this->dump->printf($this, "        general_profile_space:%d general_tier_flag:%d general_profile_idc:%d".PHP_EOL);
            echo "        general_profile_compatibility_flag:";
            foreach ($this->general_profile_compatibility_flag as $flag) {
                echo " ".$flag;
            }
            echo PHP_EOL;
            $this->dump->printf($this, "        general_progressive_source_flag:%d general_interlaced_source_flag:%d".PHP_EOL);
            $this->dump->printf($this, "        general_non_packed_constraint_flag:%d general_frame_only_constraint_flag:%d".PHP_EOL);
        }
    }
}
