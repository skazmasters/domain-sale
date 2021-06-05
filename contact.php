<?php
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if (isset($_POST) && $contentType === "application/json") {
  $requestBody = file_get_contents('php://input');
  $requestBody = json_decode($requestBody, true);

  if ($requestBody === null) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode([
      'errorMessage' => 'Please provide valid JSON',
    ]);
    die;
  }

  // EDIT THE 2 LINES BELOW AS REQUIRED
  $email_to = "a.belov@logtrade.ru";
  $email_subject = "Поступило новое предложение о покупке домена ahml1.ru";


  $name = trim($requestBody['name']); // required
  $email_from = trim($requestBody['email']); // required
  $telephone = trim($requestBody['phone']); // not required
  $comments = trim($requestBody['message']); // required


  $email_message = "Детали сообщения ниже.\n\n";

  function clean_string($string)
  {
    $bad = array("content-type", "bcc:", "to:", "cc:", "href");
    return str_replace($bad, "", $string);
  }

  $email_message .= "Имя: " . clean_string($name) . "\n";
  $email_message .= "Email: " . clean_string($email_from) . "\n";
  $email_message .= "Телефон: " . clean_string($telephone) . "\n";
  $email_message .= "Сообщение: " . clean_string($comments) . "\n";

  // create email headers
  $headers = 'From: ' . $email_from . "\r\n" .
    'Reply-To: ' . $email_from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  $success = @mail($email_to, $email_subject, $email_message, $headers);

  if ($success) {
    http_response_code(200);

    // Json Header
    header('Content-Type: application/json');
    // return changed $requestBody
    echo json_encode($requestBody);
  } else {
    http_response_code(400);
    echo "Ошибка. Данные не отправлены.";
  }

  die;
}
?>
