<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class sendEmail
{
    public static function sendEmailRegistration($req, $barcode, $banner, $quote, $team)
    {
        $path = "Barcode/2024/event3/" . $req->name . "-" . $barcode . ".png";
        $imageContent = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=200x200&qzone=4&data=" . $barcode);
        $save = File::put(public_path($path), $imageContent);
        $url = "http://" . $req->host() . '/' . $path;

        Mail::send('EmailPage.emailUndangan', [
            'url'       => $url,
            'req'       => $req,
            'quote'     => $quote,
            'banner'    => $banner,
            'team'      => $team
        ], function ($message) use ($req, $quote) {
            $message->from('no.reply@reg-gemindonesia.net', 'PT. Global Expo Management');
            $message->to("pt.globalexpomanagement@gmail.com");
            $message->subject($quote);
        });

        Mail::send('EmailPage.emailUndangan', [
            'url'       => $url,
            'req'       => $req,
            'quote'     => $quote,
            'banner'    => $banner,
            'team'      => $team
        ], function ($message) use ($req, $quote) {
            $message->from('no.reply@reg-gemindonesia.net', 'PT. Global Expo Management');
            $message->to("operationalwagem3@gmail.com");
            $message->subject($quote);
        });
    }
}
