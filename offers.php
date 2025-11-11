<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$user_id = "2465402";
$key = "f1e02dac224fac4ccd9233bd308d29bd";

$user_ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

$feedurl = "https://www.cpagrip.com/common/offer_feed_rss.php?user_id={$user_id}&key={$key}&limit=10&ip={$user_ip}&ua=" . urlencode($user_agent);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $feedurl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

$xml = @simplexml_load_string($output);

if ($xml === false) {
    echo json_encode(["error" => "Feed not found"]);
    exit;
}

$offers = [];
foreach ($xml->offers->offer as $offer) {
    $offers[] = [
        "title" => (string)$offer->title,
        "description" => (string)$offer->description,
        "image" => (string)$offer->offerphoto,
        "link" => str_replace("www.cpagrip.com", "filetrkr.com", (string)$offer->offerlink),
        "payout" => (string)$offer->payout
    ];
}

echo json_encode(["offers" => $offers]);
?>
