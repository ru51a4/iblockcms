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
        $res = Iblocks::treeToArray($els);
        $keys = $res["keys"];
        $tree = $res["tree"];
        return view('home', compact("keys", "tree", "id"));
    }

    public function detail($id)
    {
        dd(Iblocks::ElementsGetList([$id])[0]);
    }
}
