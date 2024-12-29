<?php

namespace App\Repositories;

use App\Models\TableType;

class TabletypeRepository
{
    public function table_type_list($request)
    {
        $data = TableType::with(['created_by_user','updated_by_user']);

        return $data;
    }

    public function create($request)
    {
        $table_type = new TableType();
        $table_type->type_name = $request->table_type;
        $table_type->created_by = $request->created_by;
        $table_type->updated_by = $request->created_by;
        $table_type->save();

        return;
    }

    public function update($request)
    {
        $table_type = TableType::find($request->id);
        $table_type->type_name = $request->table_type;
        $table_type->updated_by = $request->updated_by;
        $table_type->update();

        return;
    }

    public function delete($request)
    {
        $type =  TableType::find($request->id);
        if ($type) {
            $type->elements_list()->delete();
            
            TableType::destroy($request->id);
        }

        return;
    }
}
