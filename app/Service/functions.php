<?php

namespace App\Service;


class functions
{
    public static function getOpItem($iblockId)
    {
        $res = Iblocks::GetList($iblockId, $iblockId, 5, false, [["prop" => "is_op", "type" => "=", "value" => "1"]]);
        if (isset($res[$iblockId]["elements"][0])) {
            return $res[$iblockId]["elements"][0];
        }
        return [];
    }
}
