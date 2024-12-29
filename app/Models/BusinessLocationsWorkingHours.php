<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessLocationsWorkingHours extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'business_id',
        'location_id',
        'week_day',
        'opens_at',
        'close_at',
        'status',
        'deleted_at',
    ];
}
