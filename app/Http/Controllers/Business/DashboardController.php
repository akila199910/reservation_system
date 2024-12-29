<?php

namespace App\Http\Controllers\Business;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessUsers;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    private $business_id;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });
    }

    public function index()
    {
        // Getting the current date
        $today = now()->toDateString();

        // total reservations
        $totalReservations = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->where('deleted_at', NULL)
            ->count();

        // today's total reservations
        $todayTotalReservations = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->where('deleted_at', NULL)
            ->whereDate('request_date', $today)
            ->count();

        // reservation counts by status
        $pendingCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 0)
            ->count();

        $rejectedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->count();

        $confirmedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 2)
            ->count();

        $cancelledCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 3)
            ->count();

        $completedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 4)
            ->count();

        $pastReservations = $cancelledCount + $completedCount + $rejectedCount;
        $currentReservations = $pendingCount + $confirmedCount;

        // Today's reservation counts by status
        $todayPendingCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 0)
            ->whereDate('request_date', $today)
            ->count();

        $todayRejectedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->whereDate('request_date', $today)
            ->count();

        $todayConfirmedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 2)
            ->whereDate('request_date', $today)
            ->count();

        $todayCancelledCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 3)
            ->whereDate('request_date', $today)
            ->count();

        $todayCompletedCount = DB::table('reservations')
            ->where('business_id', $this->business_id)
            ->whereNull('deleted_at')
            ->where('status', 4)
            ->whereDate('request_date', $today)
            ->count();

        $todayPastReservations = $todayCancelledCount + $todayCompletedCount + $todayRejectedCount;
        $todayCurrentReservations = $todayPendingCount + $todayConfirmedCount;

        return view('business.dashboard', [
            // reservation counts by status
            'totalReservations' => $totalReservations,
            'pendingCount' => $pendingCount,
            'rejectedCount' => $rejectedCount,
            'confirmedCount' => $confirmedCount,
            'cancelledCount' => $cancelledCount,
            'completedCount' => $completedCount,
            'pastReservations' => $pastReservations,
            'currentReservations' => $currentReservations,


            // Today's reservation counts by status
            'todayTotalReservations' => $todayTotalReservations,
            'todayPendingCount' => $todayPendingCount,
            'todayRejectedCount' => $todayRejectedCount,
            'todayConfirmedCount' => $todayConfirmedCount,
            'todayCancelledCount' => $todayCancelledCount,
            'todayCompletedCount' => $todayCompletedCount,
            'todayPastReservations' => $todayPastReservations,
            'todayCurrentReservations' => $todayCurrentReservations
        ]);
    }

    public function graph()
    {
        $business_id = $this->business_id;

        $counts = [
            'pending' => array_fill(0, 31, 0),
            'rejected' => array_fill(0, 31, 0),
            'confirmed' => array_fill(0, 31, 0),
            'cancelled' => array_fill(0, 31, 0),
            'completed' => array_fill(0, 31, 0),
        ];

        $assetCounts = DB::table('reservations')
            ->select(DB::raw('DAY(request_date) as day'), 'status', DB::raw('count(*) as count'))
            ->where('business_id', $business_id)
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('DAY(request_date)'), 'status')
            ->get();

        $statusMap = [
            0 => 'pending',
            1 => 'rejected',
            2 => 'confirmed',
            3 => 'cancelled',
            4 => 'completed'
        ];

        foreach ($assetCounts as $assetCount) {
            $dayIndex = $assetCount->day - 1; // Day index for array (0-based)
            $statusKey = $statusMap[$assetCount->status];

            $counts[$statusKey][$dayIndex] = $assetCount->count;
        }

        return response()->json($counts);
    }

    public function get_reservation(Request $request)
    {
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $current = false;
        $view ='business.reservation_all_list';

        if (isset($request->day) && !empty($request->day)) {
            if ($request->day == 'current') {
                $current = true;
                $view = 'business.reservation_list';
            }
        }

        return view($view, [
            'current' => $current,
            'status' => $request->status
        ]);
    }

    public function get_reservation_list(Request $request)
    {
        $reservation_query = Reservation::with(['client_info', 'table_info', 'location_info'])->where('business_id', $this->business_id);
        if (isset($request->current) && $request->current == true)
            $reservation_query = $reservation_query->whereDate('request_date', date('Y-m-d'));

        if (isset($request->status) && $request->status != '' )
            $reservation_query = $reservation_query->where('status', $request->status);

        $reservation = $reservation_query->orderBy('request_date', 'DESC');

        $data =  Datatables::of($reservation)
            ->addIndexColumn()
            ->addColumn('status', function ($item) {
                $status = '';
                $color = 'pending';
                $dropdown_status = false;

                if ($item->status == 0) {
                    $status = 'Pending';
                    $color = 'pending';
                    $dropdown_status = true;
                }

                if ($item->status == 1) {
                    $status = 'Rejected';
                    $color = 'rejected';
                    $dropdown_status = false;
                }

                if ($item->status == 2) {
                    $status = 'Confirmed';
                    $color = 'confirmed';
                    $dropdown_status = true;
                }

                if ($item->status == 3) {
                    $status = 'Cancelled';
                    $color = 'canceled';
                    $dropdown_status = false;
                }

                if ($item->status == 4) {
                    $status = 'Completed';
                    $color = 'completed';
                    $dropdown_status = false;
                }

                if (!Auth::user()->hasPermissionTo('Update_Reservation')) {
                    $dropdown_status = false;
                }

                $dropdown_action = '';
                if($dropdown_status == true)
                {
                    $action = $this->reservation_status($item);

                    $dropdown_action = '<div class="dropdown-menu dropdown-menu-end status-staff" style="">
                                            '.$action.'
                                        </div>';
                }

                $dropdown = '<div class="dropdown action-label">
                                <a class="custom-badge status-'.$color.' '.($dropdown_status == true ? 'dropdown-toggle' : '').' " href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">
                                    '.$status.'
                                </a>
                                '.$dropdown_action.'
                            </div>';

                return $dropdown;
            })
            ->addColumn('client_name', function ($item) {
                $client_name = '';

                if ($item->client_info) {
                    $client_name = $item->client_info->name;
                }

                return $client_name;
            })
            ->addColumn('location_name', function ($item) {
                $location_name = '';

                if ($item->location_info) {
                    $location_name = $item->location_info->location_name;
                }

                return $location_name;
            })
            ->addColumn('client_contact', function ($item) {
                $client_contact = '';

                if ($item->client_info) {
                    $client_contact = $item->client_info->contact;
                }

                return $client_contact;
            })
            ->addColumn('table_name', function ($item) {
                $table_name = '';

                if ($item->table_info) {
                    $table_name = $item->table_info->name;
                }

                return $table_name;
            })
            ->addColumn('paid_status', function ($item) {
                if ($item->paid_status == 0) {
                    return '<span class="badge badge-soft-warning badge-border">Not Paid</span>';
                }

                if ($item->paid_status == 1) {

                    $pay_method = '';

                    if ($item->payment_type == 1) {
                        $pay_method = 'Direct Pay';
                    }

                    if ($item->payment_type == 2) {
                        $pay_method = 'Online Pay';
                    }

                    return '<span class="badge badge-soft-success badge-border">Paid - ' . $pay_method . '</span>';
                }
            })
            ->addColumn('action', function ($item) {
                $user = Auth::user();
                $edit_url = '';
                if ($item->status == 0 || $item->status == 2) {
                    $edit_url = route('business.reservation.update.form', $item->ref_no);
                }
                $view_url = route('business.reservation.view_details', $item->ref_no);
                $actions = '';
                $actions .= action_btns($actions, $user, 'Reservation', $edit_url, $item->id,$view_url);

                $action = '<div class="dropdown dropdown-action">
                                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </a>
                                    <div class="dropdown-menu dropdown-menu-end">'
                    . $actions .
                    '</div>
                                </div>';

                return $action;
            })
            ->rawColumns(['action', 'status', 'client_name', 'table_name', 'client_contact', 'paid_status', 'location_name'])
            ->make(true);

        return $data;
    }

    function reservation_status($item)
    {
        $action = '';
        if ($item->status == 0) {
            $action = '<a class="dropdown-item" href="javascript:;" onclick="change_status('.$item->id.',1)">Rejected</a>
                        <a class="dropdown-item" href="javascript:;" onclick="change_status('.$item->id.',2)">Confirmed</a>';
        }

        if ($item->status == 2) {
            $action = '<a class="dropdown-item" href="javascript:;" onclick="change_status('.$item->id.',3)">Cancelled</a>';
        }

        if ($item->status == 2 && (strtotime(date('Y-m-d H:i:s')) >= strtotime($item->request_end_time))) {
            $action .= '<a class="dropdown-item" href="javascript:;" onclick="change_status('.$item->id.',4)">Completed</a>';
        }

        return $action;
    }

    function change_business(Request $request){

        $id = $request->id;
        $all_business_list = Business::all();

        session()->forget(['_business_id']);
        session()->put('_business_id', $id);
        return response()->json(['status'=>true, 'message'=>'Business added to session','all_business_list'=>$all_business_list]);
    }

    public function getBusinessList()
    {
        $user = auth()->user();
        if($user->hasRole('super_admin')||$user->hasRole('admin')){
            $all_business_list = Business::all();
        }else{
            $business_ids = BusinessUsers::Where('user_id',$user->id)->pluck('business_id');
            $all_business_list= Business::WhereIn('id',$business_ids)->get();
        }
        //$all_business_list = Business::all();
        return response()->json(['all_business_list' => $all_business_list]);
    }

}

