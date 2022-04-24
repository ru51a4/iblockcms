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
                    $c[$iblock->id]["elements"][] = $t;
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
        $els = iblock_element::whereIn('id', $ids)->get();
        $res = [];
        foreach ($els as $el) {
            $t = $el->toArray();
            $t["prop"] = [];
            foreach ($el->propvalue as $prop) {
                $t["prop"][$prop->prop->name] = $prop->value;
            }
            $res[] = $t;
        }
        return $res;
    }

    /**
     * $obj = ["name"=>"air core2dd", "prop"=>["prop1"=>"aaa"]];
     * Iblocks::addElement($obj, 1);
     */
    public static function addElement($obj, $iblockId)
    {
        $el = new iblock_element();
        $el->name = $obj["name"];
        $el->iblock_id = $iblockId;
        $el->save();
        foreach ($obj["prop"] as $key => $value) {
            $prop = new iblock_prop_value();
            $pp = iblock_property::where("name", "=", $key)->firstOrFail();
            $prop->prop_id = $pp->id;
            $prop->value = $value;
            $prop->el_id = $el->id;
            $prop->save();
        }
    }

    /**
     * $prop = ["prop1"=>"bbb"];
     * Iblocks::updateElement($prop, 16);
     */
    public static function updateElement($props, $elId)
    {
        $pp = iblock_prop_value::where("el_id", "=", $elId)->get();
        foreach ($pp as $p) {
            if (isset($props[$p->prop->name])) {
                $p->value = $props[$p->prop->name];
                $p->update();
            }
        }
    }

    public static function treeToArray($tree)
    {
        $resTree = [];
        $getTree = function ($tree, $c) use (&$getTree, &$treeKeys, &$resTree) {
            foreach ($tree as $key => $el) {
                //key - iblock_id
                if (isset($el["key"])) { //if curr iblock
                    $resTree[$key] = $c[$key];
                    $getTree($el, $c[$key]);
                }
            }
        };
        $getTree($tree, $tree);
        return $resTree;
    }
}
