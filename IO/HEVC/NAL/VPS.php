<?php

/*
  (c) 2017/09/14 yoya@awm.jp 
  (c) 2017/09/21 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
*/

require_once 'IO/Bit.php';
require_once 'IO/HEVC/ProfleTierLevel.php';

class IO_HEVC_NAL_VPS {
    // Table 7-3.2.1  (T-REC-H.265-201612)
    // video_parameter_set_rbsp
    function parse($bit) {
        $this->vps_video_parameter_set_id = $bit->getUIBits(4);
        $this->vps_base_layer_internal_flag = $bit->getUIBit();
        $this->vps_base_layer_available_flag = $bit->getUIBit();
        $this->vps_max_layers_minus1 = $bit->getUIBits(6);
        $this->vps_max_sub_layers_minus1 = $bit->getUIBits(3);
        $this->vps_temporal_id_nesting_flag = $bit->getUIBit();
        $vps_reserved_0xffff_16bits = $bit->getUI16BE();
        if ($vps_reserved_0xffff_16bits !== 0xffff) {
            throw new Exception("ERROR; vps_reserved_0xffff_16bits:$vps_reserved_0xffff_16bits");
        }
        $this->profile_tier_level = new IO_HEVC_ProfileTierLevel();
        $this->profile_tier_level->parse($bit, 1, $this->vps_max_sub_layers_minus1);
        $vps_sub_layer_ordering_info_present_flag = $bit->getUIBit();
        $this->vps_sub_layer_ordering_info_present_flag = $vps_sub_layer_ordering_info_present_flag;
        if ($vps_sub_layer_ordering_info_present_flag) {
            ;
        }
    }
    function dump() {
        ;
    }
}
