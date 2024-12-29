<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CafeTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref_no',
        'name',
        'perference_id',
        'capacity',
        'amount',
        'image',
        'status',
        'reservation_status',
        'business_id',
        'location_id',
    ];

    public function preference(){
        return $this->belongsTo(TablePreference::class, 'perference_id','id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class);
    }

    public function element_info(){
        return $this->belongsTo(TableLayout::class, 'element_id','id');
    }
}
