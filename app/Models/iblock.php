<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class iblock extends Model
{
    use HasFactory;

    public function elements()
    {
        return $this->hasMany(iblock_element::class);
    }

    public function properties()
    {
        return $this->hasMany("App\Models\iblock_property");
    }

    public function getPropWithParrents()
    {
        return \App\Service\Iblocks::getPropsParrents($this);
    }


}
