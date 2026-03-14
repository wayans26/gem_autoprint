<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\registration;
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

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            'A' . makeid::calculateCentreX($visitor->Name) . ',470,2,3,2,2,N,"' . makeid::esc($visitor->Name) . '"',
            'A' . makeid::calculateCentreX($visitor->JobTitle) . ',420,2,3,2,2,N,"' . makeid::esc($visitor->JobTitle) . '"',
            'A' . makeid::calculateCentreX($visitor->Company) . ',370,2,3,2,2,N,"' . makeid::esc($visitor->Company) . '"',
            'b310,135,Q,m2,s6,eM,iA,"' . makeid::esc($visitor->Barcode) . '"',
            "P1"
        ]) . "\r\n";
        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
}
