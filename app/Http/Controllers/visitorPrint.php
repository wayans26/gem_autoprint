<?php

namespace App\Http\Controllers;

use App\Http\Utils\generatePrint;
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

        if (!activity_history::whereDate('CheckedInTime', $checkinTime->format('Y-m-d'))->where('registration_id', $registerId)->exists()) {
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

        $isPrinted = $visitor->IsPrinted === 0 || $firstRegister ? 0 : 1;
        $visitor->update([
            'IsPrinted' => 1,
            'LastCheckinLocation'   => "AP"
        ]);
        $data_print = generatePrint::PDFPPLB($name, $job, $visitor->Company, $visitor->Barcode);
        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'data_print' => $data_print,
            'isPrinted'  => $isPrinted
        ));
    }
}
