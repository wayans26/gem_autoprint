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
use Illuminate\Support\Str;

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

        $textSize = Str::length($req->company) <= 20 ? "3" : (Str::length($req->company) <= 28 ? "2" : "1");
        $startYText = 470;
        $pengurangan = $textSize === "3" ? 50 : ($textSize === "2" ? 40 : 30);
        $vhmul = $textSize === "1" ? "2" : "2";
        $company = [];
        if ($textSize === "1") {
            $startY = $startYText - ($pengurangan * 2);
            $split_company = str_split($req->company, 32);
            // dd($split_company);
            foreach ($split_company as $key => $value) {
                array_push($company, 'A' . makeid::calculateCentreX($value, $textSize) . ',' . $startY . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($value))  . '"');
                if ($startY > 140) {
                    $startY -= 30;
                } else {
                    $startY -= 1;
                }
            }
        } else {
            array_push($company, 'A' . makeid::calculateCentreX($req->company, $textSize) . ',370,2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($req->company))  . '"');
        }

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            'A' . makeid::calculateCentreX($req->name, $textSize) . ',' . ($startYText - ($pengurangan * 0)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($req->name))  . '"',
            'A' . makeid::calculateCentreX($req->title, $textSize) . ',' . ($startYText - ($pengurangan * 1)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($req->title))  . '"',
            ...$company,
            'b340,135,Q,m2,s6,eM,"' . makeid::esc($barcode) . '"',
            "P1"
        ]) . "\r\n";

        // dd($data_print);

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
}
