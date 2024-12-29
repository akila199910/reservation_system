<?php

namespace App\Http\Controllers\Business;

use App\Models\CafeTable;
use App\Models\FloorPlan;
use App\Models\TableLayout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FloorReservationController extends Controller
{
    //
    private $business_id;


    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->business_id = session()->get('_business_id');
            return $next($request);
        });


    }

    public function index(Request $request)
    {

        $floor_plan = FloorPlan::with(['preference_info','tables'])->where([ 'business_id' => $this->business_id])->first();

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


        return view('business.reservation_floorplan.index',[
            'tables' => $tables,
            'floor_plan' => $floor_plan
        ]);

    }
}
