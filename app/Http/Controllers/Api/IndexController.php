<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Iblocks;
use JWTAuth;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }


    /**
     * @OA\Get(
     * path="/api/index",
     *
     *  @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="catalog+els",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
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
