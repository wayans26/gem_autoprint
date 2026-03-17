<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Http\Utils\sendEmail;
use App\Models\activity_history;
use App\Models\country;
use App\Models\exhibitions;
use App\Models\registration;
use App\Models\sub_exhibitions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class registerController extends Controller
{
    //
    function getRegisterData(Request $req)
    {
        $country = country::select('idcountry', 'country_name')->get();
        $exhibitions = exhibitions::select('idexhibitions', 'name')->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'country'       => $country,
            'exhibitions'   => $exhibitions
        ));
    }

    function getSubExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'idexhibitions' => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $subExhibitions = sub_exhibitions::where('idexhibitions', $req->idexhibitions)->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $subExhibitions);
    }

    function registrasi(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibitions'       => 'required',
            'sub_exhibitions'   => 'required',
            'name'              => 'required',
            'title'             => 'required',
            'company'           => 'required',
            'email'             => 'required|email',
            'phone'             => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::find($req->exhibitions);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $sub_exhibitions = sub_exhibitions::find($req->sub_exhibitions);
        if (empty($sub_exhibitions)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }
        $barcode = $exhibition->type . $exhibition->idexhibitions . "=" . makeid::createId(6);



        $registrasi = registration::create([
            'Exhibition'    => $exhibition->idexhibitions,
            'NameTitle'                             => 0,
            'Name'                                  => $req->name,
            'Company'                               => $req->company,
            'JobTitle'                              => $req->title,
            'Address'                               => "",
            'State'                                 => "",
            'Country'                               => $req->country,
            'MobilePhone'                           => $req->phone,
            'Email'                                 => $req->email,
            'JobFunction'                           => "0",
            'VisitPurpose'                          => "0",
            'PurchasingRole'                        => "0",
            'EventFind'                             => "0",
            'IsReceivedInvitationNext'              => "0",
            'IsReceivedInvitationNextAddressSame'   => "0",
            'Barcode'                               => $barcode,
            'IsPrinted'                             => "1",
            'SubExhibition'                         => $sub_exhibitions->idsubexhibitions,
            'SubExhibitionName'                     => $sub_exhibitions->nama,
            'RegisterDate'                          => Carbon::now()->format("Y-m-d H:i:s")
        ]);

        $checkinLocation = "AP";
        $checkinTime = Carbon::now();
        $checkinBy = $req->users->full_name;
        $registerId = $registrasi->id;

        activity_history::create([
            'CheckedInTime' => $checkinTime,
            'CheckedInLocation' => $checkinLocation,
            'CheckedBy' => $checkinBy,
            'registration_id' => $registerId,
        ]);

        sendEmail::sendEmailRegistration($req, $barcode, $exhibition, $sub_exhibitions);

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            'A' . makeid::calculateCentreX($req->name) . ',470,2,3,2,2,N,"' . makeid::esc($req->name) . '"',
            'A' . makeid::calculateCentreX($req->title) . ',420,2,3,2,2,N,"' . makeid::esc($req->title) . '"',
            'A' . makeid::calculateCentreX($req->company) . ',370,2,3,2,2,N,"' . makeid::esc($req->company) . '"',
            'b340,135,Q,m2,s6,eM,iA,"' . makeid::esc($barcode) . '"',
            "P1"
        ]) . "\r\n";

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
}
