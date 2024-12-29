<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorPlan extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'business_id',
        'ref_no',
        'section_id',
        'floor_width',
        'floor_length',
        'status'
    ];


    public function tables()
    {
        return $this->hasMany(FloorPlanTable::class, 'plan_id', 'id');
    }

    public function preference_info()
    {
        return $this->hasOne(TablePreference::class, 'id', 'section_id');
    }

}
