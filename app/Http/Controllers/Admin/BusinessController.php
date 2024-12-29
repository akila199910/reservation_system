<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Business;
use App\Models\NotificationSetting;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\BusinessRepository;
use Illuminate\Validation\Rule;

class BusinessController extends Controller
{
    private $business_repo;

    function __construct()
    {
        $this->business_repo = new BusinessRepository();
    }

    public function index(Request $request)
    {
        if ($request->json) {
            $business = Business::get();

            $data =  Datatables::of($business)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                return '<span align="center" style="cursor: pointer; font-weight: bold;" onclick="goToDashboard(' . $item->id . ')">' . $item->name . '</span>';
                // return '<img src="' . $url . '" border="0" width="50" class="stylist-image" align="center" style="cursor: pointer;" onclick="goToDashboard('.$item->id.')" />';
            })
            ->addColumn('status', function ($item) {
                // if ($item->status == 0) {
                //     return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                // }

                // if ($item->status == 1) {
                //     return '<span class="badge badge-soft-success badge-border">Active</span>';
                // }

                if ($item->status == 0) {
                    return '<div class="dropdown">
                                <button class="btn btn-sm badge badge-soft-danger badge-border dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Inactive</button>
                                <ul class="dropdown-menu dropdown-menu" >
                                    <li class="dropdown-item badge badge-soft-success" onclick="update_status(' . $item->id . ',1)">Active</li>
                                </ul>
                        </div>';
                }

                if ($item->status == 1) {
                    return '<div class="dropdown">
                        <button class="btn btn-sm badge badge-soft-success badge-border dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Active</button>
                        <ul class="dropdown-menu dropdown-menu">
                            <li class="dropdown-item  badge badge-soft-danger" onclick="update_status(' . $item->id . ',0)">Inactive</li>
                        </ul>
                    </div>';
                }

            })
            ->addColumn('ibson_business', function ($item) {
                if ($item->ibson_business == 0) {
                    return '<span class="badge badge-soft-danger badge-border">No</span>';
                }

                if ($item->ibson_business == 1) {
                    return '<span class="badge badge-soft-success badge-border">Yes</span>';
                }
            })
            ->addColumn('action', function ($item) {
                $user = Auth::user();
                $edit_url = route('admin.business.update.form', $item->ref_no);
                $view_url = route('admin.business.view_details', $item->ref_no);

                $actions = '';
                $actions .= action_buttons($actions, $edit_url, $item->id, $view_url);

                $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        $dashboard = '<button type="button" class="dropdown-item" title="Go To Dashboard" onclick="goToDashboard(' . $item->id . ')"><i class="fa-solid fa-gauge m-r-5"></i>Dashboard</button>  ';

                    '</div></div>';

                return $action;
            })
            ->rawColumns(['action','status','ibson_business','name'])
            ->make(true);

        return $data;
        }

        return view('admin.business.index');
    }

    public function create_form()
    {
        return view('admin.business.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|regex:/^[a-zA-Z0-9\s,\/_\-\'".\$]+$/u',
                'email' => 'required|email:rfc,dns|max:190|unique:businesses,email,NULL,id,deleted_at,NULL',
                'contact' => 'required|digits:10|unique:businesses,contact,NULL,id,deleted_at,NULL',
                'address' => 'required|regex:/^[a-zA-Z0-9\s,.\-\/\'#]+$/u',
                'ibson_id' => 'nullable|unique:businesses',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $data = $this->business_repo->create_business($request);

        $data['status'] = true;
        $data['message'] = 'New Business Created Successfully!';
        $data['route'] = route('admin.business');

        return response()->json($data);
    }

    public function update_form($id)
    {
        $business = Business::where(['ref_no' => $id])->first();

        if (!$business) {
            return abort(404);
        }

        return view('admin.business.update',[
            'business' => $business
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $business = Business::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|regex:/^[a-zA-Z0-9\s,\/_\-\'".\$]+$/u',
                'email' => 'required|email:rfc,dns|max:190|unique:businesses,email,' . $id . ',id,deleted_at,NULL',
                'contact' => 'required|digits:10|unique:businesses,contact,' . $id . ',id,deleted_at,NULL',
                'address' => 'required|regex:/^[a-zA-Z0-9\s,.\-\/\'#]+$/u',
                'ibson_id' => [
                    'nullable',
                    Rule::unique('businesses')->ignore($id)->whereNull('deleted_at')
                ],
            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $data = $this->business_repo->update_business($request);

        $data['status'] = true;
        $data['message'] = 'Selected Business Updated Successfully!';
        $data['route'] = route('admin.business');

        return response()->json($data);
    }

    public function view_details(Request $request, $ref_no)
    {

        $business = Business::where(['ref_no' => $ref_no])->first();

        if (!$business) {
            return abort(404);
        }

        return view('admin.business.view_details', [
            'business' =>  $business
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->business_repo->delete_business($request);

        $data['route'] = route('admin.business');

        return response()->json($data);
    }

    public function move_dashboard(Request $request)
    {
        $id = $request->id;
        $business = Business::find($id);

        session()->put('_business_id', $id);
        session()->put('business', $business);

        return response()->json(['status'=>true, 'message'=>'Business added to session','business'=>$business]);
    }


    public function update_status(Request $request)
    {
        if ($request->ajax()) {

            $business_id = $request->business_id;
            $business = Business::find($business_id);

            $business->status = $request->status;
            $business->update();

            return response()->json(['status'=>true]);
        }
    }
}
