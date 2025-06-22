<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/plugin/PHPMailer-master/src/Exception.php';
require 'assets/plugin/PHPMailer-master/src/PHPMailer.php';
require 'assets/plugin/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['bumil_email'];
    $nama = $_POST['bumil_nama'];

    $mail = new PHPMailer(true);

    try {
        // Konfigurasi server email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'timpresmil@gmail.com'; // Ganti dengan username SMTP Anda
        $mail->Password = 'kubpdvudcsnikbyv'; // Ganti dengan password SMTP Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pengaturan email
        $mail->setFrom('timpresmil@gmail.com', 'Tim Kesehatan Presmil.com');
        $mail->addAddress($email);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Pemberitahuan Kuesioner Ibu Hamil';
        $mail->Body = "Halo $nama,<br><br>Terima kasih telah mengisi kuesioner ibu hamil.<br><br>Salam,<br>Tim Kesehatan";
        $mail->AltBody = "Halo $nama,\n\nTerima kasih telah mengisi kuesioner ibu hamil.\n\nSalam,\nTim Kesehatan";

        $mail->send();
        echo 'Email berhasil dikirim.';
    } catch (Exception $e) {
        echo "Email gagal dikirim. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request method.";
}
