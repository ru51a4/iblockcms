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

    public static function getParams()
    {
        $url = $_GET;
        $urls = '?';
        $count = 0;
        foreach ($url as $key => $item) {
            $urls .= ($count++ !== 0) ? "&" . htmlspecialchars($key) . "=" . htmlspecialchars($item) : htmlspecialchars($key) . "=" . htmlspecialchars($item);

        }
        return $urls;
    }
}
