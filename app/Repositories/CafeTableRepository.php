<?php

namespace App\Repositories;

use App\Models\CafeTable;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;

class CafeTableRepository
{
    public function create($request)
    {
        $file = 'user/user.png';
        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'cafe_table_images');
        }

        $new_cafe = new CafeTable();
        $new_cafe->name = $request->name;
        $new_cafe->reservation_status = $request->r_status == true ? 1 : 0;
        $new_cafe->capacity = $request->capacity ?  $request->capacity:0;
        $new_cafe->amount = $request->amount;
        $new_cafe->perference_id = $request->perference_id;
        $new_cafe->status = $request->status == true ? 1 : 0;
        $new_cafe->business_id = $request->business_id;
        $new_cafe->image = $file;
        $new_cafe->location_id = $request->location;
        $new_cafe->element_id = $request->element;
        $new_cafe->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $new_cafe->id);
        $new_cafe->ref_no = $ref_no;
        $new_cafe->update();

        $data =  [
            'id' => $new_cafe->id,
            'name' => $new_cafe->name,
            'reservation_status' => $new_cafe->reservation_status,
            'capacity' => $new_cafe->capacity,
            'amount' => $new_cafe->amount,
            'perference_id' => $new_cafe->perference_id,
            'status' => $new_cafe->status,
            'business_id' => $new_cafe->business_id,
            'image' => $new_cafe->image,
            'location_id'=> $new_cafe->location_id,

        ];
        return [
            'status' => true,
            'message' => 'New Table Created Successfully!',
            'data' => $data
        ];
    }

    public function update($request)
    {
        // $file = 'user/user.png';
        // if (isset($request->image) && $request->image->getClientOriginalName()) {
        //     $file = file_upload($request->image, 'cafe_table_images');
        // }
        $edit_cafe = CafeTable::find($request->id);

        if (isset($request->image) && $request->image->getClientOriginalName()) {
            $file = file_upload($request->image, 'cafe_table_images');
        } else {
            $file = $edit_cafe->image;
        }

        $edit_cafe->name = $request->name;
        $edit_cafe->reservation_status = $request->r_status == true ? 1 : 0;
        $edit_cafe->capacity = $request->capacity  ?  $request->capacity:0;
        $edit_cafe->amount = $request->amount;
        $edit_cafe->perference_id = $request->perference_id;
        $edit_cafe->status = $request->status == true ? 1 : 0;
        $edit_cafe->image = $file;
        $edit_cafe->location_id = $request->location;
        $edit_cafe->element_id = $request->element;
        $edit_cafe->update();

        $data =  [
            'id' => $edit_cafe->id,
            'name' => $edit_cafe->name,
            'reservation_status' => $edit_cafe->reservation_status,
            'capacity' => $edit_cafe->capacity,
            'amount' => $edit_cafe->amount,
            'perference_id' => $edit_cafe->perference_id,
            'status' => $edit_cafe->status,
            'business_id' => $edit_cafe->business_id,
            'image' => $edit_cafe->image,
            'location_id'=> $edit_cafe->location_id,
        ];

        return [
            'status' => true,
            'message' => 'New Table Updated Successfully!',
            'data' => $data
        ];
    }

    public function delete($request)
    {
        $cafe = CafeTable::find($request->id);

        if (!$cafe) {
            return [
                'status' => false,
                'message' => 'Table Not Found'
            ];
        }

        $reservation = Reservation::where('cafetable_id',$request->id)->delete();
        $cafe->delete();

        return [
            'status' => true,
            'message' => 'Selected Table Deleted Successfully'
        ];
    }

    public function table_info($request)
    {
        $table = CafeTable::where('ref_no', $request->table_id)->where('business_id', $request->business_id)->first();

        if (!$table) {
            return [
                'status' => false,
                'message' => 'Table Not Found!'
            ];
        }

        $data = [
            'id' => $table->ref_no,
            'name' => $table->name,
            'perference_id' => $table->perference_id,
            'preference' => $table->preference->preference,
            'capacity' => $table->capacity,
            'amount' => number_format($table->amount,2, '.',''),
            'image' => config('aws_url.url') . $table->image
        ];

        return [
            'status' => true,
            'data' => $data
        ];
    }
}
