<?php

namespace App\Repositories;

use App\Models\TableLayout;

class ElementRepository
{

    public function element_list($request)
    {
        $elements = TableLayout::with(['element_type','created_by_user','updated_by_user']);

            if(isset($request->element_type) && !empty($request->element_type))
                $elements = $elements->where('type_id',$request->element_type);

            if(isset($request->element_name) && !empty($request->element_name))
                $elements = $elements->where('layout_name','LIKE','%'.$request->element_name.'%');

            if(isset($request->status))
                $elements = $elements->where('status',$request->status);

        return $elements;
    }

    public function create($request)
    {
        $normal_image = '';
        if (isset($request->normal_image) && $request->normal_image->getClientOriginalName()) {
            $normal_image = file_upload($request->normal_image, 'elements');
        }

        $checkedin_image = '';
        if (isset($request->checkedin_image) && $request->checkedin_image->getClientOriginalName()) {
            $checkedin_image = file_upload($request->checkedin_image, 'elements');
        }

        $element = new TableLayout();
        $element->layout_name = $request->element_name;
        $element->type_id = $request->element_type;
        $element->normal_image = $normal_image;
        $element->checkedin_image = $checkedin_image;
        $element->status = $request->status == true ? 1 : 0;
        $element->created_by = $request->created_by;
        $element->updated_by = $request->created_by;
        $element->save();

        //Generate Reference Number
        $ref_no = refno_generate(16, 2, $element->id);
        $element->ref_no = $ref_no;
        $element->update();

        $data = $this->element_info($element);

        return [
            'status' => true,
            'message' => 'New Element Created Successfully!',
            'data' => $data
        ];
    }

    public function element_info($element)
    {
        $data =  [
            'id' => $element->id,
            'ref_no' => $element->ref_no,
            'element_name' => $element->layout_name,
            'element_type' => $element->element_type->type_name,
            'element_type_id' => $element->type_id,
            'status' => $element->status,
            'status_name' => $element->status == 1 ? 'Active' : 'Inactive',
            'normal_image' => config('aws_url.url').$element->normal_image,
            'checkedin_image' => config('aws_url.url').$element->checkedin_image

        ];

        return $data;
    }

    public function update($request, $element)
    {
        $normal_image = $element->normal_image;
        if (isset($request->normal_image) && $request->normal_image->getClientOriginalName()) {
            $normal_image = file_upload($request->normal_image, 'elements');
        }

        $checkedin_image = $element->checkedin_image;
        if (isset($request->checkedin_image) && $request->checkedin_image->getClientOriginalName()) {
            $checkedin_image = file_upload($request->checkedin_image, 'elements');
        }

        $element->layout_name = $request->element_name;
        $element->type_id = $request->element_type;
        $element->normal_image = $normal_image;
        $element->checkedin_image = $checkedin_image;
        $element->status = $request->status == true ? 1 : 0;
        $element->updated_by = $request->updated_by;
        $element->update();

        $data = $this->element_info($element);

        return [
            'status' => true,
            'message' => 'Selected Element Updated Successfully!',
            'data' => $data
        ];
    }

    public function delete($request)
    {
        $element = TableLayout::find($request->id);

        $element->delete();

        return [
            'status' => true,
            'message' => 'Selected Element Deleted Successfully!'
        ];
    }
}
