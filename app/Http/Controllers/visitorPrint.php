<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\activity_history;
use App\Models\registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            'A' . makeid::calculateCentreX($name) . ',470,2,3,2,2,N,"' . makeid::esc($name) . '"',
            'A' . makeid::calculateCentreX($job) . ',420,2,3,2,2,N,"' . makeid::esc($job) . '"',
            'A' . makeid::calculateCentreX($visitor->Company) . ',370,2,3,2,2,N,"' . makeid::esc($visitor->Company) . '"',
            'b340,135,Q,m2,s6,eM,iA,"' . makeid::esc($visitor->Barcode) . '"',
            "P1"
        ]) . "\r\n";
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
