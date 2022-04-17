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
        $elements = iblock_element::where("iblock_id", "=", $iblock->id)->get();
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

    public function propertyadd(Request $request, iblock $iblock)
    {
        $property = new iblock_property();
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
            $p->value = $request[$prop->id];
            $p->save();
        }
        return redirect("/admin/");
    }

    public function deleteelement($iblock_el)
    {
        $el = iblock_element::find($iblock_el);
        $el->delete();
        return redirect("/admin/");

    }

}
