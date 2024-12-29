<?php

namespace App\Http\Controllers\Admin;

use App\Models\TableType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\TableLayout;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ElementRepository;
use Illuminate\Support\Facades\Validator;

class ElementsController extends Controller
{
    private $element_repo;

    function __construct()
    {
        $this->element_repo = new ElementRepository();
    }

    public function index(Request $request)
    {
        if ($request->json) {
            $lists = $this->element_repo->element_list($request);

            return $this->element_lists($lists);
        }

        return view('admin.elements.index');
    }

    public function element_lists($lists)
    {
        $data =  Datatables::of($lists)
            ->addIndexColumn()
            ->editColumn('type_id', function ($item) {
                $type_id = 'N/A';
                if (isset($item->element_type)) $type_id = $item->element_type->type_name;

                return $type_id;
            })
            ->editColumn('created_by', function ($item) {
                $created_by = 'N/A';
                if (isset($item->created_by_user)) $created_by = $item->created_by_user->name;

                return $created_by;
            })
            ->editColumn('updated_by', function ($item) {
                $updated_by = 'N/A';
                if (isset($item->updated_by_user)) $updated_by = $item->updated_by_user->name;

                return $updated_by;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge badge-soft-danger badge-border">Inactive</span>';
                }

                if ($item->status == 1) {
                    return '<span class="badge badge-soft-success badge-border">Active</span>';
                }
            })
            ->addColumn('normal_image', function ($item) {
                $img_url = $item->normal_image;
                return '<img src="'.$img_url.'" height="45px">';
            })
            ->addColumn('checkedin_image', function ($item) {
                $img_url =  $item->checkedin_image;
                return '<img src="'.$img_url.'" height="45px">';
            })
            ->addColumn('action', function ($item) {
                $user = Auth::user();
                $edit_url = route('admin.elements.update.form', $item->ref_no);
                $view_url = '';

                $actions = '';
                $actions .= action_buttons($actions, $edit_url, $item->id, $view_url);

                $action = '<div class="dropdown dropdown-action">
                    <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                <div class="dropdown-menu dropdown-menu-end">'
                    . $actions .
                    '</div></div>';

                return $action;
            })
            ->rawColumns(['action', 'created_by', 'updated_by', 'type_id', 'status', 'normal_image', 'checkedin_image'])
            ->make(true);

        return $data;
    }

    public function create_form()
    {
        $element_types = TableType::all();

        return view('admin.elements.create',[
            'element_types' => $element_types
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'element_type' => 'required',
                'element_name' => 'required|regex:/^[a-z0-9A-Z -_]+$/u|max:190|unique:table_layouts,layout_name,NULL,id,deleted_at,NULL,type_id,'.$request->element_type,
                'normal_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
                'checkedin_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'created_by' => Auth::user()->id
        ]);

        $data = $this->element_repo->create($request);

        $data['route'] = route('admin.elements');

        return response()->json($data);
    }

    public function update_form(Request $request, $ref_no)
    {
        $element_types = TableType::all();

        $element = TableLayout::where('ref_no',$ref_no)->first();

        if (!$element) {
            return abort(404, 'Element Not Found');
        }

        return view('admin.elements.update',[
            'element_types' => $element_types,
            'element' => $element
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make(
            $request->all(),
            [
                'element_type' => 'required',
                'element_name' => 'required|regex:/^[a-z0-9A-Z -_]+$/u|max:190|unique:table_layouts,layout_name,'.$id.',id,deleted_at,NULL,type_id,'.$request->element_type,
                'normal_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
                'checkedin_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'updated_by' => Auth::user()->id
        ]);

        $element = TableLayout::find($id);

        $data = $this->element_repo->update($request,$element);

        $data['route'] = route('admin.elements');

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $data = $this->element_repo->delete($request);

        return response()->json($data);
    }
}
