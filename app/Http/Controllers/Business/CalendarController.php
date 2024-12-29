<?php

namespace App\Http\Controllers\Business;

use App\Models\Client;
use App\Models\CafeTable;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\TablePreference;
use App\Models\BusinessLocation;
use App\Http\Controllers\Controller;
use App\Models\BusinessLocationsWorkingHours;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ReservationRepository;

class CalendarController extends Controller
{
    private $business_id;
    private $reservation_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->reservation_repo = new ReservationRepository();
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

        //Locations
        $default_location = BusinessLocation::where('business_id', $this->business_id)->where(['is_default' => 1, 'status' => 1])->first();
        $first_location = BusinessLocation::where('business_id', $this->business_id)->where(['status' => 1])->orderBy('id', 'ASC')->first();

        $location_id = '';
        //Getting First Location
        if ($first_location) {
            $location_id = $first_location->id;
        }

        //checking defaul location available or not. If available set the location id as default location
        if ($default_location) {
            $location_id = $default_location->id;
        }

        if (session()->get('_location_id')) {
            session()->forget('_location_id');
        }

        if ($location_id != '') {
            session()->put('_location_id', $location_id);
        }

        $locations = BusinessLocation::where('business_id', $this->business_id)->where(['status' => 1])->get();
        //-------------------------- End

        //-------------------------- Preference
        $default_preference_query = TablePreference::where('business_id', $this->business_id);
        if ($location_id != '')
            $default_preference_query = $default_preference_query->where('location_id', $location_id);

        $default_preference = $default_preference_query->where(['is_default' => 1, 'status' => 1])->first();


        $first_preference_query = TablePreference::where('business_id', $this->business_id);
        if ($location_id != '')
            $first_preference_query = $first_preference_query->where('location_id', $location_id);

        $first_preference = $first_preference_query->where(['status' => 1])->orderBy('id', 'ASC')->first();

        $preference_id = '';
        if ($first_preference) {
            $preference_id = $first_preference->id;
        }

        if ($default_preference) {
            $preference_id = $default_preference->id;
        }

        if (session()->get('_preference_id')) {
            session()->forget('_preference_id');
        }

        if ($preference_id != '') {
            session()->put('_preference_id', $preference_id);
        }

        $preferences_query = TablePreference::where('business_id', $this->business_id);
        if ($location_id != '')
            $preferences_query = $preferences_query->where('location_id', $location_id);

        $preferences = $preferences_query->where(['status' => 1])->get();
        //-- End

        $clients = Client::where('business_id', $this->business_id)->where('status', 1)->get();

        $selected_true = false;
        $premission_create = user_permission_check($user, 'Create_Reservation');

        if ($premission_create == true) {
            $selected_true = true;
        }

