<?php

namespace App\Http\Controllers;

use App\Models\iblock_prop_value;
use App\Models\iblock_property;
use App\Service\functions;
use Illuminate\Http\Request;

use App\Service\Iblocks;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $id = 1, $page = 1)
    {
        $resParams = [];
        if ($request) {
            $params = ($request->toArray());
            $resParams["range"] = [];
            foreach ($params as $key => $param) {
                if (str_contains($key, "-")) {
                    $c = explode("-", $key);
                    $resParams["param"][$c[0]][] = $c[1];
                }
                if (str_contains($key, "_") && !str_contains($key, "token")) {
                    $c = explode("_", $key);
                    $cc = explode(";", $param);
                    $resParams["range"][$c[1]]["from"] = $cc[0];
                    $resParams["range"][$c[1]]["to"] = $cc[1];
                }
            }
        }
        $c = Iblocks::GetList(1, $id, 5, $page, null, $resParams);
        $els = $c["res"];
        $count = $c["count"];
        $res = Iblocks::treeToArray($els);
        $tree = $res;
        $sectionsDetail = [];
        foreach ($tree as $cId => $c) {
            $cEls = [];
            $deep = function ($c) use (&$cEls, &$deep) {
                if (isset($c["elements"])) {
                    foreach ($c["elements"] as $cv) {
                        $cEls[] = $cv;
                    }
                }
                foreach ($c as $key => $value) {
                    if (is_numeric($key)) {
                        $deep($c[$key]);
                    }
                }
            };
            $deep($tree[$cId]);
            $tree[$cId]["elements"] = [];
            foreach ($cEls as $cv) {
                $tree[$cId]["elements"][] = $cv;
            }
            $sectionsDetail[$cId] = functions::getOpItem($cId);
        }
        $countSection = array_filter($tree[$id], function ($item) {
            return isset($item["key"]);
        });
        $sectionIsset = count($countSection);

        $allProps = Iblocks::getAllProps($id, true);
        $allPropValue = $allProps["values"];
        $allProps = $allProps["res"];

        foreach ($allProps as $prop) {
            if ($prop->is_number) {
                $max = 0;
                $min = 0;
                foreach ($prop->propvalue as $p) {
                    if ($p->value_number < $min) {
                        $min = $p->value_number;
                    }
                    if ($p->value_number > $max) {
                        $max = $p->value_number;
                    }
                }
                $prop->propvalue = ["min" => $min, "max" => $max];
            }
        }
        $getParams = functions::getParams();
        return view('home', compact("tree", "id", "sectionIsset", "sectionsDetail", "allProps", "resParams", "allPropValue", "page", "count", "getParams"));
    }

    public function detail($id)
    {
        dd(Iblocks::ElementsGetList([$id])[0]);
    }
}
