<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ref_no',
        'email',
        'contact_no',
        'address',
        'status',
        'business_id',
        'location_name',
        'google_location',
        'is_default'
    ];

    public function workingHours()
    {
        return $this->hasMany(BusinessLocationsWorkingHours::class, 'location_id', 'id');
    }

}
