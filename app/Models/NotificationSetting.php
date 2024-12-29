<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'rejected_mail',
        'rejected_text',
        'confirmation_mail',
        'confirmation_text',
        'reminder_mail',
        'reminder_text',
        'cancel_mail',
        'cancel_text',
        'completed_mail',
        'completed_text'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
