<?php

namespace App\Http\Controllers\Business;

use App\Models\Client;
use App\Models\CafeTable;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\TablePreference;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CafeTableRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReservationRepository;

class ReservationController extends Controller
{
    private $business_id;
    private $reservation_repo;
    private $table_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->reservation_repo = new ReservationRepository();
        $this->table_repo = new CafeTableRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Reservation');

        if ($check_premission == false) {
            return abort(404);
        }
        //END

        if (isset($request->json)) {
            $request->merge([
                'business_id' => $this->business_id
            ]);

            $reservation = $this->reservation_repo->reservation_list($request);

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
                    if ($dropdown_status == true) {
                        $action = $this->reservation_status($item);

                        $dropdown_action = '<div class="dropdown-menu dropdown-menu-end status-staff" style="">
                                                ' . $action . '
                                            </div>';
                    }

                    $dropdown = '<div class="dropdown action-label">
                                    <a class="custom-badge status-' . $color . ' ' . ($dropdown_status == true ? 'dropdown-toggle' : '') . ' " href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">
                                        ' . $status . '
                                    </a>
                                    ' . $dropdown_action . '
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
                        $user = Auth::user();
                        $check_premission = user_permission_check($user, 'Update_Reservation');

                        if ($check_premission == false) {
                            return '<span class="badge badge-soft-warning badge-border">Not Paid</span>';
                        } else {
                            return '<span class="badge badge-soft-warning badge-border">Not Paid</span>
                                <button type="button" onclick="change_pay_status(' . $item->id . ')" class="btn btn-sm btn-outline-primary" title="Click here to update the payment status" style="font-size:10px"><i class="fas fa-check"></i></button>';
                        }
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
                    $view_url = route('business.reservation.view_details', $item->ref_no);

                    if (($item->status == 0) && (strtotime(date('Y-m-d H:i:s')) < strtotime($item->request_start_time))) {
                        $edit_url = route('business.reservation.update.form', $item->ref_no);
                    }

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'Reservation', $edit_url, $item->id, $view_url);

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

