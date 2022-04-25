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
        $els = Iblocks::GetList(1, 1, [["prop" => "prop1", "value" => "23", "type" => "="]]);
        dd($els);
        $res = Iblocks::treeToArray($els);
        $tree = $res;
        $countSection = array_filter($tree[$id], function ($item) {
            return isset($item["key"]);
        });
        $sectionIsset = count($countSection);
        return view('home', compact("tree", "id", "sectionIsset"));
    }

    public function detail($id)
    {
        dd(Iblocks::ElementsGetList([$id])[0]);
    }
}
