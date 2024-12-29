<?php

namespace App\Repositories;

use App\Models\BusinessLocation;
use App\Models\BusinessLocationsWorkingHours;
use App\Models\CafeTable;
use App\Models\Reservation;
use App\Models\TablePreference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LocationRepository
{

    public function create($request)
    {
        // Create location
        $new_location = BusinessLocation::create([
            'business_id' => $request->business_id,
            'location_name' => $request->location_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status == true ? 1 : 0,
            'address' => $request->address,
            'google_location' => $request->google_location,
            'is_default' => $request->is_default == true ? 1 : 0,
        ]);

        $location_ref = refno_generate(16, 2, $new_location->id);

        $new_location->update([
            'ref_no' => $location_ref
        ]);

        if ($request->is_default == true) {
            // Change other location default to null
            BusinessLocation::where('business_id', $request->business_id)
                ->where('id', '!=', $new_location->id)
                ->update(['is_default' => 0]);
        }

        $weeks_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($weeks_days as $day) {
            // Get input times
            $openTimeField = $request->input('open_time_' . $day);
            $closeTimeField = $request->input('close_time_' . $day);

            if ($openTimeField && $closeTimeField) {
                try {
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $opensAtTime = Carbon::createFromFormat('g:i A', $openTimeField)->format('H:i:s');
                    $closeAtTime = Carbon::createFromFormat('g:i A', $closeTimeField)->format('H:i:s');

                    $opensAt = $currentDate . ' ' . $opensAtTime;
                    $closeAt = $currentDate . ' ' . $closeAtTime;

                    $opensAt = Carbon::createFromFormat('Y-m-d H:i:s', $opensAt)->format('Y-m-d H:i:s');
                    $closeAt = Carbon::createFromFormat('Y-m-d H:i:s', $closeAt)->format('Y-m-d H:i:s');

                    $statusKey = 'status_' . $day;
                    $status = $request->input($statusKey) == '1' ? 1 : 0;

                    // Save working hours
                    BusinessLocationsWorkingHours::updateOrCreate(
                        [
                            'business_id' => $request->business_id,
                            'week_day' => $day,
                            'location_id' => $new_location->id,
                        ],
                        [
                            'opens_at' => $opensAt,
                            'close_at' => $closeAt,
                            'status' => $status,
                        ]
                    );
                } catch (\Exception $e) {
                    // Log or handle the error as needed
                    Log::error("Error processing times for $day: " . $e->getMessage());
                }
            }
        }

        $data = [
            'id' => $new_location->id,
            'ref_no' => $new_location->ref_no,
            'bname' => $new_location->business_id,
            'location_name' => $new_location->location_name,
            'email' => $new_location->email,
            'contact_no' => $new_location->contact_no,
            'status' => $new_location->status,
            'address' => $new_location->address,
            'google_location' => $new_location->google_location,
            'business_id' => $new_location->business_id,
        ];

        return [
            'status' => true,
            'message' => 'New Location Created Successfully!',
            'data' => $data
        ];
    }


    public function update($request)
    {
        $edit_location = BusinessLocation::find($request->id);

        if (!$edit_location) {
            return [
                'status' => false,
                'message' => 'Location Not Found'
            ];
        }

        $edit_location->update([
            'business_id' => $request->business_id,
            'location_name' => $request->location_name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status == true ? 1 : 0,
            'address' => $request->address,
            'google_location' => $request->google_location,
            'is_default' => $request->is_default == true ? 1 : 0,
        ]);


        if ($request->is_default == true) {
            //change other location default to null
            $locations = BusinessLocation::where('business_id', $request->business_id)->where('id', '!=', $edit_location->id)->get();

            if (count($locations)) {
                BusinessLocation::where('business_id', $request->business_id)->where('id', '!=', $edit_location->id)->update([
                    'is_default' => 0
                ]);
            }
        }

        $weeks_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($weeks_days as $day) {
            // Get input times
            $openTimeField = $request->input('open_time_' . $day);
            $closeTimeField = $request->input('close_time_' . $day);

            if ($openTimeField && $closeTimeField) {
                try {
                    $currentDate = Carbon::now()->format('Y-m-d');

                    $opensAtTime = Carbon::createFromFormat('g:i A', $openTimeField)->format('H:i:s');
                    $closeAtTime = Carbon::createFromFormat('g:i A', $closeTimeField)->format('H:i:s');

                    $opensAt = $currentDate . ' ' . $opensAtTime;
                    $closeAt = $currentDate . ' ' . $closeAtTime;

                    $opensAt = Carbon::createFromFormat('Y-m-d H:i:s', $opensAt)->format('Y-m-d H:i:s');
                    $closeAt = Carbon::createFromFormat('Y-m-d H:i:s', $closeAt)->format('Y-m-d H:i:s');

                    $statusKey = 'status_' . $day;
                    $status = $request->input($statusKey) == '1' ? 1 : 0;

                    // Save working hours
                    BusinessLocationsWorkingHours::updateOrCreate(
                        [
                            'business_id' => $request->business_id,
                            'week_day' => $day,
                            'location_id' => $edit_location->id,
                        ],
                        [
                            'opens_at' => $opensAt,
                            'close_at' => $closeAt,
                            'status' => $status,
                        ]
                    );
                } catch (\Exception $e) {
                    // Log or handle the error as needed
                    Log::error("Error processing times for $day: " . $e->getMessage());
                }
            }
        }
        // check_snap_status($request->business_id);

        return [
            'status' => true,
            'message' => 'Selected Location  Updated Successfully!'
        ];
    }

    public function delete($request)
    {
        $location = BusinessLocation::find($request->id);

        if (!$location) {
            return [
                'status' => false,
                'message' => 'Location Not Found'
            ];
        }

        TablePreference::where('location_id',$request->id)->delete();
        CafeTable::where('location_id',$request->id)->delete();
        Reservation::where('location_id',$request->id)->delete();

        $location->delete();

        return [
            'status' => true,
            'message' => 'Selected Location Deleted Successfully'
        ];
    }
}
