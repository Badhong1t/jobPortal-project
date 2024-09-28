<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function awards()
    {
        return $this->hasMany(CompanyAward::class,"company_id","id");
    }

    public function branchs()
    {
        return $this->hasMany(CompanyBranch::class,"company_id","id");
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class,"company_id","id");
    }
}
