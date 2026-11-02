<?php
$files = ['assets/js/admin.js', 'assets/js/storefront.js', 'assets/images/placeholder-lock.svg'];
foreach ($files as $file) {
    $ch = curl_init('http://localhost:8000/' . $file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $len = strlen($response);
    curl_close($ch);
    echo "$file => Code: $code | Type: $type | Size: $len bytes\n";
}
