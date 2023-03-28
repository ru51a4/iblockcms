<?php

namespace App\Console\Commands;

use App\Models\iblock;
use App\Service\Iblocks;
use Illuminate\Console\Command;

class LoadDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $svalue = [];
        $data = json_decode(file_get_contents('http://shop.su/data.json'), 1);
        
        $deep = function ($item) use (&$deep, &$svalue) {
            $svalue[$item["ID"]] = $item["NAME"];
            if (isset($item["children"])) {
                foreach ($item["children"] as $q) {
                    $deep($q);
                }
            }
        };
        foreach ($data as $q) {
            $deep($q);
        }

        
        $deep = function ($item) use (&$deep, &$svalue) {
            //$svalue[++$item["ID"]] = $item["NAME"];
            //var_dump($svalue);
            if ($item["IBLOCK_SECTION_ID"] == NULL) {
                $item["IBLOCK_SECTION_ID"] = 1;
                $iblockId = 1;

            } else { 
               
                var_dump( $item["IBLOCK_SECTION_ID"]);
                   
                if (isset($svalue[$item["IBLOCK_SECTION_ID"]])) {
                    $iblockId = iblock::where("name", "=", $svalue[$item["IBLOCK_SECTION_ID"]])->first()->id;
                } else {
                    $iblockId = 1;
                }
            }

            Iblocks::addSection(["name" => $item["NAME"]], $iblockId);
            if (isset($item["children"])) {
                foreach ($item["children"] as $q) {
                    $deep($q);
                }
            }
        };
        foreach ($data as $q) {
            $deep($q);
        }


        $deep = function ($item) use (&$deep, &$svalue) {
            if ($item["IBLOCK_SECTION_ID"] == NULL) {
                $item["IBLOCK_SECTION_ID"] = 1;
            } else {
            }
            if (isset($item["ITEMS"])) {
                foreach ($item["ITEMS"] as $_item) {
                    $res = [];
                    foreach ($_item["PROP"] as $_q) {
                        if ($_q["PROPERTY_TYPE"] == "S") {
                            $res[$_q["NAME"]] = $_q["VALUE"];
                        }
                    }
                    $res["DETAIL_PICTURE"] = $_item["Fields"]["DETAIL_PICTURE"];
                    $iblockId = iblock::where("name", "=", $item["NAME"])->first()->id;
                    Iblocks::addElement(["name" => $_item["Fields"]["NAME"], "prop" => $res], $iblockId);

                }
            }
            if (isset($item["children"])) {
                foreach ($item["children"] as $q) {
                    $deep($q);
                }
            }
        };
        foreach ($data as $q) {
            $deep($q);
        }
        /*
        for ($i = 0; $i < 600; $i++) {
        foreach ($data["catalog"][1]["products"] as $key => $value) {
        foreach ($data["catalog"][0]["categories"] as $key => $svalue) {
        if ($svalue["id"] == $value["category_id"]) {
        $iblockId = iblock::where("name", "=", $svalue["name"])->first()->id;
        break;
        }
        }
        $res = [];
        foreach ($value["features"] as $q) {
        $res[$q["name"]] = $q["value"];
        }
        $res["price"] = random_int(100, 999);
        Iblocks::addElement(["name" => $value["name"], "prop" => $res], $iblockId);
        }
        }
        */
        return 0;
    }
}