        return view('business.reservation.index');
    }

    function reservation_status($item)
    {
        $action = '';
        if ($item->status == 0 && (strtotime(date('Y-m-d H:i:s')) < strtotime($item->request_start_time))) {
            $action = '<a class="dropdown-item" href="javascript:;" onclick="change_status(' . $item->id . ',1)">Rejected</a>
                        <a class="dropdown-item" href="javascript:;" onclick="change_status(' . $item->id . ',2)">Confirmed</a>';
        }

        if ($item->status == 2 && (strtotime(date('Y-m-d H:i:s')) < strtotime($item->request_start_time))) {
            $action = '<a class="dropdown-item" href="javascript:;" onclick="change_status(' . $item->id . ',3)">Cancelled</a>';
        }

        if ($item->status == 2 && (strtotime(date('Y-m-d H:i:s')) >= strtotime($item->request_end_time))) {
            $action .= '<a class="dropdown-item" href="javascript:;" onclick="change_status(' . $item->id . ',4)">Completed</a>';
        }

        return $action;
    }

    public function create_form(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Reservation');

        if ($check_premission == false) {
            return abort(404);
        }
        //END

        $client = Client::where('business_id', $this->business_id)->where('status', 1)->get();
        $tables = CafeTable::where('business_id', $this->business_id)->where('status', 1)->get();
        $location = BusinessLocation::where('business_id', $this->business_id)->where('status', 1)->get();
        $preference = TablePreference::where('business_id', $this->business_id)->where('status', 1)->get();

        return view('business.reservation.create', [
            'client' => $client,
            'tables' => $tables,
            'location' => $location,
            'preference' => $preference
        ]);
    }

    public function get_available_table(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'location' => 'required',
                'requested_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $current_date_time = date('Y-m-d H:i:s');
        $requested_date = date('Y-m-d', strtotime($request->requested_date));

        $start_time = date('H:i:s', strtotime($request->start_time));
        $end_time = date('H:i:s', strtotime($request->end_time));

        $start_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $end_time));

        if (strtotime($current_date_time) > strtotime($start_time)) {
            return response()->json(['status' => false,  'message' => ['start_time' => 'The start time must be future time']]);
        }

        if (strtotime($end_time) <= strtotime($start_time)) {
            return response()->json(['status' => false,  'message' => ['end_time' => 'The end time must be after start time']]);
        }

        $request->merge([
            'business_id' => $this->business_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'interval_min' => 0
        ]);

        //check the business open
        $loc_business = $this->reservation_repo->location_work_hours($request);

        if ($loc_business['status'] == false) {
            return response()->json(['status' => false,  'message' => [$loc_business['error_type'] => $loc_business['message']]]);
        }

        //get the minutes
        $minutes = $this->reservation_repo->calculate_minutes($start_time, $end_time);

        if ($minutes < 60) {
            return response()->json(['status' => false,  'message' => ['end_time' => 'Reservation time duration must be grater than or equal to 1 hour']]);
        }

        $tables = $this->reservation_repo->available_table($request);

        if (count($tables) == 0) {
            return response()->json(['status' => false,  'message' => ['no_table' => 'Tables not available!']]);
        }

        return view('business.reservation.table_reservation', [
            'tables' => $tables
        ]);

        return response()->json(['status' => true, 'data' => $tables]);
    }

    public function table_details(Request $request)
    {
        $request->merge([
            'business_id' => $this->business_id
        ]);

        $tables = $this->table_repo->table_info($request);

        if ($tables['status'] == false) {
            return response()->json(['status' => false,  'message' => ['table' => 'No tables available']]);
        }

        return response()->json(['status' => true, 'data' => $tables['data']]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'client' => 'required',
                'location' => 'required',
                'requested_date' => 'required',
                'end_time' => 'required',
                'start_time' => 'required',
                'table' => 'required',
                'no_of_people' => 'required',
                'no_of_extra_people' => 'nullable|numeric|min:0|max:100',
                'table_amount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'service_charge' => 'nullable|numeric|min:0',
                'extra_people_charge' => 'nullable|numeric|min:0',
                'total_amount' => 'nullable',
                'reservation_note' => 'nullable|regex:/^[a-z A-Z 0-9]+$/u'
            ],
            [
                'table.required' => 'Select the table',
                'no_of_extra_people.max' => 'The maximum number of extra people is 100',
                'no_of_extra_people.min' => 'The number of extra people cannot be less than 0'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $requested_date = date('Y-m-d', strtotime($request->requested_date));

        $start_time = date('H:i:s', strtotime($request->start_time));
        $end_time = date('H:i:s', strtotime($request->end_time));

        $start_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $end_time));

        //get the minutes
        $minutes = $this->reservation_repo->calculate_minutes($start_time, $end_time);

        if ($minutes < 60) {
            return response()->json(['status' => false,  'message' => ['duration' => 'Reservation time duration must be grater than or equal to 1 hour']]);
        }

        $table = CafeTable::where('ref_no', $request->table)->where('business_id', $this->business_id)->first();

        $request->merge([
            'business_id' => $this->business_id,
            'table_id' => $table->id
        ]);

        $data = $this->reservation_repo->create_reservation($request);

        $data['status'] = true;
        $data['message'] = 'New Reservation created successfully!';
        $data['route'] = route('business.reservation');

        return response()->json($data);
    }

    public function update_form(Request $request, $id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Reservation');

        if ($check_premission == false) {
            return abort(404);
        }
        //END

        $reservation = Reservation::where('ref_no', $id)->first();

        if (!$reservation) {
            return abort(404);
        }

        $client = Client::where('business_id', $this->business_id)->where('status', 1)->get();

        $request->merge([
            'business_id' => $this->business_id,
            'table_id' => $reservation->table_info->ref_no,
            'preference_id'  => $reservation->table_info->perference_id,
            'requested_date' => $reservation->request_date,
            'start_time' => $reservation->request_start_time,
            'end_time' => $reservation->request_end_time,
            'interval_min' => 15,
            'location' => $reservation->location_id,
            'reservation_id' =>  $reservation->reservation_id

        ]);

        //getting available table based on reservation date
        $tables = $this->reservation_repo->available_table($request);
        $tables_ref = array_column($tables, 'id');

        $tables = CafeTable::whereIn('ref_no', $tables_ref)->where('business_id', $this->business_id)->where('status', 1)->get();

        $location = BusinessLocation::where('business_id', $this->business_id)->where('status', 1)->get();

        //getting current table infor
        $tables_info = $this->table_repo->table_info($request);

        $preference = TablePreference::where('business_id', $this->business_id)->where('location_id', $reservation->location_id)->where('status', 1)->get();

        return view('business.reservation.update', [
            'client' => $client,
            'tables' => $tables,
            'reservation' => $reservation,
            'tables_info' => $tables_info['data'],
            'location' => $location,
            'preference' => $preference
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'client' => 'required',
                'location' => 'required',
                'requested_date' => 'required',
                'end_time' => 'required',
                'start_time' => 'required',
                'table' => 'required',
                'no_of_people' => 'required',
                'no_of_extra_people' => 'nullable|numeric|min:0|max:100',
                'table_amount' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'service_charge' => 'nullable|numeric|min:0',
                'extra_people_charge' => 'nullable|numeric|min:0',
                'total_amount' => 'nullable',
                'reservation_note' => 'nullable|regex:/^[a-z A-Z 0-9]+$/u'
            ],
            [
                'no_of_extra_people.max' => 'The maximum number of extra people is 100',
                'no_of_extra_people.min' => 'The number of extra people cannot be less than 0'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $requested_date = date('Y-m-d', strtotime($request->requested_date));

        $start_time = date('H:i:s', strtotime($request->start_time));
        $end_time = date('H:i:s', strtotime($request->end_time));

        $start_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $end_time));

        //get the minutes
        $minutes = $this->reservation_repo->calculate_minutes($start_time, $end_time);

        if ($minutes < 60) {
            return response()->json(['status' => false,  'message' => ['duration' => 'Reservation time duration must be grater than or equal to 1 hour']]);
        }

        $table = CafeTable::where('ref_no', $request->table)->where('business_id', $this->business_id)->first();

        $request->merge([
            'business_id' => $this->business_id,
            'table_id' => $table->id
        ]);

        //check the business open
        $loc_business = $this->reservation_repo->location_work_hours($request);

        if ($loc_business['status'] == false) {
            return response()->json(['status' => false,  'message' => [$loc_business['error_type'] => $loc_business['message']]]);
        }

        $data = $this->reservation_repo->update_reservation($request);

        $data['status'] = true;
        $data['message'] = 'Selected Reservation Updated successfully!';
        $data['route'] = route('business.reservation');

        return response()->json($data);
    }

    public function change_status(Request $request)
    {
        $data = $this->reservation_repo->change_reservation_status($request);

        if ($data['status'] == false) {
            $data['status'] = false;
            $data['message'] = 'Selected Reservation Payment Status did not updated. Please update the payment status before completed!';
            $data['route'] = route('business.reservation.update.form', $data['ref_no']);

            return response()->json($data);
        }

        $data['status'] = true;
        $data['message'] = 'Selected Reservation Updated successfully!';
        $data['route'] = route('business.reservation');

        return response()->json($data);
    }

    public function get_existing_booking(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'location' => 'required',
                'requested_date' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $data = $this->reservation_repo->booking_list($request);

        return response()->json(['status' => true,  'data' => $data]);
    }

    public function get_section(Request $request)
    {
        $preference_query = TablePreference::where('business_id', $this->business_id);
        if (isset($request->location) && !empty($request->location))
            $preference_query = $preference_query->where('location_id', $request->location);

        $preference = $preference_query->where('status', 1)->get();

        $reservation = [];

        $view = 'business.reservation.section_content';
        if (isset($request->reservation_id) && !empty($request->reservation_id)) {
            $reservation = Reservation::find($request->reservation_id);

            $view = 'business.reservation.update_section_content';
        }

        if (count($preference) == 0) {
            return response()->json(['status' => false, 'message' => 'There is no section found in selected location. Try different location.']);
        }

        return view($view, [
            'preference' => $preference,
            'reservation' => $reservation
        ]);
    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Reservation');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $reservation = Reservation::with(['client_info', 'table_info', 'location_info'])->where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$reservation) {
            return abort(404);
        }

        $request->merge([
            'ref_no' => $reservation->ref_no
        ]);

        $data = $this->reservation_repo->get_reservation_info($request);

        return view('business.reservation.view_details', [
            'data' => $data
        ]);
    }

    public function delete(Request $request)
    {
        Reservation::destroy($request->id);

        return response()->json(['status' => true, 'message' => 'Selected Reservation Deleted Successfully']);
    }

    public function get_update_data(Request $request)
    {
        $reservation = Reservation::find($request->reservation_id);

        $status = false;
        if ($reservation) {

            $client = Client::where('business_id', $this->business_id)->where('status', 1)->get();

            $request->merge([
                'business_id' => $this->business_id,
                'table_id' => $reservation->table_info->ref_no,
                'preference_id'  => $reservation->table_info->perference_id,
                'requested_date' => $reservation->request_date,
                'start_time' => $reservation->request_start_time,
                'end_time' => $reservation->request_end_time,
                'interval_min' => 15,
                'location' => $reservation->location_id,
            ]);

            //getting available table based on reservation date
            $tables = $this->reservation_repo->available_table($request);
            $tables_ref = array_column($tables, 'id');

            $tables = CafeTable::whereIn('ref_no', $tables_ref)->where('business_id', $this->business_id)->where('status', 1)->get();

            $location = BusinessLocation::where('business_id', $this->business_id)->where('status', 1)->get();

            //getting current table infor
            $tables_info = $this->table_repo->table_info($request);

            $preference = TablePreference::where('business_id', $this->business_id)->where('location_id', $reservation->location_id)->where('status', 1)->get();

            if (count($tables) && count($preference)) {
                $status = true;
            }
        }

        return response()->json(['status' => $status]);
    }

    public function get_payment_status(Request $request)
    {
        $reservation = Reservation::find($request->id);

        $view = 'business.reservation.update_payment';
        if (isset($request->view) && !empty($request->view)) {
            $view = 'business.reservation_calendar.update_payment';
        }

        return view($view,[
            'reservation' => $reservation
        ]);
    }

    public function update_payment_status(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'payment_type' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $reservation = Reservation::find($request->id);
        $reservation->payment_type = $request->payment_type;
        $reservation->paid_status = $request->payment_type == 0 ? 0 : 1;
        $reservation->update();

        return response()->json(['status' => true, 'message' => 'Selected reservation payment status updated successfully!']);
    }
}
