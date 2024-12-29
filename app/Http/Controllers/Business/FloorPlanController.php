<?php

namespace App\Http\Controllers\Business;

use App\Models\CafeTable;
use App\Models\FloorPlan;
use App\Models\TableLayout;
use Illuminate\Http\Request;
use App\Models\TablePreference;
use App\Http\Controllers\Controller;
use App\Repositories\FloorPlanRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FloorPlanController extends Controller
{
    private $business_id;
    private $floor_plan_repo;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });

        $this->floor_plan_repo = new FloorPlanRepository();
    }

    public function index(Request $request)
    {
        if ($request->json) {

            $floor_plan_sections = FloorPlan::with(['preference_info'])->where('business_id', $this->business_id)->get();

            $data = DataTables::of($floor_plan_sections)
                ->addIndexColumn()
                ->addColumn('section', function ($item) {
                    return $item->preference_info->preference . ' (Location - ' . $item->preference_info->location->location_name . ')';

                })
                ->addColumn('status', function ($item) {
                    if ($item->status == 0) {
                        return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                    }

                    if ($item->status == 1) {
                        return '<span class="badge badge-soft-success badge-border">Active</span>';
                    }
                })
                ->addColumn('action', function ($item) {
                    $user = Auth::user();
                    $edit_url = route('business.floor_plan.update.form', $item->ref_no);
                    $view_url = route('business.floor_plan.view_details', $item->ref_no);

                    $actions = '';
                    $actions .= action_btns($actions, $user, 'Floor_Plan', $edit_url, $item->id, $view_url);

                    $action = '<div class="dropdown dropdown-action">
                        <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    <div class="dropdown-menu dropdown-menu-end">'
                        . $actions .
                        '</div></div>';

                    return $action;
                })
                ->rawColumns(['action', 'status', 'section'])
                ->make(true);

            return $data;
        }

        return view('business.floor_plan.index');
    }
    public function create_form(Request $request)
    {
        $floor_plan_sections = FloorPlan::where('business_id', $this->business_id)->pluck('section_id')->toArray();

        $sections = TablePreference::with(['location'])->where('business_id', $this->business_id)->where('status', 1);
        if (!empty($floor_plan_sections))
            $sections = $sections->whereNotIn('id', $floor_plan_sections);

        $sections = $sections->get();

        return view('business.floor_plan.create', [
            'sections' => $sections
        ]);
    }

    public function get_floor_layout(Request $request)
    {
        $elements_id = CafeTable::where('perference_id', $request->section_id)->pluck('element_id')->toArray();
        $table_ids = CafeTable::where('perference_id', $request->section_id)->where('status',1)->pluck('id')->toArray();

        // Remove null values
        $filteredArray = array_filter($elements_id, function ($value) {
            return $value !== null;
        });

        $elements_id = array_unique($elements_id);

        $elements_ids = TableLayout::whereIn('id',$elements_id)->pluck('id')->toArray();

        $tables = CafeTable::whereIn('id', $table_ids)->whereIn('element_id',$elements_ids)->get();

        return view('business.floor_plan.content.create',[
            'tables' => $tables
        ]);

    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'section_id' => 'required|unique:floor_plans,section_id,NULL,id,deleted_at,NULL,business_id,'.$this->business_id,
                'dropped_shape_data' => 'required',
            ],
            [
                'dropped_shape_data.required' => 'Drop atleast one element'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'business_id' => $this->business_id,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ]);

        $data = $this->floor_plan_repo->create($request);

        $data['route'] = route('business.floor_plan');

        return response()->json(['status' => true,  'message' => 'New Floor Plan Created Successfully!', 'route' => route('business.floor_plan')]);
    }

    public function view_details(Request $request, $ref_no)
    {

        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Read_Floor_Plan');

        if ($check_premission == false) {
            return abort(404);
        }

        $floor_plan = FloorPlan::with(['preference_info','tables'])->where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$floor_plan ) {
            return abort(404);
        }


        $exist_table_ids = $floor_plan->tables->pluck('table_id')->toArray();

        $elements_id = CafeTable::where('perference_id', $floor_plan->section_id)->pluck('element_id')->toArray();
        $table_ids = CafeTable::where('perference_id', $floor_plan->section_id)->whereNotIn('id',$exist_table_ids)->where('status',1)->pluck('id')->toArray();

        // Remove null values
        $filteredArray = array_filter($elements_id, function ($value) {
            return $value !== null;
        });

        $elements_id = array_unique($elements_id);

        $elements_ids = TableLayout::whereIn('id',$elements_id)->pluck('id')->toArray();

        $tables = CafeTable::whereIn('id', $table_ids)->whereIn('element_id',$elements_ids)->get();

        return view('business.floor_plan.view_details', [
            'floor_plan' =>  $floor_plan,
            'tables' => $tables
        ]);
    }

    public function update_form(Request $request, $ref_no)
    {
        $user = Auth::user();
        $check_premission = user_permission_check($user, 'Update_Floor_Plan');

        if ($check_premission == false) {
            return abort(404);
        }

        $floor_plan = FloorPlan::with(['preference_info','tables'])->where(['ref_no' => $ref_no, 'business_id' => $this->business_id])->first();

        if (!$floor_plan ) {
            return abort(404);
        }

        $exist_table_ids = $floor_plan->tables->pluck('table_id')->toArray();

        $elements_id = CafeTable::where('perference_id', $floor_plan->section_id)->pluck('element_id')->toArray();
        $table_ids = CafeTable::where('perference_id', $floor_plan->section_id)->whereNotIn('id',$exist_table_ids)->where('status',1)->pluck('id')->toArray();

        // Remove null values
        $filteredArray = array_filter($elements_id, function ($value) {
            return $value !== null;
        });

        $elements_id = array_unique($elements_id);

        $elements_ids = TableLayout::whereIn('id',$elements_id)->pluck('id')->toArray();

        $tables = CafeTable::whereIn('id', $table_ids)->whereIn('element_id',$elements_ids)->get();

        return view('business.floor_plan.update',[
            'tables' => $tables,
            'floor_plan' => $floor_plan
        ]);
    }
}
