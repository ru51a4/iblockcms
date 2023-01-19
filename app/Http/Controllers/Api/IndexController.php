<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Iblocks;
use App\Service\functions;


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
        $tree = Iblocks::GetList($id, $id, 5, $page, null, []);
        $props = Iblocks::getAllProps($id, true);
        $cTree = $tree["res"];
        $cEls = [];
        $deep = function (&$c, $id) use (&$cEls, &$deep) {
            if (isset($c["elements"])) {
                foreach ($c["elements"] as $cv) {
                    $cEls[] = $cv;
                }
            }
            foreach ($c as $key => $value) {
                if (is_numeric($key)) {
                    $deep($c[$key], $key);
                }
            }
            $c["sectionDetail"] = functions::getOpItem($id);
        };
        $deep($cTree[$id], $id);
        $kek[$id] = $cTree[$id];
        return ["count" => $tree["count"], "tree" => $kek, "props" => $props, "els" => $cEls];


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
