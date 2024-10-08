<?php

function fetchDataMulti($urls, $headers = []) {
    $multiHandle = curl_multi_init();
    $curlHandles = [];
    $responses = [];

    foreach ($urls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        curl_multi_add_handle($multiHandle, $ch);
        $curlHandles[] = $ch;
    }

    $running = null;
    do {
        curl_multi_exec($multiHandle, $running);
        curl_multi_select($multiHandle);
    } while ($running > 0);

    foreach ($curlHandles as $ch) {
        $responses[] = curl_multi_getcontent($ch);
        curl_multi_remove_handle($multiHandle, $ch);
        curl_close($ch);
    }

    curl_multi_close($multiHandle);
    return $responses;
}

function decodeUnicode($str) {
    return preg_replace_callback('/\\\\u([a-f0-9]{4})/i', function($match) {
        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
    }, $str);
}

function extractInfoApi($data, &$processedIds) {
    $extractedData = [];
    $baseLogourl = "https://v3img.voot.com/";

    if (isset($data['result'])) {
        foreach ($data['result'] as $item) {
            if (isValidItem($item, $processedIds)) {
                $processedIds[] = $item['id'];
                $logourl = $baseLogourl . (!empty($item['image16x9']) ? $item['image16x9'] : $item['imageUri']);
                
                $extractedData[] = [
                    'id' => $item['id'],
                    'logourl' => $logourl,
                    'title' => isset($item['fullTitle']) ? decodeUnicode($item['fullTitle']) : ''
                ];
            }
        }
    }
    return $extractedData;
}

function isValidItem($item, &$processedIds) {
    return isset($item['id']) &&
           (!isset($item['isPremium']) || !$item['isPremium']) &&
           (!isset($item['assetMarketType']) || $item['assetMarketType'] !== 'PREMIUM') &&
           (!isset($item['showMarketType']) || $item['showMarketType'] !== 'PREMIUM') &&
           (!isset($item['assetBusinessType']) || !in_array('Premium', $item['assetBusinessType'])) &&
           !in_array($item['id'], $processedIds);
}

function main() {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameter: id']);
        return;
    }
    
    $id = htmlspecialchars($_GET['id']);
    $baseUrl = "https://content-jiovoot.voot.com/psapi/voot/v1/voot-web/{$id}&responseType=common&page=%d";
    
    $headers = [
        "Host: content-jiovoot.voot.com",
        "sec-ch-ua: \" Not A;Brand\";v=\"99\", \"Chromium\";v=\"100\", \"Google Chrome\";v=\"100\"",
        "accept: application/json, text/plain, */*",
        "app-version: 24.06.05.1-0a2d783a",
        "origin: https://www.jiocinema.com",
        "referer: https://www.jiocinema.com/",
        "sec-fetch-site: same-site",
        "sec-fetch-mode: cors",
        "sec-fetch-dest: empty"
    ];

    $allData = [];
    $processedIds = [];
    $page = 1;
    $urls = [];
    $maxPages = 100; // Set a maximum number of pages to fetch

    while ($page <= $maxPages) {
        $url = sprintf($baseUrl, $page);
        $urls[] = $url;
        $page++;

        // Limit to a batch of 10 pages per round
        if (count($urls) >= 10) {
            processBatch($urls, $headers, $allData, $processedIds);
            $urls = [];
        }
    }

    // Process any remaining URLs
    if (!empty($urls)) {
        processBatch($urls, $headers, $allData, $processedIds);
    }

    header('Content-Type: application/json');
    echo json_encode($allData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function processBatch($urls, $headers, &$allData, &$processedIds) {
    $responses = fetchDataMulti($urls, $headers);
    foreach ($responses as $response) {
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data) || !isset($data['result'])) {
            continue; // Skip invalid responses
        }
        $extractedData = extractInfoApi($data, $processedIds);
        if (!empty($extractedData)) {
            $allData = array_merge($allData, $extractedData);
        }
    }
}

main();
