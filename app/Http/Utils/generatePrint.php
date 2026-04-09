<?php

namespace App\Http\Utils;

use Illuminate\Support\Str;

class generatePrint
{

    public static function PPLB($name, $title, $company, $barcode)
    {
        $textSize = Str::length($company) <= 20 ? "3" : (Str::length($company) <= 84 ? "2" : "1");
        $startYText = 470;
        $pengurangan = $textSize === "3" ? 50 : ($textSize === "2" ? 40 : 30);
        $vhmul = $textSize === "1" ? "2" : "2";
        $barcodeSize = $textSize === "3" ? "s6" : ($textSize === "2" ? "s6" : "s5");
        $barcodePositionX = $textSize === "3" ? "340" : ($textSize === "2" ? "345" : "350"); //makin besar makin ke kiri
        $barcodePositionY = $textSize === "3" ? "100" : ($textSize === "2" ? "100" : "100"); //makin kecil makin turun
        $array_company = [];
        if ($textSize === "1") {
            $startY = $startYText - ($pengurangan * 2);
            $split_company = str_split($company, 32);
            foreach ($split_company as $key => $value) {
                array_push($array_company, 'A' . makeid::calculateCentreX($value, $textSize) . ',' . $startY . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($value))  . '"');
                if ($startY > 140) {
                    $startY -= 30;
                } else {
                    $startY -= 1;
                }
            }
        } else if ($textSize === "2") {
            $startY = $startYText - ($pengurangan * 2);
            $split_company = str_split($company, 28);
            foreach ($split_company as $key => $value) {
                array_push($array_company, 'A' . makeid::calculateCentreX($value, $textSize) . ',' . $startY . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($value))  . '"');
                if ($startY > 140) {
                    $startY -= 30;
                } else {
                    $startY -= 1;
                }
            }
        } else {
            array_push($array_company, 'A' . makeid::calculateCentreX($company, $textSize) . ',370,2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($company))  . '"');
        }

        $dpi = 203;

        $feedAdjustmentInch = 1;
        $gapInch = 1.00;
        $topAdjustmentInch = 0.35;
        $backfeedValue = 220 + (int) round($feedAdjustmentInch * 100);
        $backfeedCommand = "f{$backfeedValue}";
        $gapDots = (int) round($gapInch * $dpi);
        $topAdjustmentDots = (int) round($topAdjustmentInch * $dpi);

        $data_print = implode("\r\n", [
            "N",
            "q832",
            "Q609,24",
            "S2",
            "D9",
            'A' . makeid::calculateCentreX($name, $textSize) . ',' . ($startYText - ($pengurangan * 0)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($name))  . '"',
            'A' . makeid::calculateCentreX($title, $textSize) . ',' . ($startYText - ($pengurangan * 1)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($title))  . '"',
            ...$array_company,
            'b' . $barcodePositionX . ',' . $barcodePositionY . ',Q,m2,' . $barcodeSize . ',eH,"' . makeid::esc($barcode) . '"',
            "P1"
        ]) . "\r\n";
        // $data_print = implode("\r\n", [
        //     // $backfeedCommand,
        //     "N",
        //     "q100",
        // "Q609," . $gapDots . "," . "+" . $topAdjustmentDots,
        // "Q609,0,+200",
        //     "Q100,0",
        //     "S2",
        //     "D9",
        //     // 'A' . makeid::calculateCentreX($name, $textSize) . ',' . ($startYText - ($pengurangan * 0)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($name))  . '"',
        //     // 'A' . makeid::calculateCentreX($title, $textSize) . ',' . ($startYText - ($pengurangan * 1)) . ',2,' . $textSize . ',' . $vhmul . ',' . $vhmul . ',N,"' . Str::upper(makeid::esc($title))  . '"',
        //     // ...$array_company,
        //     // 'b' . $barcodePositionX . ',' . $barcodePositionY . ',Q,m2,' . $barcodeSize . ',eH,"' . makeid::esc($barcode) . '"',
        //     // "JF",
        //     "P1"
        // ]) . "\r\n";
        return $data_print;
    }
}
