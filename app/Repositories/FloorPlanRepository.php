<?php

namespace App\Repositories;

use App\Models\FloorPlan;
use App\Models\FloorPlanTable;

class FloorPlanRepository
{
    public function create($request)
    {
        $floor_plan = new FloorPlan();
        $floor_plan->business_id = $request->business_id;
        $floor_plan->section_id = $request->section_id;
        $floor_plan->status = 0;
        $floor_plan->save();

         //Generate Reference Number
         $ref_no = refno_generate(16, 2, $floor_plan->id);
         $floor_plan->ref_no = $ref_no;
         $floor_plan->update();

        if(isset($request->dropped_shape_data) && !empty($request->dropped_shape_data))
        {
            foreach ($request->dropped_shape_data as $key => $value) {
                FloorPlanTable::updateOrCreate(
                    [
                        'plan_id' => $floor_plan->id,
                        'table_id' => $key
                    ],
                    [
                        'table_width' => $value['width'],
                        'table_height' => $value['height'],
                        'table_pos_x' => $value['left'],
                        'table_pos_y' => $value['top'],
                        'created_by' => $request->created_by,
                        'updated_by' => $request->updated_by
                    ]
                );
            }
        }
    }
}
