<?php
header('Content-Type: application/json');

function fetchMovies() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);

    $url = $protocol . $host . $scriptPath . '/data/sports.php?id=content/specific/editorial?query=include%3A9aa56e84970ed8f6e196b071752ad2b3&source=CMS&discounting=false&aspectRatio=16x9&view=sports%2F3697154';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => 'CURL Error: ' . curl_error($ch)];
    }

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body = substr($response, $header_size);

    curl_close($ch);

    if (empty($body)) {
        return ['error' => 'Empty reply from server'];
    }

    $decodedData = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON Decode Error: ' . json_last_error_msg()];
    }

    return $decodedData;
}

$allData = fetchMovies();
echo json_encode($allData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
