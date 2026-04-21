<?php

namespace App\Http\Controllers;

use App\Http\Utils\generatePrint;
use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Http\Utils\sendEmail;
use App\Models\activity_history;
use App\Models\country;
use App\Models\exhibitions;
use App\Models\registration;
use App\Models\sub_exhibitions;
use App\Models\user_has_exhibitions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class registerController extends Controller
{
    //
    function getRegisterData(Request $req)
    {
        $country = country::select('idcountry', 'country_name')->get();
        $userExhibitions = user_has_exhibitions::where('user_id', $req->users->id)->pluck('exhibition_id')->toArray();
        $exhibitions = exhibitions::whereIn('idexhibitions', $userExhibitions)->select('idexhibitions', 'name')->get();

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

        if (registration::where([
            'SubExhibition' => $sub_exhibitions->idsubexhibitions,
            'Email'         => $req->email
        ])->exists()) {
            return responseMessage::responseMessage(0, "Email already registered", 200);
        }
        $barcode = $exhibition->type . $exhibition->idexhibitions . "-" . makeid::createId(6);



        $registrasi = registration::create([
            'Exhibition'                            => $exhibition->idexhibitions,
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

        $data_print = generatePrint::PDFPPLB($req->name, $req->title, $req->company, $barcode);

        // dd($data_print);

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
    function testPrint(Request $req)
    {


        // $data_print = generatePrint::PPLB("TEST", "TEST", "TEST", "oiuytghjkiuytghjkiuy");
        $data_print = generatePrint::PDFPPLB("TEST", "TEST", "TEST PRINTER WAYAN AJAH DEH WHAHAHAH WAYAN PRINTER COMPANY HAHAHA TEXT", "oiuytghjkiuytghjkiuy");

        // dd($data_print);

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
}
