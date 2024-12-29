<?php

namespace App\Http\Controllers\Business;

use App\Models\IntakeForm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\IntakeFormRepository;


class IntakeFormController extends Controller
{
    private $business_id;
    private $intake_repo;

    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->intake_repo = new IntakeFormRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_intake');

        if ($check_premission == false) {
            return abort(404);
        }
        // Check if the request expects JSON data for DataTables
        if ($request->json) {
            // Query the IntakeForm table, selecting necessary columns
            $intakes = IntakeForm::select('id', 'ref_no', 'f_name', 'address', 'email', 'contact', 'appointment_date', 'appointment_time', 'communication_mode')
                ->where('business_id', $this->business_id);

            // Process data for DataTables
            $data = DataTables::of($intakes)
                ->addIndexColumn()

                // Add gender column with badges
                ->addColumn('gender', function ($item) {
                    if ($item->gender == 'M') {
                        return '<span class="badge badge-soft-danger badge-border">Male</span>';
                    }
                    if ($item->gender == 'F') {
                        return '<span class="badge badge-soft-success badge-border">Female</span>';
                    }
                    if ($item->gender == 'O') {
                        return '<span class="badge badge-soft-info badge-border">Other</span>';
                    }
                    return '<span class="badge badge-soft-warning badge-border">Unknown</span>'; // Handle missing gender
                })

                // Add communication_mode column with badges
                ->addColumn('communication_mode', function ($item) {
                    switch ($item->communication_mode) {
                        case 1:
                            return '<span class="badge badge-soft-success badge-border">Email</span>';
                        case 2:
                            return '<span class="badge badge-soft-primary badge-border">Phone</span>';
                        case 3:
                            return '<span class="badge badge-soft-info badge-border">SMS</span>';
                        case 4:
                            return '<span class="badge badge-soft-warning badge-border">Physical</span>';
                        default:
                            return '<span class="badge badge-soft-secondary badge-border">N/A</span>';
                    }
                })

                // Add action buttons (Edit and View)
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_route = route('business.IntakeForm.update.form', $item->ref_no);
                    $view_url = route('business.IntakeForm.view_details', $item->ref_no);
                    $actions = '';
                    $actions = action_btns($actions, $user, 'intake', $edit_route, $item->id, $view_url);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        '</div></div>';

                    return $action;
                })

                // Render columns as raw HTML
                ->rawColumns(['action', 'gender', 'communication_mode'])
                ->make(true);

            return $data;
        }

        // Return the IntakeForm index view
        return view('business.IntakeForm.index');
    }

    public function create_form()
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_intake');

        if ($check_premission == false) {
            return abort(404);
        }
        return view('business.IntakeForm.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'f_name' => 'required|string|max:255',
                'l_name' => 'required|string|max:255',
                'email' => 'required|email:rfc,dns|max:190',
                'contact' => 'required|digits:10',
                'address' => 'required|regex:/^[a-zA-Z0-9\s,.\-\/\'#]+$/u',
                'dob' => 'required|date',
                'gender' => 'required|string',
                'reason' => 'nullable|string',
                'description' => 'nullable|string',
                'appointment_time' => 'required',
                'appointment_date' => 'required|date',
                'communication_mode' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $business_id = $this->business_id;

        $request->merge([
            'business_id' => $business_id,
        ]);
        $data = $this->intake_repo->create_intakeForm($request);

        $data['status'] = true;
        $data['message'] = 'New Intake Form Created Successfully!';
        $data['route'] = route('business.IntakeForm.index');

        return response()->json($data);
    }

    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_intake');

        if ($check_premission == false) {
            return abort(404);
        }

        $intake = IntakeForm::where(['ref_no' => $id])->first();

        if (!$intake) {
            return abort(404);
        }
        return view('business.IntakeForm.update', [
            'intake' => $intake
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'f_name' => 'required|string|max:255',
                'l_name' => 'required|string|max:255',
                'email' => 'required|email:rfc,dns|max:190',
                'contact' => 'required|digits:10',
                'address' => 'required|string',
                'dob' => 'required|date',
                'gender' => 'required|string',
                'reason' => 'nullable|string',
                'description' => 'nullable|string',
                'appointment_time' => 'required',
                'appointment_date' => 'required|date',
                'communication_mode' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $data = $this->intake_repo->update_intakeForm($request);

        $data['route'] = route('business.IntakeForm.index');

        return response()->json($data);
    }
    
    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_intake');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $intakes = IntakeForm::Where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$intakes) {
            return abort(404);
        }

        return view('business.IntakeForm.view_details', [
            'intakes' =>  $intakes
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->intake_repo->delete_intakeForm($request);

        $data['route'] = route('business.IntakeForm.index');

        return response()->json($data);
    }
}
