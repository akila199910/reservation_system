<?php

namespace App\Http\Controllers;

use App\Models\NotificationSetting;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function reminder_mail_text($request)
    {
        $reminder_mail = NotificationSetting::where('reminder_mail', 1)->get();
        $start_time = date('Y-m-d H:i:s',strtotime('+1hours'));
        $end_time = date('Y-m-d H:i:s', strtotime('+15minutes',strtotime($start_time)));

        if (count($reminder_mail)) {
            foreach ($reminder_mail as $reminder) {

                $reservations = Reservation::where('business_id', $reminder->business_id)
                    ->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time)
                    ->where('status', 2)
                    ->where('remider_send_mail', 0)
                    ->get();

                foreach ($reservations as $reservation) {
                    $data["title"] = 'Reservation Reminder | ' . $reservation->business_info->name;
                    $data["email"] = $reservation->client_info->email;
                    $data["name"] = $reservation->client_info->name;
                    $data["company"] = $reservation->business_info;
                    $data["reservation"] = $reservation;
                    $data["view"] = 'mail.reservation.reminder';

                    //update the reminder mail status
                    $reservation->remider_send_mail = 1;
                    $reservation->update();

                    mailNotification($data);
                }
            }
        }

        //send reminder sms
        $reminder_text = NotificationSetting::where('reminder_text', 1)->get();

        if (count($reminder_text)) {
            foreach ($reminder_text as $reminder) {

                $reservations = Reservation::where('business_id', $reminder->business_id)
                    ->where('request_start_time', '>=', $start_time)
                    ->where('request_start_time', '<=', $end_time)
                    ->where('status', 2)
                    ->where('remider_send_text', 0)
                    ->get();

                foreach ($reservations as $reservation) {
                    //merge the request
                    $message = 'Reservation Reminder | ' . $reservation->business_info->name. " \n\n";
                    $message .= "Reference Number - " . $reservation->ref_no . " \n";
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

                    if ($reservation->paid_status == 0) {
                        $pay_link = "\n\n Click Here to Pay Now - " . route('payhere.reservation.payment.view', $reservation->ref_no);
                    }

                    if ($reservation->location_info->google_location != '') {
                        $message .= "Google Map - " . $reservation->location_info->google_location;
                    }

                    if ($pay_link != '') {
                        $message .= $pay_link;
                    }

                    //merge the request
                    $request->merge([
                        'snap_auth_key' => $reservation->business_info->snap_auth_key,
                        'client_name' => $reservation->client_info->name,
                        'client_contact' => $reservation->client_info->contact,
                        'message' => $message
                    ]);

                    send_slsnap_message($request);

                    //update the reminder mail status
                    $reservation->remider_send_text = 1;
                    $reservation->update();
                }
            }
        }
    }
}
