<?php

namespace App\Repositories;

use App\Models\Business;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Hash;

class BusinessRepository
{
    public function create_business($request)
    {
        //Create business
        $business = new Business();
        $business->name = $request->name;
        $business->email = $request->email;
        $business->contact = $request->contact;
        $business->address = $request->address;
        $business->status = $request->status == true ? 1 : 0;
        $business->snap_auth_key =$request->snap_auth_key;
        $business->ibson_business = $request->ibson_business == true ? 1 :0;
        $business->ibson_id = $request->ibson_id;
        $business->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $business->id);
        $business->ref_no = $ref_no;
        $business->update();

        //storing notificationSettings
        $notificationSettings = new NotificationSetting();
        $notificationSettings->business_id = $business->id;
        $notificationSettings->save();

        return[
            'id' => $business->id,
            'name' => $business->name,
            'email' => $business->email,
            'contact' => $business->contact,
            'address' => $business->address,
            'status' => $business->status,
            'snap_auth_key' => $business->snap_auth_key,
            'ibson_business' => $business->ibson_business,
            'ibson_id' => $business->ibson_id
        ];
    }

    public function update_business($request)
    {
        //update business
        $business = Business::find($request->id);
        $business->name = $request->name;
        $business->email = $request->email;
        $business->contact = $request->contact;
        $business->address = $request->address;
        $business->status = $request->status == true ? 1 : 0;
        $business->snap_auth_key =$request->snap_auth_key;
        $business->ibson_business = $request->ibson_business == true ? 1 :0;

        // If not a Ibson's Business, Ibson's_id automatically delete
        if ($business->ibson_business == 0) {
            $business->ibson_id = null;
        } else {
            $business->ibson_id = $request->ibson_id;
        }
        $business->update();

        return[
            'id' => $business->id,
            'name' => $business->name,
            'email' => $business->email,
            'contact' => $business->contact,
            'address' => $business->address,
            'status' => $business->status,
            'snap_auth_key' => $business->snap_auth_key,
            'ibson_business' => $business->ibson_business,
            'ibson_id' => $business->ibson_id
        ];
    }

    public function delete_business($request)
    {
        $business = Business::find($request->id);

        if (!$business) {
            return [
                'status' => false,
                'message' => 'Business Not Found'
            ];
        }

        $business->delete();

        return [
            'status' => true,
            'message' => 'Selected Business Deleted Successfully'
        ];
    }
}
