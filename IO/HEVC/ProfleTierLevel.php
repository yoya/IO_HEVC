<?php

require_once dirname(__FILE__).'/Dump.php';

class IO_HEVC_ProfileTierLevel {
    // 7.3.3 profile_tier_level
    function __construct() {
        $this->dump = new IO_HEVC_Dump();
    }
    function parse($bit, $profilePresentFlag, $maxNumSubLayersMinus1) {
        $this->profilePresentFlag = $profilePresentFlag;
        $this->maxNumSubLayersMinus1 = $maxNumSubLayersMinus1;
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
                    $this->general_max_14bit_constraint_flag =
                                                             $bit->getUIBit();
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
                $general_reserved_zero_43bits = $bit->getUIBits(43);
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
            $sub_layer_profile_present_flag = array();
            $sub_layer_level_present_flag = array();
            for ($i = 0 ; $i < $maxNumSubLayersMinus1 ; $i++) {
                $sub_layer_profile_present_flag []= $bit->getUIBit();
                $sub_layer_level_present_flag []= $bit->getUIBit();
            }
            $this->sub_layer_profile_present_flag = $sub_layer_profile_present_flag;
            $this->sub_layer_level_present_flag = $sub_layer_level_present_flag;
            if (0 < $maxNumSubLayersMinus1) {
                for ($i = $maxNumSubLayersMinus1; $i < 8; $i++) {
                    $reserved_zero_2bits = $bit->getUIBits(2);
                    if ($reserved_zero_2bits !== 0) {
                        throw new Exception("ERROR: reserved_zero_2bits:$reserved_zero_2bits");
                    }
                }
            }
            for ($i = 0; $i < $maxNumSubLayersMinus1; $i++ ) {
                // TODO
                throw new Exception("ERROR: not implemented yet. 0 < maxNumSubLayersMinus1:$maxNumSubLayersMinus1 ");
            }
        }
    }
    function dump() {
        echo "    profile_tier_level:";
        $profilePresentFlag = $this->profilePresentFlag;
        $maxNumSubLayersMinus1 = $this->maxNumSubLayersMinus1;
        $this->dump->printf($this, " profilePresentFlag:%d maxNumSubLayersMinus1:%d".PHP_EOL);
        if ($profilePresentFlag) {
            $general_profile_idc = $this->general_profile_idc;
            $this->dump->printf($this, "        general_profile_space:%d general_tier_flag:%d general_profile_idc:%d".PHP_EOL);
            echo "        general_profile_compatibility_flag:";
            $general_profile_compatibility_flag = $this->general_profile_compatibility_flag;
            foreach ($general_profile_compatibility_flag as $i => $flag) {
                if (($i%8) === 0) {
                    echo " ";
                }
                echo $flag;
            }
            echo PHP_EOL;
            $this->dump->printf($this, "        general_progressive_source_flag:%d general_interlaced_source_flag:%d".PHP_EOL);
            $this->dump->printf($this, "        general_non_packed_constraint_flag:%d".PHP_EOL);
            $this->dump->printf($this, "        general_frame_only_constraint_flag:%d".PHP_EOL);
            if (($general_profile_idc === 4) || ($general_profile_compatibility_flag[4]) ||
                ($general_profile_idc === 5) || ($general_profile_compatibility_flag[5]) ||
                ($general_profile_idc === 6) || ($general_profile_compatibility_flag[6]) ||
                ($general_profile_idc === 7) || ($general_profile_compatibility_flag[7]) ||
                ($general_profile_idc === 8) || ($general_profile_compatibility_flag[8]) ||
                ($general_profile_idc === 9) || ($general_profile_compatibility_flag[9]) ||
                ($general_profile_idc === 10) || ($general_profile_compatibility_flag[10])) {
                $this->dump->printf($this, "        general_max_12bit_constraint_flag:%d general_max_10bit_constraint_flag:%d general_max_8bit_constraint_flag:%d".PHP_EOL);
                $this->dump->printf($this, "        general_max_420chroma_constraint_flag:%d general_max_monochrome_constraint_flag:%d".PHP_EOL);
                $this->dump->printf($this, "        general_intra_constraint_flag:%d general_one_picture_only_constraint_flag:%d general_lower_bit_rate_constraint_flag:%d".PHP_EOL);
                if (($general_profile_idc === 5) || ($general_profile_compatibility_flag[5]) ||
                    ($general_profile_idc === 9) || ($general_profile_compatibility_flag[9]) ||
                    ($general_profile_idc === 10) || ($general_profile_compatibility_flag[10])) {
                    $this->dump->printf($this, "        general_max_14bit_constraint_flag:%d".PHP_EOL);
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
                $this->dump->printf($this, "        general_inbld_flag:%d".PHP_EOL);
            }
            $this->dump->printf($this, "        general_level_idc:%d".PHP_EOL);
            echo "        sub_layer_profile_present_flag, sub_layer_level_present_flag: (count:".$maxNumSubLayersMinus1.")".PHP_EOL;
            for ($i = 0 ; $i < $maxNumSubLayersMinus1 ; $i++) {
                echo "            ".$this->sub_layer_profile_present_flag[$i].", ".$this->sub_layer_level_present_flag[$i].PHP_EOL;
            }
        }
    }
}
