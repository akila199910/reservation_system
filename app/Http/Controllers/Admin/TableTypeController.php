<?php

namespace App\Http\Controllers\Admin;

use App\Models\TableType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TabletypeRepository;
use Illuminate\Support\Facades\Validator;

class TableTypeController extends Controller
{
    private $table_type_repo;

    function __construct()
    {
        $this->table_type_repo = new TabletypeRepository();
    }

    public function index(Request $request)
    {
        if ($request->json) {
            $list = $this->table_type_repo->table_type_list($request);

            $data = $this->table_type_lists($list);

            return $data;
        }
        return view('admin.table_type.index');
    }

    public function table_type_lists($list)
    {
        $data =  Datatables::of($list)
            ->addIndexColumn()
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
            ->addColumn('action', function ($item) {
                $user = Auth::user();
                $edit_url = 'True';
                $view_url = '';

                $actions = '';
                $actions .= action_buttons_modals($actions, $edit_url, $item->id, $view_url);

                $action = '<div class="dropdown dropdown-action">
                    <a href="javascript:;" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                <div class="dropdown-menu dropdown-menu-end">'
                    . $actions .
                    '</div></div>';

                return $action;
            })
            ->rawColumns(['action', 'created_by', 'updated_by'])
            ->make(true);

        return $data;
    }

    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'table_type' => 'required|max:190|unique:table_types,type_name,NULL,id,deleted_at,NULL',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'created_by' => Auth::user()->id
        ]);

        $this->table_type_repo->create($request);

        return response()->json(['status'=>true, 'message' => 'New Table Type Created Successfuly!']);
    }

    public function update_view(Request $request)
    {
        $table_type = TableType::find($request->id);

        return view('admin.table_type.update',[
            'table_type' => $table_type
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make(
            $request->all(),
            [
                'table_type' => 'required|max:190|unique:table_types,type_name,'.$id.',id,deleted_at,NULL',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false,  'message' => $validator->errors()]);
        }

        $request->merge([
            'updated_by' => Auth::user()->id
        ]);

        $this->table_type_repo->update($request);

        return view('admin.table_type.create');
    }

    public function delete(Request $request)
    {
        $this->table_type_repo->delete($request);

        return response()->json(['status'=>true, 'message' => 'Selected Table Type Deleted Successfuly!']);
    }
}
