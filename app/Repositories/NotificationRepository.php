<?php

namespace App\Repositories;

use App\Models\NotificationSetting;
use Illuminate\Support\Str;
use App\Models\Business;
use Illuminate\Support\Facades\Hash;

class NotificationRepository
{
    public function update($request)
    {
            $notificationSettings = NotificationSetting::find($request->id);
            // $notificationSettings->business_id = $business_id;
            $notificationSettings->rejected_mail = $request->rejected_mail == true ? 1 : 0;
            $notificationSettings->rejected_text = $request->rejected_text == true ? 1 : 0;
            $notificationSettings->confirmation_mail = $request->confirmation_mail == true ? 1 : 0;
            $notificationSettings->confirmation_text = $request->confirmation_text == true ? 1 : 0;
            $notificationSettings->reminder_mail = $request->reminder_mail == true ? 1 : 0;
            $notificationSettings->reminder_text = $request->reminder_text == true ? 1 : 0;
            $notificationSettings->cancel_mail = $request->cancel_mail == true ? 1 : 0;
            $notificationSettings->cancel_text = $request->cancel_text == true ? 1 : 0;
            $notificationSettings->completed_mail = $request->completed_mail == true ? 1 : 0;
            $notificationSettings->completed_text = $request->completed_text == true ? 1 : 0;
            $notificationSettings->save();

        return[
            'status' => true,
            'message' => 'Select Notification Settings Updated Successfully!',
        ];
    }
}
