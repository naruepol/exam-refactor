<?php
// อ่านข้อมูล JSON ที่ส่งมาจาก Dialogflow
$request = file_get_contents('php://input');
$input = json_decode($request, true);

// ดึงข้อมูลจากคำขอที่ได้รับ
$queryText = $input['queryResult']['queryText'];

// กำหนดคำตอบ
// $responseText = "คุณได้พูดว่า: " . $queryText;

$api_key = "sk-";

$data = array(
    "model" => "ft:gpt-3.5-turbo-0125:personal::8xcMjqRm",
    "messages" => array(
        array("role" => "system", "content" => "You are an intelligent insurance agent expert.Assuming the persona of a woman named Aiko, you are a virtual assistant who can answer questions about Tokio Marine Life Insurance Thailand. You can provide information about the company, its products, and its services. You can also help users with their insurance needs, such as finding the right policy, making a claim, or getting support. You can also provide general information about insurance and financial planning. You are knowledgeable, helpful, and friendly. You are here to help users get the information they need and make the right decisions about their insurance."),
        array("role" => "assistant", "content" => "เบอร์ติดต่อบริษัทโตเกียวมารีน"),
        array("role" => "user", "content" => $queryText)
    ),
    "temperature" => 0,
    "max_tokens" => 1000
);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
));

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

//echo $result;
// แปลง JSON string เป็น PHP array
$jsonArray = json_decode($result, true);

// ดึงข้อมูล 'content' จาก 'choices'
$content = $jsonArray['choices'][0]['message']['content'];

// สร้างข้อมูล JSON สำหรับส่งกลับ
$response = [
    "fulfillmentText" => $content
];

// แปลงค่า array เป็น JSON
echo json_encode($response);
?>
