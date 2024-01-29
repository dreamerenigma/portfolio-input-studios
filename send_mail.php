<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require "PHPMailer/src/PHPMailer.php";
  require "PHPMailer/src/Exception.php";

  // Проверка наличия токена reCAPTCHA в запросе
  if (!isset($_POST['recaptcha_token'])) {
    $response = ["message" => "Отсутствует токен reCAPTCHA"];
    header('Content-type: application/json');
    echo json_encode($response);
    exit;
  }

  $recaptcha_token = $_POST['recaptcha_token'];
  $recaptcha_secret = '6LcFQ5coAAAAABFKoDZIL0BIRoYf-p2JE1yFm0u2'; // Замените на свой секретный ключ reCAPTCHA

  // Проверка токена reCAPTCHA
  $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
  $recaptcha_data = [
    'secret'   => $recaptcha_secret,
    'response' => $recaptcha_token,
  ];

  $recaptcha_options = [
    'http' => [
        'method' => 'POST',
        'content' => http_build_query($recaptcha_data),
    ],
  ];

  $recaptcha_context = stream_context_create($recaptcha_options);
  $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
  $recaptcha_response = json_decode($recaptcha_result, true);

  if (!$recaptcha_response || !$recaptcha_response['success']) {
    $response = ["message" => "Ошибка валидации reCAPTCHA"];
    header('Content-type: application/json');
    echo json_encode($response);
    exit;
  }

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

  $mail->addAddress("");
  $mail->setFrom("");

  $mail->Subject = "[Заявка с формы]";
  $mail->MsgHTML($body);

  if (!$mail->send()) {
      $message = "Ошибка отправки";
  } else {
      $message = "Данные отправлены!";
  }

  $response = ["message" => $message];
  header('Content-type: application/json');
  echo json_encode($response);
?>
