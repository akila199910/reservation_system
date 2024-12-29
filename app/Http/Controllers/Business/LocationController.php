<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LocationController extends Controller
{
    private $business_id;
    private $location_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->location_repo = new LocationRepository();
    }
    public function index(Request $request)
    {
        if ($request->json) {
            $business_locations = BusinessLocation::Where('business_id', $this->business_id);

            $data = DataTables::of($business_locations)
                ->addIndexColumn()
                ->addColumn('loc_name', function ($item) {

                    if ($item->is_default == 1) {
                        return $item->location_name . ' <i class="fas fa-check-circle text-primary"></i>';
                    } else {
                        return $item->location_name;
                    }
                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })

                ->addColumn('is_default', function ($item) {
                    if ($item->is_default == 0) {
                        return '<span class="badge badge-soft-danger badge-border">No</span>';
                    }

                    if ($item->is_default == 1) {
                        return '<span class="badge badge-soft-success badge-border">Yes</span>';
                    }
                })
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_url = route('business.Locations.update.form', $item->ref_no);
                    $view_url = route('business.Locations.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'Location', $edit_url, $item->id, $view_url);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        '</div></div>';

                    return $action;
                })
                ->rawColumns(['action', 'status', 'is_default', 'loc_name'])
                ->make(true);

            return $data;
        }

        return view('business.Locations.index');
    }

    public function create_form()
    {
        $weeks_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('business.Locations.create', ['weeks_days' => $weeks_days]);
    }

    public function create(Request $request)
    {
        // Primary validator
        $validator = Validator::make(
            $request->all(),
            [
                'location_name' =>'required|regex:/^[a-z A-Z 0-9\s,.\-\/\'#_]+$/u|unique:business_locations,location_name,NULL,id,deleted_at,NULL',
                'contact_no' => 'required|digits:10|unique:business_locations,contact_no',
                'email' => 'required|email:rfc,dns|max:190',
                'address' => 'required|regex:/^[a-z A-Z 0-9\s,.\-\/\'#]+$/u',
                'google_location' => 'nullable',
                'open_time_Monday' => 'nullable',
                'close_time_Monday' => 'nullable|after:open_time_Monday',
                'open_time_Tuesday' => 'nullable',
                'close_time_Tuesday' => 'nullable|after:open_time_Tuesday',
                'open_time_Wednesday' => 'nullable',
                'close_time_Wednesday' => 'nullable|after:open_time_Wednesday',
                'open_time_Thursday' => 'nullable',
                'close_time_Thursday' => 'nullable|after:open_time_Thursday',
                'open_time_Friday' => 'nullable',
                'close_time_Friday' => 'nullable|after:open_time_Friday',
                'open_time_Saturday' => 'nullable',
                'close_time_Saturday' => 'nullable|after:open_time_Saturday',
                'open_time_Sunday' => 'nullable',
                'close_time_Sunday' => 'nullable|after:open_time_Sunday',
            ],
            [
                // Custom error messages
                'close_time_Monday.after' => 'The Close At time must be after the Open At time for Monday.',
                'close_time_Tuesday.after' => 'The Close At time must be after the Open At time for Tuesday.',
                'close_time_Wednesday.after' => 'The Close At time must be after the Open At time for Wednesday.',
                'close_time_Thursday.after' => 'The Close At time must be after the Open At time for Thursday.',
                'close_time_Friday.after' => 'The Close At time must be after the Open At time for Friday.',
                'close_time_Saturday.after' => 'The Close At time must be after the Open At time for Saturday.',
                'close_time_Sunday.after' => 'The Close At time must be after the Open At time for Sunday.',
            ]
        );

        // Custom rules validator
        $customValidator = Validator::make(
            $request->all(),
            [
                'open_time_Monday' => 'nullable|required_with:close_time_Monday',
                'close_time_Monday' => 'nullable|required_with:open_time_Monday',
                'open_time_Tuesday' => 'nullable|required_with:close_time_Tuesday',
                'close_time_Tuesday' => 'nullable|required_with:open_time_Tuesday',
                'open_time_Wednesday' => 'nullable|required_with:close_time_Wednesday',
                'close_time_Wednesday' => 'nullable|required_with:open_time_Wednesday',
                'open_time_Thursday' => 'nullable|required_with:close_time_Thursday',
                'close_time_Thursday' => 'nullable|required_with:open_time_Thursday',
                'open_time_Friday' => 'nullable|required_with:close_time_Friday',
                'close_time_Friday' => 'nullable|required_with:open_time_Friday',
                'open_time_Saturday' => 'nullable|required_with:close_time_Saturday',
                'close_time_Saturday' => 'nullable|required_with:open_time_Saturday',
                'open_time_Sunday' => 'nullable|required_with:close_time_Sunday',
                'close_time_Sunday' => 'nullable|required_with:open_time_Sunday',
            ]
        );

        // Combine errors from both validators
        $primaryErrors = $validator->errors()->toArray();
        $customErrors = $customValidator->errors()->toArray();

        $errors = array_merge_recursive($primaryErrors, $customErrors);

        if ($validator->fails() || $customValidator->fails()) {
            return response()->json(['status' => false, 'message' => $errors]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $data = $this->location_repo->create($request);
        $data['route'] = route('business.Locations');

        return response()->json($data);
    }

    public function update_form($id)
    {
        $find__location = BusinessLocation::With(['workingHours'])->where(['ref_no' => $id])->first();
        $weeks_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('business.Locations.update', [
            'find__location' => $find__location,
            'weeks_days' => $weeks_days
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        // dd($request->all());

        $validator = Validator::make(
            $request->all(),
            [
                'location_name' => 'required|regex:/^[a-z A-Z 0-9\s,.\-\/\'#_]+$/u|unique:business_locations,location_name,' . $id. ',id,deleted_at,NULL',
                'email' => 'required|email:rfc,dns|max:190',
                'contact_no' => 'required|digits:10',
                'address' => 'required|regex:/^[a-zA-Z0-9\s,.\-\/\'#]+$/u',
                'google_location' => 'nullable',
                'open_time_Monday' => 'nullable',
                'close_time_Monday' => 'nullable|after:open_time_Monday',
                'open_time_Tuesday' => 'nullable',
                'close_time_Tuesday' => 'nullable|after:open_time_Tuesday',
                'open_time_Wednesday' => 'nullable',
                'close_time_Wednesday' => 'nullable|after:open_time_Wednesday',
                'open_time_Thursday' => 'nullable',
                'close_time_Thursday' => 'nullable|after:open_time_Thursday',
                'open_time_Friday' => 'nullable',
                'close_time_Friday' => 'nullable|after:open_time_Friday',
                'open_time_Saturday' => 'nullable',
                'close_time_Saturday' => 'nullable|after:open_time_Saturday',
                'open_time_Sunday' => 'nullable',
                'close_time_Sunday' => 'nullable|after:open_time_Sunday',
            ],
            [
                // Custom error messages
                'close_time_Monday.after' => 'The Close At time must be after the Open At time for Monday.',
                'close_time_Tuesday.after' => 'The Close At time must be after the Open At time for Tuesday.',
                'close_time_Wednesday.after' => 'The Close At time must be after the Open At time for Wednesday.',
                'close_time_Thursday.after' => 'The Close At time must be after the Open At time for Thursday.',
                'close_time_Friday.after' => 'The Close At time must be after the Open At time for Friday.',
                'close_time_Saturday.after' => 'The Close At time must be after the Open At time for Saturday.',
                'close_time_Sunday.after' => 'The Close At time must be after the Open At time for Sunday.',
            ]
        );

        $customRules = [
            'open_time_Monday' => 'nullable|required_with:close_time_Monday',
            'close_time_Monday' => 'nullable|required_with:open_time_Monday',
            'open_time_Tuesday' => 'nullable|required_with:close_time_Tuesday',
            'close_time_Tuesday' => 'nullable|required_with:open_time_Tuesday',
            'open_time_Wednesday' => 'nullable|required_with:close_time_Wednesday',
            'close_time_Wednesday' => 'nullable|required_with:open_time_Wednesday',
            'open_time_Thursday' => 'nullable|required_with:close_time_Thursday',
            'close_time_Thursday' => 'nullable|required_with:open_time_Thursday',
            'open_time_Friday' => 'nullable|required_with:close_time_Friday',
            'close_time_Friday' => 'nullable|required_with:open_time_Friday',
            'open_time_Saturday' => 'nullable|required_with:close_time_Saturday',
            'close_time_Saturday' => 'nullable|required_with:open_time_Saturday',
            'open_time_Sunday' => 'nullable|required_with:close_time_Sunday',
            'close_time_Sunday' => 'nullable|required_with:open_time_Sunday',
        ];

        $customValidator = Validator::make(
            $request->all(),
            $customRules
        );

        // Combine errors from both validators
        $errors = array_merge($validator->errors()->toArray(), $customValidator->errors()->toArray());

        if ($validator->fails() || $customValidator->fails()) {
            return response()->json(['status' => false, 'message' => $errors]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $data = $this->location_repo->update($request);

        $data['route'] = route('business.Locations');

        return response()->json($data);
    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Location');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $business_locations = BusinessLocation::Where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$business_locations) {
            return abort(404);
        }

        return view('business.Locations.view_details', [
            'business_locations' =>  $business_locations
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->location_repo->delete($request);

        $data['route'] = route('business.Locations');

        return response()->json($data);
    }
}
