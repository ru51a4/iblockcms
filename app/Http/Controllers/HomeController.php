<?php

namespace App\Http\Controllers;

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
    public function index($id = 1)
    {
        $els = Iblocks::GetList(1, $id);
        $treeKeys = [];
        $resTree = [];
        $getTree = function ($tree, $c) use (&$getTree, &$treeKeys, &$resTree) {
            foreach ($tree as $key => $el) {
                if (isset($el["key"])) {
                    $treeKeys[$key]["value"] = $el["key"];
                    $treeKeys[$key]["lvl"] = count($el["path"]);
                    $resTree[$el["key"]] = $c[$key];
                    $getTree($el, $c[$key]);
                }
            }
        };
        $getTree($els, $els);
        $id = $treeKeys[$id]["value"];
        unset($resTree[$id]["key"]);

        return view('home', compact("treeKeys", "resTree", "id"));
    }

    public function detail($id)
    {
        dd(Iblocks::ElementsGetList([$id])[0]);
    }
}
