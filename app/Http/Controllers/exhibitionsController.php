<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use App\Models\user;
use App\Models\user_has_exhibitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class exhibitionsController extends Controller
{
    //
    function getExhibitions(Request $req)
    {
        $exhibitions = user::query()->select('username', 'id');

        return DataTables::of($exhibitions)
            ->filterColumn('username', function ($query, $keyword) {
                $query->where('username', 'like', $keyword . '%');
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" id="' . $row->id . '" class="btn btn-primary btn-sm btnAdd"><i class="zmdi zmdi-plus"> Sub</i></button> ';
                return $btn;
            })
            ->addColumn('exhibitions', function ($row) {
                $user_exhibitions = user_has_exhibitions::where('user_id', $row->id)->select('id', 'exhibitions_name')->get();

                $btn = "";
                foreach ($user_exhibitions as $key => $value) {
                    $btn .= '<a href="javascript:void(0)" class="btnExhibitions" id="' . $value->id . '"><span class="badge badge-pill badge-primary m-1">' . $value->exhibitions_name . '</span></a>';
                }
                return $btn;
            })
            ->rawColumns(['action', 'exhibitions'])
            ->make(true);
    }

    function getListExhibitions(Request $req)
    {
        $exhibitions = exhibitions::where('status', 1)->select('idexhibitions', 'name')->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibitions);
    }

    function assignExhibitionsToUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'idexhibitions' => 'required',
            'iduser'        => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibitions = exhibitions::find($req->idexhibitions);
        if (empty($exhibitions)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        $user = user::find($req->iduser);
        if (empty($user)) {
            return responseMessage::responseMessage(0, "User not found", 200);
        }

        user_has_exhibitions::create([
            'exhibition_id'     => $req->idexhibitions,
            'user_id'           => $req->iduser,
            'exhibitions_name'  => $exhibitions->name
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }
    function deleteAssignExhibitionsToUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id' => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = user_has_exhibitions::find($req->id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $exhibition->delete();

        return responseMessage::responseMessage(1, "Success", 200);
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
