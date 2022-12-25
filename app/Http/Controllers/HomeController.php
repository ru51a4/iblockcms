<?php

namespace App\Http\Controllers;

use App\Service\functions;
use Illuminate\Http\Request;

use App\Service\Iblocks;


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
    public function index(Request $request, $id = 1)
    {
        $resParams = [];
        if ($request) {
            $params = ($request->toArray());
            foreach ($params as $key => $param) {
                if (str_contains($key, "-")) {
                    $c = explode("-", $key);
                    $resParams[$c[0]][] = $c[1];
                }
            }
        }
        $els = Iblocks::GetList(1, false, 5, false, null, $resParams);
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

        $allProps = Iblocks::getAllProps($id);
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
        return view('home', compact("tree", "id", "sectionIsset", "sectionsDetail", "allProps", "resParams"));
    }

    public function detail($id)
    {
        dd(Iblocks::ElementsGetList([$id])[0]);
    }
}
