<?php

namespace App\Service;

use App\Models\iblock;
use App\Models\iblock_element;
use App\Models\iblock_property;
use App\Models\iblock_prop_value;


class Iblocks
{
    public static function getBreadcrumbIblock($iblock)
    {
        $res = [["name" => $iblock->name, "id" => $iblock->id]];
        while ($iblock->parrent_id != 0) {
            $iblock = iblock::find($iblock->parrent_id);
            $res[] = ["name" => $iblock->name, "id" => $iblock->id];
        }
        return array_reverse($res);
    }

    public static function getPropsParrents($iblock)
    {
        $res = [];
        foreach ($iblock->properties as $prop) {
            $res[] = $prop;
        }
        while ($iblock->parrent_id != 0) {
            $iblock = iblock::find($iblock->parrent_id);
            foreach ($iblock->properties as $prop) {
                $res[] = $prop;
            }
        }
        return $res;
    }

    public static function GetList($iblockID, $elId = false)
    {
        $stack = [$iblockID];
        $res = [];
        $getChilds = function ($iblock, &$c) use (&$getChilds, &$stack, $elId) {
            $c[$iblock->id]["key"] = $iblock->name;
            $c[$iblock->id]["path"] = $stack;
            //
            if ($iblock->id == $elId || !$elId) {
                $els = $iblock->elements;
                foreach ($els as $el) {
                    $t = $el->toArray();
                    $t["prop"] = [];
                    foreach ($el->propvalue as $prop) {
                        $t["prop"][$prop->prop->name] = $prop->value;
                    }
                    $c[$iblock->id][] = $t;
                }
            }
            //
            $childs = iblock::where("parrent_id", "=", $iblock->id)->get();
            foreach ($childs as $child) {
                $stack[] = $child->id;
                $getChilds($child, $c[$iblock->id]);
                array_pop($stack);
            }
        };

        $iblock = iblock::find($iblockID);
        $getChilds($iblock, $res);
        return $res;
    }

    public static function ElementsGetList($ids)
    {
        return iblock_element::with("propvalue.prop")->whereIn('id', $ids)->get();
    }
}
