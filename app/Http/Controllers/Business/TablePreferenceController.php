<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Models\TablePreference;
use App\Models\BusinessLocation;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\TablePreferenceRepository;


class TablePreferenceController extends Controller
{
    private $business_id;
    private $tablepreference_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->tablepreference_repo = new TablePreferenceRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Preference');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        if ($request->json) {
            $preference = TablePreference::with(['location'])->Where('business_id', $this->business_id)->get();

            $data = DataTables::of($preference)
                ->addIndexColumn()
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })
                ->addColumn('pre_name', function ($item) {

                    if ($item->is_default == 1) {
                        return $item->preference . ' <i class="fas fa-check-circle text-primary"></i>';
                    } else {
                        return $item->preference;
                    }
                })
                ->addColumn('location_name', function ($item) {
                    $name = '';

                    if (isset($item->location)) {
                        $name = $item->location->location_name;
                    }

                    return $name;
                })
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_url =  route('business.preference.update.form', $item->ref_no);
                    $view_url = route('business.preference.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'Preference', $edit_url, $item->id, $view_url);

                    $action = '<div class="dropdown dropdown-action">
                    <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                    . $actions .
                    '</div></div>';

                return $action;
                })
                ->addColumn('image', function ($item) {
                    $url = config('aws_url.url') . ($item->image);

                    if ($item->image == '') {
                        $url = "/admin_staff/assets/img/profile.jpg";
                    }
                    return '<img src="' . $url . '" border="0" width="50" height="50" style="border-radius:50%;object-fit: cover;" class="stylist-image" align="center" />';
                })
                ->rawColumns(['action', 'status', 'image', 'location_name', 'pre_name'])

                ->make(true);

            return $data;
        }

        return view('business.TablePreference.index');
    }

    public function create_form()
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Preference');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        $locations = BusinessLocation::Where('status', 1)->Where('business_id', $this->business_id)->get();

        return view('business.TablePreference.create', ['locations' => $locations]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
                'location' => 'required',
                'preference' => 'required|unique:table_preferences,preference,NULL,id,deleted_at,NULL,business_id,'.$this->business_id.',location_id,'.$request->location,
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge(['business_id' => $this->business_id]);

        $data = $this->tablepreference_repo->create($request);

        $data['route'] = route('business.preference');

        return response()->json($data);
    }

    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Preference');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        $find_preference = TablePreference::where(['ref_no' => $id, 'business_id' => $this->business_id])->first();

        if (!$find_preference) {
            return abort(404);
        }

        $all_locations = BusinessLocation::Where('status', 1)->Where('business_id', $this->business_id)->get();

        return view('business.TablePreference.update', [
            'find_preference' => $find_preference,
            'all_locations' => $all_locations
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make(
            $request->all(),
            [
                'nullable|image|mimes:jpeg,png,jpg,svg',
                'location' => 'required',
                'preference' => 'required|unique:table_preferences,preference,'.$id.',id,deleted_at,NULL,business_id,'.$this->business_id.',location_id,'.$request->location,
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }
        $data = $this->tablepreference_repo->update($request);

        $data['route'] = route('business.preference');

        return response()->json($data);
    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Preference');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $preference = TablePreference::with(['location'])->Where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();


        if (!$preference) {
            return abort(404);
        }

        return view('business.TablePreference.view_details', [
            'preference' =>  $preference
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->tablepreference_repo->delete($request);

        $data['route'] = route('business.preference');

        return response()->json($data);
    }
}
