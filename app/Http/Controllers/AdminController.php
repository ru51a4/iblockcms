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
            $p = new iblock_prop_value();
            $p->prop_id = $prop->id;
            $p->el_id = $el->id;
            if ($prop->is_number) {
                $p->value_number = (integer)$request[$prop->id];
                $p->save();
            } else if ($prop->is_multy) {
                foreach ($request[$prop->id] as $item) {
                    $c = new iblock_prop_value();
                    $c->prop_id = $prop->id;
                    $c->el_id = $el->id;
                    $c->value = $item;
                    $c->save();
                }
            } else {
                $p->value = $request[$prop->id];
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
            $cProp = iblock_prop_value::where("el_id", "=", $iblock_element->id)->where("prop_id", "=", $prop->id);
            if ($prop->is_multy) {
                $cProp = $cProp->get();
                foreach ($cProp as $c) {
                    $t["value"][] = $c->value;
                }
            } else {
                $cProp = $cProp->first();
                if ($prop->is_number) {
                    $t["value"] = (isset($cProp->value_number)) ? $cProp->value_number : "";
                } else {
                    $t["value"] = (isset($cProp->value)) ? $cProp->value : "";
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
            if ($prop->is_multy) {
                $p = iblock_prop_value::where("el_id", "=", $iblock_element->id)->where("prop_id", "=", $prop->id)->get();
                $counter = 0;
                foreach ($p as $c) {
                    $c->value = $request[$prop->id][$counter++];
                    $c->update();
                    //todo add/delete
                }
            } else {
                $p = iblock_prop_value::where("el_id", "=", $iblock_element->id)->where("prop_id", "=", $prop->id)->first();
                if (isset($p)) {
                    if ($prop->is_number) {
                        $p->value_number = (integer)$request[$prop->id];
                    } else {
                        $p->value = $request[$prop->id];
                    }
                    $p->update();
                } else {
                    $t = new iblock_prop_value();
                    $t->prop_id = $prop->id;
                    $t->el_id = $iblock_element->id;
                    if ($prop->is_number) {
                        $t->value_number = (integer)$request[$prop->id];
                    } else {
                        $t->value = $request[$prop->id];
                    }
                    $t->save();
                }
            }
        }
        return redirect("/admin/" . $iblock_element->iblock_id . "/elementlist");
    }


}
