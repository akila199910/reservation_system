<?php

namespace App\Http\Controllers\Business;

use App\Models\CafeTable;
use Illuminate\Http\Request;
use App\Models\TablePreference;
use App\Models\BusinessLocation;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TableLayout;
use App\Models\TableType;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CafeTableRepository;
use App\Repositories\ElementRepository;
use Illuminate\Support\Facades\Validator;


class CafeTableController extends Controller
{
    private $business_id;
    private $cafe_repo;
    private $element_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->cafe_repo = new CafeTableRepository();
        $this->element_repo = new ElementRepository();
    }
    public function index(Request $request)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_CafeTable');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        if ($request->json) {
            // $cafes = CafeTable::all();
            $cafes = CafeTable::with(['preference','element_info'])->Where('business_id', $this->business_id)->get();

            $data = DataTables::of($cafes)
                ->addIndexColumn()
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })
                ->addColumn('preference', function ($item) {
                    return $item->preference ?  $item->preference->preference  : 'N/A';
                })
                ->addColumn('location', function ($item) {
                    return $item->location ?  $item->location->location_name  : 'N/A';
                })
                ->addColumn('reservation_status', function ($item) {
                    if ($item->reservation_status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Not Reserved</span>';
                    }

                    if ($item->reservation_status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Reserved</span>';
                    }
                })
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_url = route('business.cafe.update.form', $item->ref_no);
                    $view_url = route('business.cafe.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'CafeTable', $edit_url, $item->id,  $view_url);

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
                ->addColumn('element_image', function ($item) {

                    $url = "/admin_staff/assets/img/profile.jpg";

                    if ($item->element_info && $item->element_info->normal_image != '') {

                        $url = ($item->element_info->normal_image);
                    }

                    return '<img src="' . $url . '" border="0" width="50" height="50" style="object-fit: cover;" class="stylist-image" align="center" />';
                })
                ->rawColumns(['action', 'status', 'image', 'reservation_status', 'preference', 'location','element_image'])

                ->make(true);

            return $data;
        }

        // Clear existing selected element session
        if (session()->get('selected_element_id')) session()->forget('selected_element_id');

        return view('business.cafeTable.index');
    }

    public function create_form()
    {
        // Clear existing selected element session
        if (session()->get('selected_element_id')) session()->forget('selected_element_id');
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Create_CafeTable');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        $perferences = TablePreference::Where('status', 1)->Where('business_id', $this->business_id)->get();
        $locations = BusinessLocation::Where('status', 1)->Where('business_id', $this->business_id)->get();

        $element_types = TableType::all();
        $first_element_type = TableType::first();
        $elements = TableLayout::where('type_id', $first_element_type->id)->where('status', 1)->get();
        $element_type_id = $first_element_type->id;

        return view('business.cafeTable.create', [
            'perferences' => $perferences,
            'locations' => $locations,
            'element_types' => $element_types,
            'first_element_type' => $first_element_type,
            'elements' => $elements,
            'element_type_id' => $element_type_id
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'location' => 'required',
                'name' => 'required|regex:/^[a-z 0-9 A-Z]+$/u|unique:cafe_tables,name,NULL,id,deleted_at,NULL,business_id,' . $this->business_id . ',location_id,' . $request->location,
                'capacity' => 'nullable|integer',
                'amount' => 'required',
                'perference_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
                'element' => 'required'
            ],
            [
                'element.required' => 'The element is required. Select a element.'
            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'business_id' => $this->business_id
        ]);

        $data = $this->cafe_repo->create($request);

        $data['route'] = route('business.cafe');

        return response()->json($data);
    }



    public function update_form($id)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_CafeTable');

        if ($check_premission == false) {
            return abort(404);
        }
        //End

        $find_cafe = CafeTable::where(['ref_no' => $id, 'business_id' => $this->business_id])->first();

        if (!$find_cafe) {
            return abort(404);
        }

        $all_locations = BusinessLocation::Where('status', 1)->Where('business_id', $this->business_id)->get();

        $perferences = TablePreference::Where('status', 1)->get();

        $element_types = TableType::all();

        $first_element_type = TableType::first();
        $elements = TableLayout::where('type_id', $first_element_type->id)->where('status', 1)->get();
        $element_type_id = $first_element_type->id;

        $selected_element = TableLayout::find($find_cafe->element_id);

        if ($selected_element) {
            $first_element_type = TableType::find($selected_element->type_id);
            $selected_element_id = [$selected_element->id];
            $elements_ids = TableLayout::where('type_id', $first_element_type->id)->where('status', 1)->pluck('id')->toArray();
            $elements_ids = array_merge($elements_ids,$selected_element_id);
            $elements_ids = array_unique($elements_ids);

            $elements = TableLayout::whereIn('id',$elements_ids)->get();
            $element_type_id = $first_element_type->id;
        }

        return view('business.cafeTable.update', [
            'find_cafe' => $find_cafe,
            'perferences' => $perferences,
            'all_locations' => $all_locations,
            'element_types' => $element_types,
            'first_element_type' => $first_element_type,
            'elements' => $elements,
            'element_type_id' => $element_type_id
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;

        $validator = Validator::make(
            $request->all(),
            [
                'capacity' => 'nullable|integer',
                'amount' => 'required',
                'perference_id' => 'required',
                'location' => 'required',
                'name' => 'required|regex:/^[a-z 0-9 A-Z]+$/u|unique:cafe_tables,name,' . $id . ',id,deleted_at,NULL,business_id,' . $this->business_id . ',location_id,' . $request->location,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,svg',
                'element' => 'required'
            ],
            [
                'element.required' => 'The element is required. Select a element.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $data = $this->cafe_repo->update($request);

        $data['route'] = route('business.cafe');

        return response()->json($data);
    }

    public function view_details(Request $request, $ref_no)
    {
        //Check User Permission
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_CafeTable');

        if ($check_premission == false) {
            return abort(404);
        }
        // End

        $cafes = CafeTable::with('preference', 'location')->where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$cafes) {
            return abort(404);
        }

        return view('business.cafeTable.view_details', [
            'cafes' =>  $cafes
        ]);
    }

    public function delete(Request $request)
    {
        $data = $this->cafe_repo->delete($request);

        $data['route'] = route('business.cafe');

        return response()->json($data);
    }

    public function getSection($locationId)
    {
        $selected_locations_sections = TablePreference::where('location_id', $locationId)->Where('status', 1)
            ->Where('business_id', $this->business_id)->pluck('preference', 'id');
        return response()->json($selected_locations_sections);
    }

    public function get_elements(Request $request)
    {
        $elements = TableLayout::where('type_id', $request->element_type_id)->where('status', 1)->get();

        return view('business.cafeTable.layout.create', [
            'elements' => $elements,
            'element_type_id' => $request->element_type_id
        ]);
    }

    public function filter_elements(Request $request)
    {
        $request->merge(['status' => 1]);

        $data = $this->element_repo->element_list($request);

        $data = $data->get()->toArray();

        $status = false;
        if (count($data)) {
            $status = true;
        }

        return response()->json(['status' => $status, 'data' => $data]);
    }

    public function store_element_id(Request $request)
    {
        session()->put('selected_element_id', $request->selected_element_id);

        return response()->json(['status' => true]);
    }

    public function get_stored_element_id(Request $request)
    {
        $element_id = '';
        $status = false;

        if (session()->get('selected_element_id')) {
            $status = true;
            $element_id = session()->get('selected_element_id');
        }

        return response()->json(['status' => $status, 'element_id' => $element_id]);
    }
}
