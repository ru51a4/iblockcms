<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Iblocks;
use JWTAuth;


class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id = 1, $page = 1)
    {
        return Iblocks::GetList(1, $id, 5, $page, null, []);

    }

    public function detail($id)
    {
        return (Iblocks::ElementsGetList([$id])[0]);
    }
}
