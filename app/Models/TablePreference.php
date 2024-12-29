<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TablePreference extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref_no',
        'preference',
        'image',
        'status',
        'location_id',
        'is_default',
        'business_id'
    ];

    public function location()
    {
        return $this->hasOne(BusinessLocation::class, 'id', 'location_id');
    }
}
