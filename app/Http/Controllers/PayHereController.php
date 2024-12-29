<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\PayherePayment;

class PayHereController extends Controller
{
    public function paynow_view($id)
    {
        $reservation = Reservation::where('ref_no',$id)->first();

        if (!$reservation) {
            return abort(404);
        }

        session()->put('reservation_ref_no', $reservation->ref_no);

        $view = 'payhere.index';
        if ($reservation->paid_status != 0) {
            $view = 'payhere.already_paid';
        }

        return view($view,[
            'reservation' => $reservation
        ]);
    }

    public function payment_cancel()
    {
        if (session()->get('reservation_ref_no')) {
            $id = session()->get('reservation_ref_no');
            return redirect()->route('payhere.reservation.payment.view',$id);
        } else {
            return view('payhere.cancel');
        }
    }

    public function payment_approved(Request $request)
    {
        $merchant_id         = $request->merchant_id;
        $order_id            = $request->order_id;
        $payhere_amount      = number_format($request->payhere_amount, 2, '.', '');
        $payhere_currency    = $request->payhere_currency;
        $status_code         = $request->status_code;
        $md5sig              = $request->md5sig;

        $merchant_secret = env('PAYHERE_MERCHANT_SECRET');

        $local_md5sig = strtoupper(
            md5(
                $merchant_id .
                $order_id .
                $payhere_amount .
                $payhere_currency .
                $status_code .
                strtoupper(md5($merchant_secret))
            )
        );

        if (($local_md5sig === $md5sig) && ($status_code == 2)) {
            //update the Billing Status
            $reservation = Reservation::where('ref_no',$order_id)->first();

            if ($reservation) {
                $reservation->paid_status = 1;
                $reservation->payment_type = 2; //Online Transfer
                $reservation->update();

                //Save the Payment Details
                $payment = new PayherePayment();
                $payment->order_id = $reservation->id;
                $payment->payment_id = $request->payment_id;
                $payment->payhere_amount = $payhere_amount;
                $payment->payhere_currency = $payhere_currency;
                $payment->status_code = $status_code;
                $payment->save();
            }
        }
    }

    public function payment_success(Request $request)
    {
        return view('payhere.success');
    }
}