        return view('business.reservation_calendar.index', [
            'locations' => $locations,
            'location_id' => $location_id,
            'preferences' => $preferences,
            'preference_id' => $preference_id,
            'clients' => $clients,
            'selected_true' => $selected_true
        ]);
    }

    public function get_resources(Request $request)
    {
        $cafe_table_query = CafeTable::where('business_id', $this->business_id);
        if (session()->get('_location_id'))
            $cafe_table_query = $cafe_table_query->where('location_id', session()->get('_location_id'));

        if (session()->get('_preference_id'))
            $cafe_table_query = $cafe_table_query->where('perference_id', session()->get('_preference_id'));

        $cafe_table = $cafe_table_query->where('status', 1)->get()->toArray();

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $data = [];
        foreach ($cafe_table as $item) {
            $name = $item['name'];

            $workDays = [];
            foreach ($days as $key => $day) {
                $work_hour_query = BusinessLocationsWorkingHours::where('business_id',$item['business_id'])->where('week_day',$day)->where('status',1);
                        if (session()->get('_location_id'))
                            $work_hour_query = $work_hour_query->where('location_id',session()->get('_location_id'));

                $work_hour = $work_hour_query->first();

                if ($work_hour) {
                    $workDays[] = [
                        'daysOfWeek' => [$key],
                        'startTime' => date('H:i', strtotime($work_hour->opens_at)),
                        'endTime' => date('H:i', strtotime($work_hour->close_at)),
                    ];
                }
            }

            $data[] = [
                'id' => $item['id'],
                'title' => $item['name'],
                'businessHours' => $workDays
            ];
        }

        return response()->json($data);
    }

    public function get_events(Request $request)
    {
        if (session()->get('_preference_id')) {
            $tables_id = CafeTable::where('perference_id', session()->get('_preference_id'))->where('status', 1)->pluck('id')->toArray();
        }

        $reservation_query = Reservation::with(['client_info', 'table_info', 'business_info', 'location_info'])->where('business_id', $this->business_id);
        if (session()->get('_location_id'))
            $reservation_query = $reservation_query->where('location_id', session()->get('_location_id'));

        if (session()->get('_preference_id'))
            $reservation_query = $reservation_query->whereIn('cafetable_id', $tables_id);

        $reservations = $reservation_query->whereNotIn('status', [1, 3])->get()->toArray();

        $data = [];
        foreach ($reservations as $item) {
            $complete_btn = false;
            if ($item['status'] == 0) {
                $backgroundColor = '#FFC107'; //Pending
            }

            if ($item['status'] == 1) {
                $backgroundColor = '#DC3545'; //Rejected
            }

            if ($item['status'] == 2) {
                $backgroundColor = '#007BFF'; //Confirmed
            }

            if ($item['status'] == 3) {
                $backgroundColor = '#6C757D'; //Cancelled
            }

            if ($item['status'] == 4) {
                $backgroundColor = '#28A745'; //Completed
            }

            $paid_status = false;
            if ($item['paid_status'] == 1) {
                $paid_status = true;
            }

            if ($item['status'] == 2 && (strtotime(date('Y-m-d H:i:s')) >= strtotime($item['request_end_time']))) {
                $complete_btn = true;
            }

            $edit_button = true;
            if ($item['status'] == 0  && (strtotime(date('Y-m-d H:i:s')) >= strtotime($item['request_start_time']))) {
                $edit_button = false;
            }

            $btn_show = true;
            if ((strtotime(date('Y-m-d H:i:s')) >= strtotime($item['request_start_time']))) {
                $btn_show = false;
            }

            $btn_pay_button = false;
            $user = Auth::user();
            $check_premission = user_permission_check($user, 'Update_Reservation');

            if($item['paid_status'] == 0 && $check_premission)
            {
                $btn_pay_button = true;
            }

            $data[] = [
                '_id' => $item['id'],
                'resourceId' => $item['cafetable_id'],
                'title' => $item['client_info']['name'],
                'start' => $item['request_start_time'],
                'end' => $item['request_end_time'],
                'backgroundColor' => $backgroundColor,
                'type' => 'Appointment',
                'calendar' => 'Sales',
                'className' => 'colorAppointment',
                'username' => $item['client_info']['ref_no'],
                'textColor' => "#ffffff",
                'description' => $item['reservation_note'],
                'extendedProps' => [
                    'paid_status' => $paid_status,
                    'reservation_status' => $item['status'],
                    'ref_no' => $item['ref_no'],
                    'complete_btn' => $complete_btn,
                    'edit_button' => $edit_button,
                    'btn_show' => $btn_show,
                    'btn_pay_button' => $btn_pay_button
                ]
            ];
        }

        return response()->json($data);
    }

    public function get_location_work_hours()
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $data = [];
        foreach ($days as $key => $day) {
            $work_hour_query = BusinessLocationsWorkingHours::where('business_id',$this->business_id)->where('week_day',$day)->where('status',1);
                    if (session()->get('_location_id'))
                        $work_hour_query = $work_hour_query->where('location_id',session()->get('_location_id'));

            $work_hour = $work_hour_query->first();

            if ($work_hour) {
                $data[] = [
                    'daysOfWeek' => [$key],
                    'startTime' => date('H:i', strtotime($work_hour->opens_at)),
                    'endTime' => date('H:i', strtotime($work_hour->close_at)),
                ];
            }
        }

        return response()->json($data);
    }

    public function change_location(Request $request)
    {
        $location_id = $request->location_id;
        session()->put('_location_id', $location_id);

        if (session()->get('_preference_id')) {
            session()->forget('_preference_id');
        }

        $preferences_query = TablePreference::where('business_id', $this->business_id);
        if ($location_id != '')
            $preferences_query = $preferences_query->where('location_id', $location_id);

        $preferences = $preferences_query->where(['status' => 1])->get();

        return response()->json(['status' => true, 'data' => $preferences]);
    }

    public function change_preference(Request $request)
    {
        $preference_id = $request->preference_id;
        session()->put('_preference_id', $preference_id);

        return response()->json(['status' => true, 'data' => 'success']);
    }

    //gettting the single reservation details
    public function get_detail(Request $request)
    {
        $reservation = Reservation::where('id', $request->reservation_id)->where('business_id', $this->business_id)->first();
        $data = [];
        $status = false;
        if ($reservation) {
            $request->merge([
                'ref_no' => $reservation->ref_no
            ]);

            $data = $this->reservation_repo->get_reservation_info($request);

            if (isset($data) && !empty($data)) {
                return view('business.reservation_calendar.details', [
                    'data' => $data
                ]);
            }
        }

        return response()->json(['status' => false]);
    }

    public function validations(Request $request)
    {
        $current_date_time = date('Y-m-d H:i:s');
        $requested_date = date('Y-m-d', strtotime($request->start_time));

        $start_time = date('H:i:s', strtotime($request->start_time));
        $end_time = date('H:i:s', strtotime($request->end_time));

        $start_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $start_time));
        $end_time = date('Y-m-d H:i:s', strtotime($requested_date . ' ' . $end_time));

        if (strtotime($current_date_time) > strtotime($start_time)) {
            return response()->json(['status' => false,  'message' => 'The start time must be future time']);
        }

        if (strtotime($end_time) <= strtotime($start_time)) {
            return response()->json(['status' => false,  'message' => 'The end time must be after start time']);
        }

        //get the minutes
        $minutes = $this->reservation_repo->calculate_minutes($start_time,$end_time);

        if ($minutes < 60) {
            $end_time = date('Y-m-d H:i:s', strtotime('+60minutes', strtotime($start_time)));
        }

        //clients
        $clients = Client::where('business_id', $this->business_id)->where('status', 1)->get();

        //table
        $tables = CafeTable::find($request->table_id);

        $request->merge([
            'start_time' => $start_time,
            'end_time' => $end_time,
            'business_id' => $this->business_id,
            'location' => $tables->location_id
        ]);

        //checking the table reservations
        $end_time_validation = $this->reservation_repo->check_table_reservation($request);

        if ($end_time_validation == false) {
            return response()->json(['status' => false,  'message' => 'Please check the selected time']);
        }

        return view('business.reservation_calendar.create', [
            'clients' => $clients,
            'requested_date' => $requested_date,
            'start_time' => date('h:i A', strtotime($start_time)),
            'end_time' => date('h:i A', strtotime($end_time)),
            'tables' => $tables
        ]);
    }

    public function update_view(Request $request)
    {
        $reservation = Reservation::find($request->reservation_id);

        //clients
        $clients = Client::where('business_id', $this->business_id)->where('status', 1)->get();

        //table
        $tables = CafeTable::find($reservation->cafetable_id);

        $requested_date = date('Y-m-d', strtotime($reservation->request_date));
        $start_time = $reservation->request_start_time;
        $end_time = $reservation->request_end_time;

        return view('business.reservation_calendar.update', [
            'clients' => $clients,
            'tables' => $tables,
            'reservation' => $reservation,
            'requested_date' => $requested_date,
            'start_time' => date('h:i A', strtotime($start_time)),
            'end_time' => date('h:i A', strtotime($end_time)),
        ]);
    }
}
