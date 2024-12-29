<?php

namespace App\Repositories;

use App\Models\BusinessLocationsWorkingHours;
use Carbon\Carbon;
use App\Models\CafeTable;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationRepository
{

    public function create_reservation($request)
    {
        $reservation = new Reservation();
        $reservation->business_id = $request->business_id;
        $reservation->location_id = $request->location;
        $reservation->client_id = $request->client;
        $reservation->cafetable_id = $request->table_id;
        $reservation->request_date = date('Y-m-d', strtotime($request->requested_date));
        $reservation->request_start_time = date('Y-m-d H:i:s', strtotime($request->requested_date . ' ' . $request->start_time));
        $reservation->request_end_time = date('Y-m-d H:i:s', strtotime($request->requested_date . ' ' . $request->end_time));
        $reservation->no_of_people = $request->no_of_people;
        $reservation->extra_people = $request->no_of_extra_people;
        $reservation->amount = (isset($request->table_amount) && !empty($request->table_amount)) ? $request->table_amount : 0;
        $reservation->discount = (isset($request->discount_amount) && !empty($request->discount_amount)) ? $request->discount_amount : 0;
        $reservation->service_amount = (isset($request->service_charge) && !empty($request->service_charge)) ? $request->service_charge : 0;
        $reservation->extra_amount = (isset($request->extra_people_charge) && !empty($request->extra_people_charge)) ? $request->extra_people_charge : 0;
        $reservation->final_amount = (isset($request->total_amount) && !empty($request->total_amount)) ? $request->total_amount : 0;
        $reservation->reservation_note = $request->reservation_note;
        $reservation->save();

        $reservation_id = $reservation->id;
        $formatted_reservation_id = auto_increment_id($reservation_id);
        $reservation->reservation_id = $formatted_reservation_id;
        $reservation->update();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $reservation->id);
        $reservation->ref_no = strtoupper($ref_no);
        $reservation->update();

        return [
            'ref_no' => $reservation->ref_no,
            'request_date' => $reservation->request_date
        ];
    }

    // public function available_table($request)
    // {
    //     $table_id = '';
    //     if (isset($request->table_id) && !empty($request->table_id))
    //     {
    //        $cafe_table = CafeTable::where('business_id',$request->business_id)->where('ref_no',$request->table_id)->first();

    //        $table_id = $cafe_table->id;
    //     }

    //     //Getting reservation list
    //     $reservation_query = Reservation::where('business_id',$request->business_id)
    //                         ->whereNotIn('status',[1,3,4]);

    //                         if (isset($request->table_id) && !empty($request->table_id))
    //                         $reservation_query = $reservation_query->whereNot('cafetable_id', $table_id);

    //                         if (isset($request->requested_date) && !empty($request->requested_date))
    //                         $reservation_query = $reservation_query->whereDate('request_date', $request->requested_date);


    //     $reservation_tbl_id = $reservation_query->pluck('cafetable_id')->toArray();
    //     //End

    //     //Getting all table
    //     $all_tables_query = CafeTable::where('business_id',$request->business_id)->where('status',1);
    //                     if (isset($request->location) && !empty($request->location))
    //                     $all_tables_query = $all_tables_query->where('location_id', $request->location);

    //     $all_tables = $all_tables_query->pluck('id')->toArray();
    //     //End

    //     //Get reserved table id and remove the table
    //     $table_ids = array_diff($all_tables, $reservation_tbl_id);
    //     //End

    //     //Get the final table
    //     $tables = CafeTable::whereIn('id',$table_ids)->where('business_id',$request->business_id)->where('status',1)->get();
    //     //End

    //     $data = [];
    //     foreach ($tables as $item) {
    //         $data[] = [
    //             'id' => $item->ref_no,
    //             'name' => $item->name,
    //             'perference_id' => $item->perference_id,
    //             'preference' => $item->preference->preference,
    //             'capacity' => $item->capacity,
    //             'amount' => $item->amount,
    //             'image' => config('aws_url.url').$item->image
    //         ];
    //     }

    //     return $data;
    // }

    public function available_table($request)
    {
        $table_id = '';
        if (isset($request->table_id) && !empty($request->table_id)) {
            $cafe_table = CafeTable::where('business_id', $request->business_id)->where('ref_no', $request->table_id)->first();

            $table_id = $cafe_table->id;
        }
        $interval_time = $request->interval_min;

        $requested_date = date('Y-m-d', strtotime($request->requested_date));
        $start_time = date('Y-m-d H:i:s', strtotime($request->start_time));
        $end_time = date('Y-m-d H:i:s', strtotime('+' . $interval_time . 'minutes', strtotime($request->end_time)));

        $tables_ids_Query = CafeTable::where('business_id', $request->business_id);
                    if(isset($request->preference_id) && !empty($request->preference_id))
                    $tables_ids_Query = $tables_ids_Query->where('perference_id', $request->preference_id);

        $tables_ids = $tables_ids_Query->pluck('id')->toArray();

        $reservations = Reservation::where('location_id', $request->location)->where('business_id', $request->business_id)
            ->select(DB::raw("DATE_FORMAT(request_start_time,'%H:%i:%s') start_time"), DB::raw("DATE_FORMAT(request_end_time,'%H:%i:%s') end_time"), 'request_date AS shcedule_date', 'cafetable_id')
            ->whereDate('request_date', $request->requested_date)
            ->whereNotIn('status', [1, 3, 4])
            ->whereNull('deleted_at')
            ->whereIn('cafetable_id',$tables_ids)
            ->get()->toArray();

        $tables_query = CafeTable::where('location_id', $request->location)->where('business_id', $request->business_id);
                    if(isset($request->preference_id) && !empty($request->preference_id))
                    $tables_query = $tables_query->where('perference_id', $request->preference_id);

        $tables = $tables_query->where('status', 1)->get();

        if (count($reservations)) {
            //getting the available table
            $table_ids = $this->prepare_tables($reservations, $requested_date, $start_time, $end_time);

            if (isset($request->table_id) && !empty($request->table_id)) {
                $table_ids = array_diff($table_ids, [$table_id]);
            }

            $tables_query = CafeTable::where('location_id', $request->location)->where('business_id', $request->business_id);

            if(isset($request->preference_id) && !empty($request->preference_id))
                    $tables_query = $tables_query->where('perference_id', $request->preference_id);

            $tables = $tables_query->where('status', 1)->whereNotIn('id', $table_ids)->get();
        }

        $data = [];
        foreach ($tables as $item) {
            $data[] = [
                'id' => $item->ref_no,
                'name' => $item->name,
                'perference_id' => $item->perference_id,
                'preference' => $item->preference->preference,
                'capacity' => $item->capacity,
                'amount' => $item->amount,
                'image' => config('aws_url.url') . $item->image
            ];
        }

        return $data;
    }

    public function prepare_tables($reservations, $requested_date, $start_time, $end_time)
    {
        $table_id = [];
        foreach ($reservations as $item) {
            $res_start_time = date('Y-m-d H:i:s', strtotime($item['shcedule_date'] . ' ' . $item['start_time']));
            $res_end_time = date('Y-m-d H:i:s', strtotime($item['shcedule_date'] . ' ' . $item['end_time']));

            if (strtotime($start_time) <= strtotime($res_start_time) && strtotime($end_time) >= strtotime($res_start_time) || strtotime($start_time) >= strtotime($res_start_time) && strtotime($start_time) < strtotime($res_end_time)) {
                $table_id[] = $item['cafetable_id'];
            }
        }

        return $table_id;
    }

    public function update_reservation($request)
    {
        $reservation = Reservation::find($request->id);
        $reservation->business_id = $request->business_id;
        $reservation->location_id = $request->location;
        $reservation->client_id = $request->client;
        $reservation->cafetable_id = $request->table_id;
        $reservation->request_date = date('Y-m-d', strtotime($request->requested_date));
        $reservation->request_start_time = date('Y-m-d H:i:s', strtotime($request->requested_date . ' ' . $request->start_time));
        $reservation->request_end_time = date('Y-m-d H:i:s', strtotime($request->requested_date . ' ' . $request->end_time));
        $reservation->no_of_people = $request->no_of_people;
        $reservation->extra_people = $request->no_of_extra_people;
        $reservation->amount = (isset($request->table_amount) && !empty($request->table_amount)) ? $request->table_amount : 0;
        $reservation->discount = (isset($request->discount_amount) && !empty($request->discount_amount)) ? $request->discount_amount : 0;
        $reservation->service_amount = (isset($request->service_charge) && !empty($request->service_charge)) ? $request->service_charge : 0;
        $reservation->extra_amount = (isset($request->extra_people_charge) && !empty($request->extra_people_charge)) ? $request->extra_people_charge : 0;
        $reservation->final_amount = (isset($request->total_amount) && !empty($request->total_amount)) ? $request->total_amount : 0;
        $reservation->reservation_note = $request->reservation_note;
        $reservation->payment_type = $request->payment_type;
        $reservation->paid_status = $request->payment_type == 0 ? 0 : 1;
        $reservation->update();

        return [
            'ref_no' => $reservation->ref_no,
            'request_date' => $reservation->request_date
        ];
    }

    public function change_reservation_status($request)
    {
        $reservation = Reservation::find($request->id);

        //Business info
        $business = $reservation->business_info;

        $status = true;
        $mail_status = false;
        $text_status = false;
        $message = 'Dear ' . $reservation->client_info->name . ", \n\n";
        $pay_link = '';
        $review_link = '';
        //sending rejected mail
        if ($request->status == 1) {
            $subject = 'Reservation Request Rejected | ' . $reservation->business_info->name;
            $view = 'mail.reservation.rejected';
            $mail_status = isset($business->notificationSettings) && $business->notificationSettings->rejected_mail == 1 ? true : false;
            $text_status = isset($business->notificationSettings) && $business->notificationSettings->rejected_text == 1 ? true : false;
            $message .= $subject . ", \n\n";
        }

        //sending confirm mail
        if ($request->status == 2) {
            $subject = 'Reservation Request Confirmed | ' . $reservation->business_info->name;
            $view = 'mail.reservation.confirm';
            $mail_status = isset($business->notificationSettings) && $business->notificationSettings->confirmation_mail == 1 ? true : false;
            $text_status = isset($business->notificationSettings) && $business->notificationSettings->confirmation_text == 1 ? true : false;
            $message .= $subject . ", \n\n";
            if ($reservation->paid_status == 0) {
                $pay_link = "\n\n Click Here to Pay Now - " . route('payhere.reservation.payment.view', $reservation->ref_no);
            }
        }

        //sending cancel mail
        if ($request->status == 3) {
            $subject = 'Reservation Request Cancelled | ' . $reservation->business_info->name;
            $view = 'mail.reservation.cancel';
            $mail_status = isset($business->notificationSettings) && $business->notificationSettings->cancel_mail == 1 ? true : false;
            $text_status = isset($business->notificationSettings) && $business->notificationSettings->cancel_text == 1 ? true : false;
            $message .= $subject . ", \n\n";
        }

        //sending completed mail
        if ($request->status == 4) {
            $subject = 'Reservation Request Completed | ' . $reservation->business_info->name;
            $view = 'mail.reservation.completed';
            $message .= $subject . ", \n\n";
            // $review_link = "\n\n Click Here to Review Us - ".route('reservation.reviewus',$reservation->ref_no);
            $mail_status = isset($business->notificationSettings) && $business->notificationSettings->completed_mail == 1 ? true : false;
            $text_status = isset($business->notificationSettings) && $business->notificationSettings->completed_text == 1 ? true : false;

            if ($reservation->paid_status == 0) {
                return [
                    'status' => false,
                    'ref_no' => $reservation->ref_no,
                    'request_date' => $reservation->request_date
                ];
            }
        }

        $data["title"] = $subject;
        $data["email"] = $reservation->client_info->email;
        $data["name"] = $reservation->client_info->name;
        $data["company"] = $reservation->business_info;
        $data["reservation"] = $reservation;
        $data["view"] = $view;

        //check sl snap status
        $snap_status = check_snap_status($reservation->business_info->id);

        if ($snap_status && $text_status) {

            $message .= "Reference Number - " . $reservation->reservation_id . " \n";
            $message .= "Requested Date - " . date('jS, M Y', strtotime($reservation->request_date)) . " \n";
            $message .= "Start Time - " . date('h:i A', strtotime($reservation->request_start_time)) . " \n";
            $message .= "End Time - " . date('h:i A', strtotime($reservation->request_end_time)) . " \n";
            $message .= "Requested Table - " . $reservation->table_info->name . " \n";
            $message .= "Number of People - " . $reservation->no_of_people . " \n";
            $message .= "Number of Extra People - " . $reservation->extra_people . " \n";
            $message .= "Amount - " . number_format((($reservation->amount + $reservation->extra_amount +  $reservation->service_amount) - $reservation->discount), 2, '.', '') . " \n";
            $message .= "Location Details - \n";
            $message .= $reservation->location_info->location_name . ", \n";
            $message .= $reservation->location_info->address . ", \n";
            $message .= $reservation->location_info->contact_no . ", \n";

            if ($reservation->location_info->google_location != '') {
                $message .= "Google Map - " . $reservation->location_info->google_location;
            }

            if ($pay_link != '') {
                $message .= $pay_link;
            }

            if ($review_link != '') {
                $message .= $review_link;
            }

            //merge the request
            $request->merge([
                'snap_auth_key' => $reservation->business_info->snap_auth_key,
                'client_name' => $reservation->client_info->name,
                'client_contact' => $reservation->client_info->contact,
                'message' => $message
            ]);

            send_slsnap_message($request);
        }

        if ($mail_status) {
            //send mail
            mailNotification($data);
        }

        //Update the reservation
        $reservation->status = $request->status;
        $reservation->update();

        return [
            'status' => $status,
            'ref_no' => $reservation->ref_no,
            'request_date' => $reservation->request_date
        ];
    }

    public function booking_list($request)
    {
        //Getting reservation list
        $reservation_query = Reservation::where('business_id', $request->business_id);

        if (isset($request->status) && !empty($request->status))
            $reservation_query = $reservation_query->whereIn('status', $request->status);

        if (isset($request->location) && !empty($request->location))
            $reservation_query = $reservation_query->where('location_id', $request->location);

        if (isset($request->requested_date) && !empty($request->requested_date))
            $reservation_query = $reservation_query->whereDate('request_date', $request->requested_date);

        if (isset($request->future_booking) && $request->future_booking == true)
            $reservation_query = $reservation_query->whereDate('request_date', '>=', date('Y-m-d'));

        if (isset($request->from_date) && !empty($request->from_date))
            $reservation_query = $reservation_query->whereDate('request_date', '>=', $request->from_date);

        if (isset($request->end_date) && !empty($request->end_date))
            $reservation_query = $reservation_query->whereDate('request_date', '<=', $request->end_date);

        $reservations = $reservation_query->get();
        //End

        $data = [];
        foreach ($reservations as $item) {

            $status = '';
            if ($item->status == 0) {
                $status = 'Pending';
            }

            if ($item->status == 1) {
                $status = 'Rejected';
            }

            if ($item->status == 2) {
                $status = 'Confirmed';
            }

            if ($item->status == 3) {
                $status = 'Cancelled';
            }

            if ($item->status == 4) {
                $status = 'Completed';
            }


            $paid_status = '';
            if ($item->paid_status == 0) {
                $paid_status = 'Not Paid';
            }

            if ($item->paid_status == 1) {

                $pay_method = '';

                if ($item->payment_type == 1) {
                    $pay_method = 'Direct Pay';
                }

                if ($item->payment_type == 2) {
                    $pay_method = 'Online Pay';
                }

                $paid_status = 'Paid - '.$pay_method;

            }

            $data[] = [
                'id' => $item->id,
                'ref_no' => $item->ref_no,
                'business_id' => $item->business_id,
                'business_name' => $item->business_info->name,
                'location_id' => $item->location_id,
                'location_name' => $item->location_info->location_name,
                'request_date' => $item->request_date,
                'start_time' => $item->request_start_time,
                'end_time' => $item->request_end_time,
                'no_of_people' => $item->no_of_people,
                'extra_people' => $item->extra_people,
                'amount' => $item->amount,
                'discount' => $item->discount,
                'extra_amount' => $item->extra_amount,
                'service_amount' => $item->service_amount,
                'final_amount' => $item->final_amount,
                'status_id' => $item->status,
                'status' => $status,
                'paid_status' => $paid_status,
                'reservation_note' => $item->reservation_note,
                'client_info' => [
                    'id' => $item->client_id,
                    'ref_no' => $item->client_info->ref_no,
                    'first_name' => $item->client_info->first_name,
                    'last_name' => $item->client_info->last_name,
                    'name' => $item->client_info->name,
                    'email' => $item->client_info->email,
                    'contact' => $item->client_info->contact,
                ],
                'table_info' => [
                    'id' => $item->cafetable_id,
                    'ref_no' => $item->table_info->ref_no,
                    'name' => $item->table_info->name
                ],
            ];
        }

        return $data;
    }

    public function get_reservation_info($request)
    {
        $data = [];
        $reservation = Reservation::where('ref_no', $request->ref_no)->first();

        if ($reservation) {

            $status = '';
            if ($reservation->status == 0) {
                $status = 'Pending';
            }

            if ($reservation->status == 1) {
                $status = 'Rejected';
            }

            if ($reservation->status == 2) {
                $status = 'Confirmed';
            }

            if ($reservation->status == 3) {
                $status = 'Cancelled';
            }

            if ($reservation->status == 4) {
                $status = 'Completed';
            }

            $paid_status = '';
            if ($reservation->paid_status == 0) {
                $paid_status = 'Not Paid';
            }

            if ($reservation->paid_status == 1) {

                $pay_method = '';

                if ($reservation->payment_type == 1) {
                    $pay_method = 'Direct Pay';
                }

                if ($reservation->payment_type == 2) {
                    $pay_method = 'Online Pay';
                }

                $paid_status = 'Paid - '.$pay_method;

            }

            $data = [
                'id' => $reservation->id,
                'ref_no' => $reservation->ref_no,
                'business_id' => $reservation->business_id,
                'business_name' => $reservation->business_info->name,
                'location_id' => $reservation->location_id,
                'location_name' => $reservation->location_info->location_name,
                'request_date' => $reservation->request_date,
                'start_time' => $reservation->request_start_time,
                'end_time' => $reservation->request_end_time,
                'no_of_people' => $reservation->no_of_people,
                'extra_people' => $reservation->extra_people,
                'amount' => $reservation->amount,
                'discount' => $reservation->discount,
                'extra_amount' => $reservation->extra_amount,
                'service_amount' => $reservation->service_amount,
                'final_amount' => $reservation->final_amount,
                'status_id' => $reservation->status,
                'status' => $status,
                'paid_status' => $paid_status,
                'reservation_note' => $reservation->reservation_note,
                'client_info' => [
                    'id' => $reservation->client_id,
                    'ref_no' => $reservation->client_info->ref_no,
                    'first_name' => $reservation->client_info->first_name,
                    'last_name' => $reservation->client_info->last_name,
                    'name' => $reservation->client_info->name,
                    'email' => $reservation->client_info->email,
                    'contact' => $reservation->client_info->contact,
                ],
                'table_info' => [
                    'id' => $reservation->cafetable_id,
                    'ref_no' => $reservation->table_info->ref_no,
                    'name' => $reservation->table_info->name
                ],
            ];
        }

        return $data;
    }

    public function calculate_minutes($start_time, $end_time)
    {
        $startTime = Carbon::parse($start_time);
        $endTime = Carbon::parse($end_time);

        $minutesDifference = $startTime->diffInMinutes($endTime);

        return $minutesDifference;
    }

    /*
        Checking the selected table reservations
        - Check the table have reservation between the start time and end time
    */
    public function check_table_reservation($request)
    {
        $interval_time = isset($request->interval_min) ? $request->interval_min : 15;

        $requested_date = date('Y-m-d', strtotime($request->start_time));
        $start_time = date('Y-m-d H:i:s', strtotime($request->start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($request->end_time));

        $reservations_query = Reservation::where('location_id', $request->location)->where('business_id', $request->business_id)
            ->select(DB::raw("DATE_FORMAT(request_start_time,'%H:%i:%s') start_time"), DB::raw("DATE_FORMAT(request_end_time,'%H:%i:%s') end_time"), 'request_date AS shcedule_date', 'cafetable_id')
            ->whereDate('request_date', $requested_date)
            ->whereNotIn('status', [1, 3])
            ->whereNull('deleted_at');

            if(isset($request->table_id) && !empty($request->table_id))
                $reservations_query = $reservations_query->where('cafetable_id',$request->table_id);

            if(isset($request->reservation_id) && !empty($request->reservation_id))
                $reservations_query = $reservations_query->where('id','!=',$request->reservation_id);

        $reservations = $reservations_query->get()->toArray();

        $status = true;
        if (count($reservations)) {
            $status = $this->check_exist_reservation($reservations, $requested_date, $start_time, $end_time);
        }

        return $status;
    }

    public function check_exist_reservation($reservations, $requested_date, $start_time, $end_time)
    {
        $table_id = [];
        foreach ($reservations as $item) {
            $res_start_time = date('Y-m-d H:i:s', strtotime($item['shcedule_date'] . ' ' . $item['start_time']));
            $res_end_time = date('Y-m-d H:i:s', strtotime($item['shcedule_date'] . ' ' . $item['end_time']));

            if ((strtotime($start_time) <= strtotime($res_start_time) && strtotime($end_time) > strtotime($res_start_time)) || strtotime($start_time) >= strtotime($res_start_time) && strtotime($start_time) < strtotime($res_end_time)) {
                return false;
            }
        }

        return true;
    }

    public function location_work_hours($request)
    {
        $request_day = date('l', strtotime($request->requested_date));
        $requested_date = date('Y-m-d', strtotime($request->requested_date));

        $work_hour = BusinessLocationsWorkingHours::where('location_id',$request->location)->where('status',1)->where('week_day',$request_day)->first();

        //checking business open or not
        if (!$work_hour) {
            return [
                'status' =>false,
                'error_type' => 'location',
                'message' => 'Selected business does not open today'
            ];
        }

        $start_time =  date('H:i:s', strtotime($request->start_time));
        $end_time =  date('H:i:s', strtotime($request->end_time));

        // checking the business open time less than request start time
        $open_at = date('H:i:s', strtotime($work_hour->opens_at));
        $close_at = date('H:i:s', strtotime($work_hour->close_at));

        if ((strtotime($open_at) > strtotime($start_time)) || (strtotime($close_at) < strtotime($start_time))) {
            return [
                'status' =>false,
                'error_type' => 'start_time',
                'message' => 'The start time must be after '.date('h:i A', strtotime($open_at)).' and before '.date('h:i A', strtotime($close_at))
            ];
        }

        //checkning end time after work hour
        if ((strtotime($end_time) > strtotime($close_at)) || (strtotime($end_time) < strtotime($open_at))) {
            return [
                'status' =>false,
                'error_type' => 'end_time',
                'message' => 'The end time must be before '.date('h:i A', strtotime($close_at))
            ];
        }

        return [
            'status' =>true
        ];
    }

    public function reservation_list($request)
    {
        $order_by = 'DESC';
        if(isset($request->order_by) && !empty($request->order_by))
        $order_by = $request->order_by;

        $reservation_query = Reservation::with(['client_info','table_info','location_info'])->where('business_id', $request->business_id);
                    if(isset($request->future) && $request->future == true)
                        $reservation_query = $reservation_query->whereDate('request_date', '>=', date('Y-m-d'));

                    if(isset($request->future) && $request->future == false)
                        $reservation_query = $reservation_query->whereDate('request_date','<', date('Y-m-d'));

                    if(isset($request->status) && !empty($request->status))
                        $reservation_query = $reservation_query->where('status', $request->status);

                    if(isset($request->statuses) && !empty($request->statuses))
                        $reservation_query = $reservation_query->whereIn('status', $request->statuses);

                    if (isset($request->location) && !empty($request->location))
                        $reservation_query = $reservation_query->where('location_id', $request->location);

                    if (isset($request->from_date) && !empty($request->from_date))
                        $reservation_query = $reservation_query->whereDate('request_date', '>=', $request->from_date);

                    if (isset($request->end_date) && !empty($request->end_date))
                        $reservation_query = $reservation_query->whereDate('request_date', '<=', $request->end_date);

                    if (isset($request->client) && !empty($request->client))
                        $reservation_query = $reservation_query->where('client_id', $request->client);

                    if (isset($request->table) && !empty($request->table))
                        $reservation_query = $reservation_query->where('cafetable_id', $request->table);

        $reservation = $reservation_query->orderBy('request_date', $order_by);

        return $reservation;
    }
}
