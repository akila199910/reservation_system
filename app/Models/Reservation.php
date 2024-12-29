<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    public function client_info()
    {
        return $this->hasOne(Client::class, 'id', 'client_id')->withTrashed();
    }

    public function table_info()
    {
        return $this->hasOne(CafeTable::class, 'id', 'cafetable_id');
    }

    public function business_info()
    {
        return $this->hasOne(Business::class, 'id', 'business_id');
    }

    public function location_info()
    {
        return $this->hasOne(BusinessLocation::class, 'id', 'location_id');
    }

    public function review_info()
    {
        return $this->hasOne(ReservationReview::class, 'reservation_id', 'id');
    }
}
