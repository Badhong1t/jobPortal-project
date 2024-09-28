<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companyaward extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_name',
        'award_image',
        'date',
        'month',
        'year',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }
}
