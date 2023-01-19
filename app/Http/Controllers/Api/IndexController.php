<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Iblocks;


class IndexController extends Controller
{
    public function __construct()
    {

    }


    /**
     * @OA\Get(
     * path="/api/index/{id}/{page}",
     *
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="page",
     *      in="path",
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
    /**
     * @OA\Get(
     * path="/api/detail/{id}",
     *
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="el info",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     */
    public function detail($id)
    {
        return (Iblocks::ElementsGetList([$id])[0]);
    }
}
