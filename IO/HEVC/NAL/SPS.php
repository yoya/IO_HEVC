<?php

/*
  (c) 2017/09/21 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
*/

require_once 'IO/HEVC/Dump.php';

class IO_HEVC_NAL_SPS {
    // Table 7.3.2.2.1  (T-REC-H.265-201612) p.3
        // Table F.7.3.2.2.1  p402 (nuh_layer_id)
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
        $this->ps_seq_parameter_set_id = $bit->getUIBitsEG();
        $this->chroma_format_idc = $bit->getUIBitsEG();
        if ($this->chroma_format_idc === 3) {
            $this->separate_colour_plane_flag = $bit->getUIBit();
        }
        $this->pic_width_in_luma_samples = $bit->getUIBitsEG();
        $this->pic_height_in_luma_samples =  $bit->getUIBitsEG();
        $this->conformance_window_flag = $bit->getUIBit();
        if ($this->conformance_window_flag) {
            $this->conf_win_left_offset = $bit->getUIBitsEG();
            $this->conf_win_right_offset =  $bit->getUIBitsEG();
            $this->conf_win_top_offset = $bit->getUIBitsEG();
            $this->conf_win_bottom_offset = $bit->getUIBitsEG();
        }
        $this->bit_depth_luma_minus8 = $bit->getUIBitsEG();
        $this->bit_depth_chroma_minus8 = $bit->getUIBitsEG();
        $this->log2_max_pic_order_cnt_lsb_minus4 = $bit->getUIBitsEG();
        $this->sps_sub_layer_ordering_info_present_flag = $bit->getUIBit();
    }
    function dump() {
        $this->dump->printf($this, "    sps_video_parameter_set_id:%d sps_max_sub_layers_minus1:%d".PHP_EOL);        ;
        $this->dump->printf($this, "    sps_temporal_id_nesting_flag:%d".PHP_EOL);        ;
        $this->profile_tier_level->dump();
        $this->dump->printf($this, "    ps_seq_parameter_set_id:%d chroma_format_idc:%d".PHP_EOL);
        if ($this->chroma_format_idc === 3) {
            $this->dump->printf($this, "    separate_colour_plane_flag:%d".PHP_EOL);
        }
        $this->dump->printf($this, "    pic_width_in_luma_samples:%d pic_height_in_luma_samples:%d".PHP_EOL);
        $this->dump->printf($this, "    conformance_window_flag:%d".PHP_EOL);
        if ($this->conformance_window_flag) {
            $this->dump->printf($this, "    conf_win_left_offset:%d conf_win_right_offset:%d".PHP_EOL);
            $this->dump->printf($this, "    conf_win_top_offset:%d conf_win_bottom_offset:%d".PHP_EOL);
        }
        $this->dump->printf($this, "    bit_depth_luma_minus8:%d bit_depth_chroma_minus8:%d".PHP_EOL);
        $this->dump->printf($this, "    log2_max_pic_order_cnt_lsb_minus4:%d".PHP_EOL);
        $this->dump->printf($this, "    sps_sub_layer_ordering_info_present_flag:%d".PHP_EOL);
    }
}
