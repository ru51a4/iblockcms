<?php

namespace App\Http\Controllers;

use App\Models\iblock;
use App\Models\iblock_element;
use App\Models\iblock_prop_value;
use App\Models\iblock_property;
use App\Service\Iblocks;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function index()
    {
        $iblocks = iblock::where("parrent_id", "=", 0)->get();
        return view('admin/dashboard', compact("iblocks"));
    }

    public function addiblock(Request $request)
    {
        $iblock = new iblock();
        $iblock->name = $request->name;
        $iblock->parrent_id = ($request->parrent_id) ? $request->parrent_id : 0;
        $iblock->save();
        return redirect("/admin");
    }

    public function addiblockform()
    {
        $iblocks = iblock::all();
        return view('admin/addiblock', compact("iblocks"));
    }

    public function elementlist(iblock $iblock)
    {
        $breadcrumb = Iblocks::getBreadcrumbIblock($iblock);
        $iblocks = iblock::where("parrent_id", "=", $iblock->id)->get();
        $elements = iblock_element::where("iblock_id", "=", $iblock->id)->paginate(20);
        return view('admin/elementlist', compact("iblock", "elements", "iblocks", "breadcrumb"));
    }

    public function iblockeditform(iblock $iblock)
    {
        $breadcrumb = Iblocks::getBreadcrumbIblock($iblock);
        return view('admin/iblockedit', compact("iblock", "breadcrumb"));
    }

    public function iblockedit(Request $request, iblock $iblock)
    {
        $iblock->name = $request->name;
        $iblock->save();
        return redirect("/admin/" . $iblock->id . '/iblockedit');
    }

    public function deleteiblock(iblock $iblock)
    {
        $iblock->delete();
        return redirect("/admin/");
    }

    public function propertyadd(Request $request, iblock $iblock)
    {
        $property = new iblock_property();
        $property->is_number = ($request->is_number == "on") ? 1 : 0;
        $property->is_multy = ($request->is_multy == "on") ? 1 : 0;
        $property->name = $request->name;
        $iblock->properties()->save($property);
        return redirect("/admin/" . $iblock->id . '/iblockedit');

    }

    public function addelementform(iblock $iblock)
    {
        return view('admin/addelement', compact("iblock"));
    }

    public function addelement(Request $request, iblock $iblock)
    {
        $el = new iblock_element();
        $el->name = $request->name;
        $el->iblock_id = $iblock->id;
        $el->save();
        $props = $iblock->getPropWithParrents();
        foreach ($props as $prop) {
            $count = 0;
            $p = new iblock_prop_value();
            $p->prop_id = $prop->id;
            $p->el_id = $el->id;
            $p->value_id = ++$count;
            //multy shit
            if (is_array($request->{$prop->id})) {
                $count = 0;
                foreach ($request->{$prop->id} as $item) {
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
                    $p->value_number = (integer)$request[$prop->id];
                } else {
                    $p->value = $request[$prop->id];
                }
                $p->save();
            }
        }
        return redirect("/admin/" . $iblock->id . "/elementlist");
    }

    public function deleteelement($iblock_el)
    {
        $el = iblock_element::find($iblock_el);
        $el->delete();
        return redirect("/admin/");

    }

    public function editelementform(iblock_element $iblock_element)
    {
        $props = $iblock_element->iblock->getPropWithParrents();

        $resProp = [];

        foreach ($props as $prop) {
            $t = $prop->toArray();
            $cProp = iblock_prop_value::where("el_id", "=", $iblock_element->id)->where("prop_id", "=", $prop->id)->get()->toArray();
            foreach ($cProp as $k) {
                if ($prop->is_number) {
                    $t["value"][$k["value_id"]] = (isset($k["value_number"])) ? $k["value_number"] : "";
                } else {
                    $t["value"][$k["value_id"]] = (isset($k["value"])) ? $k["value"] : "";
                }
            }
            $resProp[] = $t;
        }

        return view('admin/editelement', compact("iblock_element", "resProp"));
    }

    public function editelement(iblock_element $iblock_element, Request $request)
    {
        $iblock_element->name = $request->name;
        $props = $iblock_element->iblock->getPropWithParrents();
        $iblock_element->update();
        foreach ($props as $prop) {
            if (!count($request->{$prop->id})) {
                return;
            }
            iblock_prop_value::where("el_id", "=", $iblock_element->id)->where("prop_id", "=", $prop->id)->delete();
            $count = 0;
            foreach ($request->{$prop->id} as $item) {
                if (empty($item)) {
                    continue;
                }
                $p = new iblock_prop_value();
                $p->el_id = $iblock_element->id;
                $p->prop_id = $prop->id;
                $p->value_id = ++$count;
                if ($prop->is_number) {
                    $p->value_number = (integer)$item;
                } else {
                    $p->value = $item;
                }
                $p->save();
            }
        }
        return redirect("/admin/" . $iblock_element->iblock_id . "/elementlist");
    }


}
