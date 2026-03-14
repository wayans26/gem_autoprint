<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\menu_group;
use App\Models\menu_sub_group;
use App\Models\user_has_menu_sub_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DB;

class menuGroupController extends Controller
{
    //
    function getMenuGroup(Request $req)
    {
        $menuGroup = menu_group::orderBy('order_no', 'asc')->get();

        return DataTables::of($menuGroup)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" id="' . $row->id . '" class="btn btn-info btn-sm btnEdit"><i class="zmdi zmdi-edit"></i></button> ';
                $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-danger btn-sm btnDelete"><i class="fa fa-trash"></i></button> ';

                return $btn;
            })
            ->addColumn('menuName', function ($row) {
                return "<i class='$row->icon'></i> $row->name";
            })
            ->rawColumns(['action', 'menuName'])
            ->make(true);
    }

    function addMenuGroup(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'name'      => 'required',
            'order_no'  => 'required|integer',
            'icon'      => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $menuId = makeid::createId(16);
        $menuGroup = menu_group::create([
            'id'        => $menuId,
            'name'      => $req->name,
            'order_no'  => $req->order_no,
            'icon'      => $req->icon
        ]);

        activity("Menu Group")->withProperties([
            'subject'   => [
                'type'  => menu_group::class,
                'id'    => (string) $menuId
            ],
            'causeBy'   => [
                'id'        => $req->users->id,
                'username'  => $req->users->username,
                'full_name' => $req->users->full_name
            ],
        ])->log('Add Menu Group');

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function getMenuGroupById(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id'    => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }
        $menuGroup = menu_group::find($req->id);


        if (empty($menuGroup)) {
            return responseMessage::responseMessage(0, "Menu Group Not Found", 200);
        }

        return responseMessage::responseMessageWithData(1, "Success", 200, $menuGroup);
    }

    function editMenuGroup(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id'        => 'required',
            'name'      => 'required',
            'order_no'  => 'required|integer',
            'icon'      => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $menuGroup = menu_group::find($req->id);

        if (empty($menuGroup)) {
            return responseMessage::responseMessage(0, "Menu Group Not Found", 200);
        }

        activity("Menu Group")->withProperties([
            'subject'   => [
                'type'  => menu_group::class,
                'id'    => (string) $menuGroup->getKey()
            ],
            'causeBy'   => [
                'id'        => $req->users->id,
                'username'  => $req->users->username,
                'full_name' => $req->users->full_name
            ],
            'edit'  => [
                'old'   => [
                    'name'      => $menuGroup->name,
                    'order_no'  => $menuGroup->order_no,
                    'icon'      => $menuGroup->icon
                ],
                'new'   => [
                    'name'      => $req->name,
                    'order_no'  => $req->order_no,
                    'icon'      => $req->icon
                ]
            ]
        ])->log('Update Menu Group');

        $menuGroup->update([
            'name'      => $req->name,
            'order_no'  => $req->order_no,
            'icon'      => $req->icon
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function deleteMenuGroup(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id'    => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $menuGroup = menu_group::find($req->id);

        if (empty($menuGroup)) {
            return responseMessage::responseMessage(0, "Menu Group Not Found", 200);
        }

        try {
            DB::transaction(function () use ($req, $menuGroup) {
                $subMenu = menu_sub_group::where('menu_group_id', $menuGroup->id)->get();
                foreach ($subMenu as $key => $value) {
                    user_has_menu_sub_group::where('menu_sub_group_id', $value->id)->delete();
                    $value->delete();
                }
                activity("Menu Group")->withProperties([
                    'subject'   => [
                        'type'  => menu_group::class,
                        'id'    => (string) $menuGroup->getKey()
                    ],
                    'causeBy'   => [
                        'id'        => $req->users->id,
                        'username'  => $req->users->username,
                        'full_name' => $req->users->full_name
                    ],
                ])->log('Delete Menu Group : ' . $menuGroup->name);
                $menuGroup->delete();
            });
            return responseMessage::responseMessage(1, "Success", 200);
        } catch (\Throwable $th) {
            return responseMessage::responseMessage(0, "Failed Delete Menu Group", 200);
        }
    }
}
