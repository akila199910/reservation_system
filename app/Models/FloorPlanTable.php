<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorPlanTable extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'plan_id',
        'table_id',
        'table_width',
        'table_height',
        'table_pos_x',
        'table_pos_y',
        'created_by',
        'updated_by',
    ];

    public function table_info()
    {
        return $this->hasOne(CafeTable::class, 'id', 'table_id');
    }


}
