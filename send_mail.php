<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require "PHPMailer/src/PHPMailer.php";
  require "PHPMailer/src/Exception.php";

  define('SMARTCAPTCHA_SERVER_KEY', 'your_server_key');

  function check_captcha($token) {
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true; // Change this to false if you want to deny access on error
    }

    $resp = json_decode($server_output);
    return $resp->status === "ok";
  }

  $token = $_POST['smart-token'];
  if (check_captcha($token)) {
    // SmartCaptcha passed, proceed with sending email
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";
    $mail->IsHTML(true);

    $mail->AddEmbeddedImage('img/logo.webp', 'logo_2u');

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $message = $_POST["message"];

    $email_template = "template_mail.html";

    $body = file_get_contents($email_template);

    $body = str_replace('%name%', $name, $body);
    $body = str_replace('%email%', $email, $body);
    $body = str_replace('%phone%', $phone, $body);
    $body = str_replace('%message%', $message, $body);

    // адрес получателя
    $mail->addAddress("jarekismail@gmail.com");
    // адрес отправителя
    $mail->setFrom("feedback@inputstudios.ru");

    $mail->Subject = "[Заявка с формы]";
    $mail->MsgHTML($body);

    if (!$mail->send()) {
        $message = "Ошибка отправки";
    } else {
        $message = "Данные отправлены!";
    }
  } else {
    // SmartCaptcha verification failed
    $message = "Ошибка валидации SmartCaptcha";
  }

  $response = ["message" => $message];
  header('Content-type: application/json');
  echo json_encode($response);
?>
