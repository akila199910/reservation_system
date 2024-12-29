<?php

namespace App\Http\Controllers\Business;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ClientsRepository;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class ClientController extends Controller
{
    private $business_id;
    private $client_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->client_repo = new ClientsRepository();
    }

    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Client');

        if ($check_premission == false) {
            return abort(404);
        }

        // $clients = Client::where('business_id', $this->business_id)->get();

        if ($request->json) {
            $clients = Client::where('business_id', $this->business_id)->get();
            $data =  Datatables::of($clients)
                ->addIndexColumn()
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-borders">Active</span>';
                    }
                })

                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_route = route('business.clients.update.form', $item->ref_no);
                    $view_url = route('business.clients.view_details', $item->ref_no);
                    $actions = '';
                    $actions = action_btns($actions, $user, 'Client', $edit_route, $item->id , $view_url);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                    '</div></div>';

                    return $action;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);

            return $data;
        }

        return view('business.clients.index');
    }

    public function create_form()
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_Client');

        if ($check_premission == false) {
            return abort(404);
        }

        return view('business.clients.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|regex:/^[a-z A-Z]+$/u|max:190',
                'last_name' => 'required|regex:/^[a-z A-Z]+$/u|max:190',
                // 'email' => 'required|email:rfc,dns|max:190|unique:clients,email,NULL,id,deleted_at,NULL,business_id,'.$this->business_id,
                'email' => 'required|email:rfc|max:190|unique:clients,email,NULL,id,deleted_at,NULL,business_id,'.$this->business_id,
                'contact' => 'required|digits:10|unique:clients,contact,NULL,id,deleted_at,NULL,business_id,'.$this->business_id,
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $business_id = $this->business_id;

        $request->merge([
            'business_id' => $business_id,
        ]);
        $data = $this->client_repo->create($request);

        $data['status'] = true;
        $data['message'] = 'New Client Created Successfully!';
        $data['route'] = route('business.clients');

        return response()->json($data);
    }

    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Client');

        if ($check_premission == false) {
            return abort(404);
        }

        $client = Client::where(['ref_no' => $id])->first();

        if (!$client) {
            return abort(404);
        }

        return view('business.clients.update',[
            'client' => $client
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|regex:/^[a-z A-Z]+$/u|max:190',
                'last_name' => 'required|regex:/^[a-z A-Z]+$/u|max:190',
                'email' => 'required|email:rfc,dns|max:190|unique:clients,email,'.$id.',id,deleted_at,NULL,business_id,'.$this->business_id,
                'contact' => 'required|digits:10|unique:clients,contact,'.$id.',id,deleted_at,NULL,business_id,'.$this->business_id,
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $data = $this->client_repo->update($request);

        $data['route'] = route('business.clients');

        return response()->json($data);
    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Client');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $client = Client::where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$client) {
            return abort(404);
        }

        return view('business.clients.view_details', [
            'client' =>  $client
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->client_repo->delete($request);

        $data['route'] = route('business.clients');

        return response()->json($data);
    }
}

