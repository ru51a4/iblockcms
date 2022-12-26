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

    public static function getPropsParrents($iblock, $is_admin = false)
    {
        $res = [];
        foreach ($iblock->properties as $prop) {
            if ($iblock->id == 1 && !$is_admin) {
                continue;
            }
            $res[] = $prop;
        }
        while ($iblock->parrent_id != 0) {
            $iblock = iblock::find($iblock->parrent_id);
            foreach ($iblock->properties as $prop) {
                if ($iblock->id == 1 && !$is_admin) {
                    continue;
                }
                $res[] = $prop;
            }
        }
        return $res;
    }

    public static function getAllProps($iblock)
    {
        $res = [];
        foreach (self::getPropsParrents(iblock::find($iblock)) as $c) {
            $res[] = $c;
        }
        $deep = function ($childs) use (&$res, &$deep) {
            foreach ($childs as $child) {
                foreach ($child->properties as $prop) {
                    $res[] = $prop;
                }
            }
            foreach ($childs as $child) {
                $c = iblock::where("parrent_id", "=", $child->id)->get();
                if (count($c)) {
                    $deep($c);
                }
            }
        };
        $c = iblock::where("parrent_id", "=", $iblock)->get();
        if (count($c)) {
            $deep($c);
        }
        return $res;
    }

    /*
    $where["prop"];
    $where["type"];
    $where["value"];*/
    public static function GetList($iblockID, $elId = false, $itemPerPage = 5, $page = false, $where = null, $params = null)
    {
        $stack = [$iblockID];
        $res = [];
        $getChilds = function ($iblock, &$c) use (&$getChilds, &$stack, $elId, $where, $itemPerPage, $page, $params) {
            $c[$iblock->id]["key"] = $iblock->name;
            $c[$iblock->id]["path"] = $stack;
            //
            if ($iblock->id == $elId || !$elId) {
                $els = iblock_element::where("iblock_id", "=", $iblock->id);
                if ($where) {
                    foreach ($where as $cond) {
                        $cProp = iblock_property::where("name", "=", $cond["prop"])->first();
                        $cond["propId"] = $cProp->id;
                        $els->whereHas('propvalue', function ($query) use ($cond, $cProp) {
                            $query->where('prop_id', '=', $cond["propId"]);
                            $type = ($cProp->is_number) ? "value_number" : "value";
                            $query->where($type, $cond["type"], $cond["value"]);
                        });
                    }
                }
                if (isset($params["param"])) {
                    foreach ($params["param"] as $id => $param) {
                        $els->whereHas('propvalue', function ($query) use ($id, $param) {
                            $query->where("prop_id", "=", $id)->where(function ($query) use ($param) {
                                $param = array_map(function ($id) {
                                    return iblock_prop_value::find($id)->value;
                                }, $param);
                                $query->where("value", '=', $param[0]);
                                for ($i = 1; $i <= count($param) - 1; $i++) {
                                    $query->orWhere("value", '=', $param[$i]);
                                }
                            });
                        });
                    }
                }
                if (isset($params["range"])) {
                    foreach ($params["range"] as $id => $param) {
                        $els->whereHas('propvalue', function ($query) use ($id, $param) {
                            $query->where("prop_id", "=", $id)->where(function ($query) use ($param) {
                                $query->where("value_number", '>=', $param["from"]);
                                $query->where("value_number", '<=', $param["to"]);
                            });
                        });
                    }
                }
                if ($page) {
                    $els = $els->offset($itemPerPage * ($page - 1))->take($itemPerPage);
                }
                $els = $els->get();
                foreach ($els as $el) {
                    $t = $el->toArray();
                    $t["prop"] = [];
                    foreach ($el->propvalue as $prop) {
                        $type = ($prop->prop->is_number) ? "value_number" : "value";
                        if (isset($t["prop"][$prop->prop->name])) {
                            if (is_array($t["prop"][$prop->prop->name])) {
                                $t["prop"][$prop->prop->name][] = $prop->{$type};
                            } else {
                                $t["prop"][$prop->prop->name] = [$t["prop"][$prop->prop->name]];
                                $t["prop"][$prop->prop->name][] = $prop->{$type};
                            }
                        } else {
                            $t["prop"][$prop->prop->name] = $prop->{$type};
                        }
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
                $type = ($prop->prop->is_number) ? "value_number" : "value";
                if (isset($t["prop"][$prop->prop->name])) {
                    if (is_array($t["prop"][$prop->prop->name])) {
                        $t["prop"][$prop->prop->name][] = $prop->{$type};
                    } else {
                        $t["prop"][$prop->prop->name] = [$t["prop"][$prop->prop->name]];
                        $t["prop"][$prop->prop->name][] = $prop->{$type};
                    }
                } else {
                    $t["prop"][$prop->prop->name] = $prop->{$type};
                }
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
        foreach ($obj["prop"] as $id => $prop) {
            if (empty($prop)) {
                continue;
            }
            $prop = iblock_property::where("id", "=", $id)->first();
            $count = 0;
            $p = new iblock_prop_value();
            $p->prop_id = $prop->id;
            $p->el_id = $el->id;
            $p->value_id = ++$count;
            //multy shit
            if (is_array($obj["prop"][$prop->id])) {
                $count = 0;
                foreach ($obj["prop"][$prop->id] as $item) {
                    $p = new iblock_prop_value();
                    $p->prop_id = $prop->id;
                    $p->el_id = $el->id;
                    $p->value_id = ++$count;
                    if ($prop->is_number) {
                        $p->value_number = (integer)$item;
                    } else {
                        $p->value = $item;
                    }
                    $p->save();
                }
            } else {
                //
                if ($prop->is_number) {
                    $p->value_number = (integer)$obj["prop"][$prop->id];
                } else {
                    $p->value = $obj["prop"][$prop->id];
                }
                $p->save();
            }
        }
    }

    public static function addSection($obj, $parentId)
    {
        $el = new iblock();
        $el->name = $obj["name"];
        if ($parentId) {
            $el->parrent_id = $parentId;
        }
        $el->save();
    }

    /**
     * $prop = ["prop1"=>"bbb"];
     * Iblocks::updateElement($prop, 16);
     */
    public static function updateElement($props, $elId)
    {
        $pp = iblock_property::whereHas('propvalue', function ($query) use ($elId) {
            $query->where('el_id', '=', $elId);
        })->get();
        foreach ($pp as $p) {
            if (isset($props[$p->name])) {
                iblock_prop_value::where("el_id", "=", $elId)->where("prop_id", "=", $p->id)->delete();
                if (is_array($props[$p->name])) {
                    $count = 0;
                    foreach ($props[$p->name] as $item) {
                        if (empty($item)) {
                            continue;
                        }
                        $c = new iblock_prop_value();
                        $c->el_id = $elId;
                        $c->prop_id = $p->id;
                        $c->value_id = ++$count;
                        if ($p->is_number) {
                            $c->value_number = (integer)$item;
                        } else {
                            $c->value = $item;
                        }
                        $c->save();
                    }
                } else {
                    $count = 0;
                    $c = new iblock_prop_value();
                    $c->el_id = $elId;
                    $c->prop_id = $p->id;
                    $c->value_id = ++$count;
                    if ($p->is_number) {
                        $c->value_number = (integer)$props[$p->name];
                    } else {
                        $c->value = $props[$p->name];
                    }
                    $c->save();
                }
            }
        }
    }

    public static function treeToArray($tree)
    {
        $resTree = [];
        $getTree = function ($tree) use (&$getTree, &$treeKeys, &$resTree) {
            foreach ($tree as $key => $el) {
                //key - iblock_id
                if (isset($el["key"])) { //if curr iblock
                    $resTree[$key] = $el;
                    $getTree($el);
                }
            }
        };
        $getTree($tree);
        return $resTree;
    }
}
