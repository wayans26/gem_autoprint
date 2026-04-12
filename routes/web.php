<?php

use App\Exports\catalog_group;
use App\Http\Controllers\imageController;
use App\Http\Controllers\reportController;
use App\Http\Utils\makeid;
use App\Mail\reset_password_mail;
use App\Models\license_permit;
use App\Models\personal_token;
use App\Models\report_file;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\Renderers\SvgRenderer;
use Picqer\Barcode\Types\TypeCodabar;
use Picqer\Barcode\Types\TypeCode128;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZanySoft\Zip\Facades\Zip;


// Route::get('/test', function () {
//     // $barcodeSvg = (new TypeCode128())->getBarcode("YANAAAA");
//     // $renderer = new SvgRenderer();
//     // $renderer->setSvgType($renderer::TYPE_SVG_INLINE);
//     // $barcodeSvgRendered = $renderer->render($barcodeSvg, 360, 70);
//     // $qrcode = QrCode::format('svg')
//     //     ->size(220)
//     //     ->margin(1)
//     //     ->errorCorrection('H')
//     //     ->generate("AAAA");
//     $qrcode = QrCode::format('png')
//         ->size(220)
//         ->margin(1)
//         ->errorCorrection('H')
//         ->generate("YAN AHJAHG");
//     $widthMM = 104.1;
//     $heightMM = 76.2;
//     $urlQr = "data:image/png;base64," . base64_encode($qrcode);
//     // dd($urlQr);
//     return view('Print.barcode',  [
//         'nama'  => "TEST",
//         'job'   => "Job",
//         'company' => "COMPANY",
//         'barcodeSvg' => $urlQr,
//     ]);
// });

Route::get('/', function (Request $req) {
    // dd(Hash::make("admin"));
    if ($req->has('token')) {
        $token = personal_token::where('token', $req->token)->first();
        if (empty($token)) {
            return redirect()->route('auth', ['any' => 'login']);
        }
        $user = user::find($token->iduser);

        if (empty($user)) {
            return redirect()->route('auth', ['any' => 'login']);
        }
        return redirect()->route('user', ['any' => 'redirect']);
    }
    return redirect()->route('auth', ['any' => 'login']);
});
Route::get('/auth/{any}', function () {
    return view('index_login');
})->where("any", '.*')->name('auth');


Route::get('/user/{any}', function () {
    return view('index');
})->where("any", '.*')->name('user');
