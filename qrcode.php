<?php
include "assets/plugin/phpqrcode/qrlib.php";

function generateQRCode($url, $filename)
{
    $tempDir = 'temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir);
    }
    $filePath = $tempDir . $filename;
    QRcode::png($url, $filePath, QR_ECLEVEL_L, 5);
    return $filePath;
}
