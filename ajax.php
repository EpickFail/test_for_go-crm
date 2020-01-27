<?
$email_user = 'eickfailboost@yandex.ru';
$phone_user = '8999999999';

function send_SMS($message) {
  return true;
}


function getData($methodURL, $params)
{
    $queryUrl = 'https://b24-pv6mh8.bitrix24.ru/rest/1/gcl3ionifevz9c9z'.$methodURL;

    $queryData = http_build_query($params);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData
      ]);
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result, true);
      return $result;
}


$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$message = $_POST['message'];

$result = [];

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result['email'] = 1;
}
else {
  $result['email'] = 0;
  echo json_encode($result);
  exit;
}

if(!preg_match("/^[0-9]{10,10}+$/", $phone)){
   $result['phone'] = 1;
}
else {
  $result['phone'] = 0;
  echo json_encode($result);
  exit;
}

$subject = 'Новая заявка';
$mail_message = 'Новая заявка. Имя: '.$name.' Номер телефона: '.$phone.
                ' E-Mail: '.$mail.' Сообщение: '.$message;
$headers  = "Content-type: text/html; charset=utf-8\r\n";

mail($email_user, $subject, $mail_message, $headers);

$subject_client = 'Заявка принята!';
$mail_message_client = 'Добрый день! Ваша заявка принята.';
if (mail($email, $subject_client, $mail_message_client, $headers)) {
  $result['email_send'] = 1;
}
else {
  $result['email_send'] = 0;
  echo json_encode($result);
  exit;
}
echo json_encode($result);

$Params = ['fields' => ['TITLE' => $name,
                        'NAME' => $name,
                        'STATUS_ID' => 'NEW',
                        'PHONE' => [["VALUE" => $phone, "VALUE_TYPE" => "WORK"]],
                        'EMAIL' => [["VALUE" => $email, "VALUE_TYPE" => "WORK"]]],
           'params' => ["REGISTER_SONET_EVENT" => "N"]];
$Method = '/crm.lead.add.json';
$Data = getData($Method, $Params);

if ($Data['result'] != 1){
  send_SMS($phone_user);
}
