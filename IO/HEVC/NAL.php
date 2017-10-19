<?php

/*
  (c) 2017/09/21 yoya@awm.jp 
  ref) https://www.itu.int/rec/T-REC-H.265
*/

require_once dirname(__FILE__).'/Dump.php';
require_once dirname(__FILE__).'/NAL/VPS.php';
require_once dirname(__FILE__).'/NAL/SPS.php';
require_once dirname(__FILE__).'/NAL/PPS.php';
require_once dirname(__FILE__).'/NAL/Unknown.php';

class IO_HEVC_NAL {
    function __construct() {
        $this->dump = new IO_HEVC_Dump();
    }
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
    function parse($bit) {
        $header = $this->parseNALU_Header($bit);
        $nalUnitType = $header["nal_unit_type"];
        // echo "nalUnitType:$nalUnitType\n";
        // Table 7-1  (T-REC-H.265-201612)
        switch ($nalUnitType) {
        case 32: // VPS_NUT
            $unit = new IO_HEVC_NAL_VPS();
            break;
        case 33: // SPS_NUT
            $unit = new IO_HEVC_NAL_SPS();
            break;
        case 34: // PPS_NUT
            $unit = new IO_HEVC_NAL_PPS();
            break;
        default:
            $unit = new IO_HEVC_NAL_Unknown();
            break;
        }
        $unit->parse($bit);
        $this->header = $header;
        $this->unit = $unit;
    }
    function parseNALU_Header($bit) {
        $header = [];
        $header["forbidden_zero_bit"] = $bit->getUIBit();
        $header["nal_unit_type"] = $bit->getUIBits(6);
        $header["nuh_layer_id"] = $bit->getUIBits(6);
        $header["nuh_temporal_id_plus1"] = $bit->getUIBits(3);
        return $header;
    }
    function dump() {
        $header = $this->header;
        $unit = $this->unit;
        $type = $header["nal_unit_type"];
        $typeStr = $this->getUnitTypeString($type);
        echo "header:".PHP_EOL;
        $this->dump->printf($header, "    forbidden_zero_bit:%d nal_unit_type:%d($typeStr)".PHP_EOL);
        $this->dump->printf($header, "    nuh_layer_id:%d nuh_temporal_id_plus1:%d".PHP_EOL);
        echo "unit:".PHP_EOL;
        if (isset($unit->dump)) {
            $unit->dump();
        }
    }
}


