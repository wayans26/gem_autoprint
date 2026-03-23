<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\activity_history;
use App\Models\registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class visitorPrint extends Controller
{
    //
    function printVisitor(Request $req)
    {

        $validate = Validator::make($req->all(), [
            'barcode'    => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $visitor = registration::where('barcode', $req->barcode)->first();

        if (empty($visitor)) {
            return responseMessage::responseMessage(0, "Visitor Not Found", 200);
        }

        $checkinLocation = "AP";
        $checkinTime = Carbon::now();
        $checkinBy = $req->users->full_name;
        $registerId = $visitor->id;
        $firstRegister = false;

        if (!activity_history::whereDate('CheckedInTime', $checkinTime->format('Y-m-d'))->exists()) {
            activity_history::create([
                'CheckedInTime' => $checkinTime,
                'CheckedInLocation' => $checkinLocation,
                'CheckedBy' => $checkinBy,
                'registration_id' => $registerId,
            ]);
            $firstRegister = true;
        }

        $name = $visitor->Name === null ? $visitor->FirstName : $visitor->Name;
        $job = $visitor->JobTitle === null ? $visitor->JobLevel : $visitor->JobTitle;

        $textSize = Str::length($visitor->Company) <= 20 ? "3" : (Str::length($visitor->Company) <= 28 ? "2" : "1");

        $startYText = 470;
        $pengurangan = $textSize === "3" ? 50 : ($textSize === "2" ? 40 : 30);
        $vhmul = $textSize === "1" ? "2" : "2";
        $company = [];
        if ($textSize === "1") {
            $startY = $startYText - ($pengurangan * 2);
            $split_company = str_split($visitor->Company, 32);
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
            array_push($company, 'A' . makeid::calculateCentreX($visitor->Company, $textSize) . ',370,2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($visitor->Company))  . '"');
        }

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            "S2",
            "D9",
            'A' . makeid::calculateCentreX($name, $textSize) . ',' . ($startYText - ($pengurangan * 0)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($name))  . '"',
            'A' . makeid::calculateCentreX($job, $textSize) . ',' . ($startYText - ($pengurangan * 1)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($job))  . '"',
            ...$company,
            'b350,135,Q,m2,s4,eH,"' . makeid::esc($visitor->Barcode) . '"',
            "P1"
        ]) . "\r\n";


        // $data_print = implode("\r\n", [
        //     "N",
        //     "q832",
        //     "Q609,24",
        //     'A' . makeid::calculateCentreX($name, $textSize) . ',470,2,' . $textSize . ',2,2,N,"' . Str::upper(makeid::esc($name)) . '"',
        //     'A' . makeid::calculateCentreX($job, $textSize) . ',420,2,' . $textSize . ',2,2,N,"' . Str::upper(makeid::esc($job)) . '"',
        //     'A' . makeid::calculateCentreX($visitor->Company, $textSize) . ',370,2,' . $textSize . ',2,2,N,"' . Str::upper(makeid::esc($visitor->Company)) . '"',
        //     'b340,135,Q,m2,s6,eM,iA,"' . makeid::esc($visitor->Barcode) . '"',
        //     "P1"
        // ]) . "\r\n";
        $isPrinted = $visitor->IsPrinted === 0 || $firstRegister ? 0 : 1;
        $visitor->update([
            'IsPrinted' => 1,
            'LastCheckinLocation'   => "AP"
        ]);
        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'data_print' => $data_print,
            'isPrinted'  => $isPrinted
        ));
    }
}
