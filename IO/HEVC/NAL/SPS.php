<?php

/*
  (c) 2017/09/21 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
*/

require_once 'IO/HEVC/Dump.php';

class IO_HEVC_NAL_SPS {
    // Table F.7.3.2.2.1  (T-REC-H.265-201612)
    // seq_parameter_set_rbsp
    function __construct() {
        $this->dump = new IO_HEVC_Dump();
    }
    function parse($bit) {
        $this->sps_video_parameter_set_id = $bit->getUIBits(4);
        $this->sps_max_sub_layers_minus1 = $bit->getUIBits(3);
        $this->sps_temporal_id_nesting_flag = $bit->getUIBit();
        $this->profile_tier_level = new IO_HEVC_ProfileTierLevel();
        $this->profile_tier_level->parse($bit, 1, $this->sps_max_sub_layers_minus1);
    }
    function dump() {
        $this->dump->printf($this, "    sps_video_parameter_set_id:%d sps_max_sub_layers_minus1:%d".PHP_EOL);        ;
        $this->dump->printf($this, "    sps_temporal_id_nesting_flag:%d".PHP_EOL);        ;
        $this->profile_tier_level->dump();
    }
}
