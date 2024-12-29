<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use App\Models\Reservation;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    private $business_id;
    private $report_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->report_repo = new ReportRepository();
    }

    public function index(Request $request)
    {
        // Check User Permission
        $user = Auth::user();
        $check_permission = user_permission_check($user, 'Read_Report');

        if (!$check_permission) {
            return abort(404);
        }
        $cafes = CafeTable::where('business_id', $this->business_id)->where('status', 1)->get();

        if ($request->ajax()) {

            $reservations = Reservation::with(['client_info', 'table_info', 'location_info'])
                ->where('business_id', $this->business_id);

            if ($request->has('cafe') && !empty($request->cafe)) {
                $reservations->whereHas('table_info', function ($query) use ($request) {
                    $query->where('id', $request->cafe);
                });
            }

            // if ($request->has('status') ) {
            //     $reservations->where('status',$request->status);
            // }
            if ($request->has('status') && $request->status !== null && $request->status !== '') {
                $reservations->where('status', $request->status);
            }

            if ($request->has('from') && !empty($request->from)) {
                $from = $request->from;

                $reservations->whereDate('request_date', '>=', $from);
            }

            if ($request->has('to') && !empty($request->to)) {
                $to = $request->to;
                $reservations->whereDate('request_date', '<=', $to);
            }


            $data = DataTables::of($reservations)
                ->addIndexColumn()
                ->addColumn('status', function ($item) {
                    $status = '';
                    $color = 'pending';

                    switch ($item->status) {
                        case 0:
                            $status = 'Pending';
                            $color = 'pending';
                            break;
                        case 1:
                            $status = 'Rejected';
                            $color = 'rejected';
                            break;
                        case 2:
                            $status = 'Confirmed';
                            $color = 'confirmed';
                            break;
                        case 3:
                            $status = 'Cancelled';
                            $color = 'canceled';
                            break;
                        case 4:
                            $status = 'Completed';
                            $color = 'completed';
                            break;
                    }

                    return '<span class="custom-badge status-' . $color . '">' . $status . '</span>';
                })
                ->addColumn('client_name', function ($item) {
                    return $item->client_info ? $item->client_info->name : '';
                })
                ->addColumn('location_name', function ($item) {
                    return $item->location_info ? $item->location_info->location_name : '';
                })
                ->addColumn('client_contact', function ($item) {
                    return $item->client_info ? $item->client_info->contact : '';
                })
                ->addColumn('table_name', function ($item) {
                    return $item->table_info ? $item->table_info->name : '';
                })
                ->addColumn('paid_status', function ($item) {
                    if ($item->paid_status == 0) {
                        return '<span class="badge badge-soft-warning badge-border">Not Paid</span>';
                    }

                    if ($item->paid_status == 1) {
                        $pay_method = $item->payment_type == 1 ? 'Direct Pay' : 'Online Pay';
                        return '<span class="badge badge-soft-success badge-border">Paid - ' . $pay_method . '</span>';
                    }
                })
                ->rawColumns(['status', 'client_name', 'table_name', 'client_contact', 'paid_status', 'location_name'])
                ->make(true);

            return $data;
        }

        return view('business.Reports.index', compact('cafes'));
    }

    public function graph()
    {
        $currentYear = date('Y');

        $monthlyCounts = array_fill(1, 12, 0);

        $reservations = Reservation::selectRaw('MONTH(request_date) as month, COUNT(*) as count')
            ->Where('business_id',$this->business_id)
            ->where('status', 4)
            ->whereYear('request_date', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month');

        foreach ($reservations as $month => $count) {
            $monthlyCounts[$month] = $count;
        }

        return response()->json([
            'status' => true,
            'monthlyCounts' => $monthlyCounts,
        ]);
    }

    public function graphTable()
    {
        $data = Reservation::select('cafe_tables.name', DB::raw('count(*) as total'))
        ->join('cafe_tables', 'reservations.cafetable_id', '=', 'cafe_tables.id')
        ->Where('reservations.business_id',$this->business_id)
        ->where('reservations.status', 4)
        ->groupBy('cafe_tables.name')
        ->pluck('total', 'cafe_tables.name');

        $tablelyCounts = [];
            foreach ($data as $tableName => $total) {
                $tablelyCounts[$tableName] = $total;
        }
        return response()->json(['status' => true, 'tablelyCounts' => $tablelyCounts]);
    }

    public function report(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cafe' => 'nullable',
                'status' => 'nullable',
                'to'=>'nullable',
                'from'=>'nullable',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $export = $this->report_repo->createExport($request);

        return Excel::download($export, 'reservations_report_' . time() . '.xlsx');

    }
}
