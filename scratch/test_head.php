<?php
$ch = curl_init('http://localhost:8000/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $code\n";
preg_match_all('/<link[^>]+>/i', $html, $matches);
print_r($matches[0]);
