<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Support\Str;
use App\Models\ClientProfile;
use App\Models\ClientPasswordReset;
use Illuminate\Support\Facades\Hash;

class ClientsRepository
{
    public function create($request)
    {
        $client = new Client();
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->name = $request->first_name.' '.$request->last_name;
        $client->email = $request->email;
        $client->contact = $request->contact;
        $client->status = $request->status == true ? 1 : 0;
        $client->business_id = $request->business_id;
        $client->save();

        $ref_no = refno_generate(16, 2, $client->id);
        $client->ref_no = $ref_no;
        $client->update();

        $profile = new ClientProfile();
        $profile->client_id = $client->id;
        $profile->save();

        $data["title"] = 'Client Creation | '.env('APP_NAME');
        $data["email"] = $client->email;
        $data["name"] = $client->name;
        $data["business"] = $client->business->name;
        $data["view"] = 'mail.welcome.client';

        mailNotification($data);

        return [
            'id' => $client->id,
            'ref_no' => $client->ref_no,
            'first_name' => $client->first_name,
            'last_name' => $client->last_name,
            'name' => $client->name,
            'email' => $client->email,
            'contact' => $client->contact,
        ];
    }

    public function update($request)
    {
        $client = Client::find($request->id);
        $client->first_name = $request->first_name;
        $client->last_name = $request->last_name;
        $client->name = $request->first_name.' '.$request->last_name;
        $client->email = $request->email;
        $client->contact = $request->contact;
        $client->status = $request->status == true ? 1 : 0;
        $client->update();

        return [
            'status' => true,
            'message' => 'Selected Client Updated Successfully!'
        ];
    }

    public function delete($request)
    {
        $client = Client::find($request->id);

        if (!$client) {
            return [
                'status' => false,
                'message' => 'Client Not Found'
            ];
        }

        Reservation::where('client_id',$request->id)->delete();

        $client->delete();

        return [
            'status' => true,
            'message' => 'Selected Client Deleted Successfully'
        ];
    }
}
