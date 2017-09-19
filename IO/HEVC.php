<?php

/*
  IO_HEVC class
  (c) 2017/09/14 yoya@awm.jp
  ref) https://www.itu.int/rec/T-REC-H.265
 */

    
require_once 'IO/Bit.php';
require_once 'IO/HEVC/ProfleTierLevel.php';



class IO_HEVC {
    var $_naluList = null;
    var $_hevcdata = null;
    function getUnitTypeString($type) {
        static $unitTypeTable = [
            19 => "IDR_W_RADL",
            20 => "IDR_N_LP",
            //
            32 => "VPS_NUT",
            33 => "SPS_NUT",
            34 => "PPS_NUT",
            //
            39 => "PREFIX_SEI_NUT",
            40 => "SUFFIX_SEI_NUT",
        ];
        if (isset($unitTypeTable[$type])) {
            return $unitTypeTable[$type];
        }
        return "(unknown)";
    }

    function parse($hevcdata, $opts = array()) {
        $bit = new IO_Bit();
        $bit->input($hevcdata);
        $this->_hevcdata = $hevcdata;
        $this->naluList = [];
        while ($bit->hasNextData(3)) {
            // start code prefix
            $bit->byteAlign();
            if ($bit->getUIBits(24) !== 0x000001) {
                $bit->incrementOffset(-2, 0);
                continue;
            }
            $nalu = $this->parseNALU($bit);
            $bit->byteAlign();
            while ($bit->hasNextData(3) && $bit->getUIBits(24) !== 0x000001) {
                $bit->incrementOffset(-2, 0);
            }
            $bit->incrementOffset(-3, 0);
            $this->naluList[] = $nalu;
        }
    }
    function parseNALU($bit) {
        $header = $this->parseNALU_Header($bit);
        $nalUnitType = $header["nal_unit_type"];
        echo "nalUnitType:$nalUnitType\n";
        // Table 7-1  (T-REC-H.265-201612)
        switch ($nalUnitType) {
        case 32: // VPS_NUT
            $unit = $this->parseNALU_VPS_NUT($bit);
            break;
        default:
            // case 33: // SPS_NUT
            // case 34: // PPS_NUT
            $unit = [];
            break;
        }
        return [$header, $unit];
    }

    function parseNALU_Header($bit) {
        $header = [];
        $header["forbidden_zero_bit"] = $bit->getUIBit();
        $header["nal_unit_type"] = $bit->getUIBits(6);
        $header["nuh_layer_id"] = $bit->getUIBits(6);
        $header["nuh_temporal_id_plus1"] = $bit->getUIBits(3);
        return $header;
    }
    // Table 7-3.2.1  (T-REC-H.265-201612)
    function parseNALU_VPS_NUT($bit) {
        $unit = [];
        $unit["vps_video_parameter_set_id"] = $bit->getUIBits(4);
        $unit["vps_base_layer_internal_flag"] = $bit->getUIBit();
        $unit["vps_base_layer_available_flag"] = $bit->getUIBit();
        $unit["vps_max_layers_minus1"] = $bit->getUIBits(6);
        $unit["vps_max_sub_layers_minus1"] = $bit->getUIBits(3);
        $unit["vps_temporal_id_nesting_flag"] = $bit->getUIBit();
        $vps_reserved_0xffff_16bits = $bit->getUI16BE();
        if ($vps_reserved_0xffff_16bits !== 0xffff) {
            throw new Exception("ERROR; vps_reserved_0xffff_16bits:$vps_reserved_0xffff_16bits");
        }
        $unit["profile_tier_level"] = IO_HEVC_ProfileTierLevel::parse($bit, 1, $unit["vps_max_sub_layers_minus1"]);
        $vps_sub_layer_ordering_info_present_flag = $bit->getUIBit();
        $unit["vps_sub_layer_ordering_info_present_flag"] = $vps_sub_layer_ordering_info_present_flag;    
        if ($vps_sub_layer_ordering_info_present_flag) {
            ;
        }
        // $unit[""] = $bit->getUIBit();
        return $unit;
    }
    function dump() {
        var_dump($this->naluList);
    }
}
