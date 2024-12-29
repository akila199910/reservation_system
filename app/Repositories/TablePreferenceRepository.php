<?php

namespace App\Repositories;

use App\Models\CafeTable;
use App\Models\Reservation;
use App\Models\TablePreference;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TablePreferenceRepository
{

    public function create($request)
    {
        $file = 'user/user.png';
        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'table_preference');
        }

        $new_preference = new TablePreference();
        $new_preference->preference = $request->preference;
        $new_preference->status = $request->status == true ? 1 : 0;
        $new_preference->image = $file;
        $new_preference->location_id = $request->location;
        $new_preference->business_id = $request->business_id;
        $new_preference->is_default = $request->is_default == true ? 1 : 0;
        $new_preference->save();

        //Generate Reference Number
        $preference_ref = refno_generate(16, 2, $new_preference->id);
        $new_preference->ref_no = $preference_ref;
        $new_preference->update();

        if ($request->is_default == true) {
            //change other location default to null
            $locations = TablePreference::where('business_id', $request->business_id)->where('location_id',$request->location)->where('id', '!=', $new_preference->id)->get();

            if (count($locations)) {
                TablePreference::where('business_id', $request->business_id)->where('location_id',$request->location)->where('id', '!=', $new_preference->id)->update([
                    'is_default' => 0
                ]);
            }
        }

        $data =  [
            'id' => $new_preference->id,
            'preference' => $new_preference->preference,
            'status' => $new_preference->status,
            'image' => $new_preference->image,
            'location_id'=>$new_preference->location_id,
            'business_id'=>$new_preference->business_id,
        ];
        return [
            'status' => true,
            'message' => 'New Section Created Successfully!',
            'data' => $data
        ];
    }

    public function update($request)
    {
        $edit_preference = TablePreference::find($request->id);

        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'table_preference');
        } else {
            if (!$edit_preference->image)
                $file = '';
            else
                $file = $edit_preference->image;
        }

        $edit_preference->preference = $request->preference;
        $edit_preference->status = $request->status == true ? 1 : 0;
        $edit_preference->image = $file;
        $edit_preference->location_id = $request->location;
        $edit_preference->is_default = $request->is_default == true ? 1 : 0;
        $edit_preference->update();

        if ($request->is_default == true) {
            //change other location default to null
            $locations_preference = TablePreference::where('business_id', $edit_preference->business_id)->where('location_id',$request->location)->get();

            if (count($locations_preference)) {

                TablePreference::where('business_id', $edit_preference->business_id)->where('location_id',$request->location)->where('id', '!=', $edit_preference->id)->update([
                    'is_default' => 0
                ]);
            }
        }


        $data =  [
            'id' => $edit_preference->id,
            'preference' => $edit_preference->preference,
            'status' => $edit_preference->status,
            'image' => $edit_preference->image,
            'location_id'=>$edit_preference->location_id,
            'business_id'=>$edit_preference->business_id
        ];

        return [
            'status' => true,
            'message' => 'Selected Section Updated Successfully!',
            'data' => $data
        ];
    }

    public function delete($request)
    {
        $preference = TablePreference::find($request->id);

        if (!$preference) {
            return [
                'status' => false,
                'message' => 'Section Not Found'
            ];
        }

        $tables_id = CafeTable::where('perference_id',$request->id)->pluck('id')->toArray();

        if (count($tables_id)) {
            Reservation::whereIn('cafetable_id',$tables_id)->delete();
        }

        CafeTable::where('perference_id',$request->id)->delete();

        $preference->delete();

        return [
            'status' => true,
            'message' => 'Selected Section Deleted Successfully'
        ];
    }
}
