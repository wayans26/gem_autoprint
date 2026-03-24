<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class exhibitionsController extends Controller
{
    //
    function getExhibitions(Request $req)
    {
        $exhibitions = exhibitions::query()->select('idexhibitions', 'name', 'status', 'is_show')->orderBy('is_show', 'asc');

        return DataTables::of($exhibitions)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = "";
                if ($row->is_show == '1') {
                    $btn .= '<button type="button" id="' . $row->idexhibitions . '" class="btn btn-danger btn-sm btnDisable"><i class="zmdi zmdi-close"></i></button> ';
                } else {
                    $btn .= '<button type="button" id="' . $row->idexhibitions . '" class="btn btn-success btn-sm btnEnable"><i class="zmdi zmdi-check"></i></button> ';
                }
                return $btn;
            })
            ->make(true);
    }

    function changeShowStatus(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'idexhibitions' => 'required',
            'cmd'           => 'required|in:1,0'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibitions = exhibitions::find($req->idexhibitions);
        if (empty($exhibitions)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $exhibitions->update([
            'is_show'   => $req->cmd
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }
}
