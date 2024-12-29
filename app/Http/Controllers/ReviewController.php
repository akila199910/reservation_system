<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index($id)
    {
        $reservation = Reservation::where('ref_no',$id)->first();

        if (!$reservation) {
            return abort(404);
        }

        $view = 'review.index';
        if (isset($reservation->review_info)) {
            $view = 'review.already_review';
        }

        return view($view,[
            'reservation' => $reservation
        ]);
    }

    public function submit_review(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'score' => 'required',
                'review_message' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => false,  'errors' => $validator->errors()]);
        }

        $review = new ReservationReview();
        $review->reservation_id = $request->review_id;
        $review->no_stars = $request->score;
        $review->message = $request->review_message;
        $review->save();

        return response()->json(['status' => true,  'message' => 'Review Submitted Successfully!']);
    }
}
