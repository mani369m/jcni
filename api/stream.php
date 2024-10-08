<?php
session_start();

function initCurl($url, $headers, $body = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return $ch;
}

function guestToken() {
    $url = "https://auth-jiocinema.voot.com/tokenservice/apis/v4/guest";
    $data = '{"appName":"RJIL_JioCinema","deviceType":"phone","os":"ios","deviceId":"3968183400","freshLaunch":false,"adId":"3968183400"}';
    $defaultHeaders = [
        'Accept: application/json, text/plain, */*',
        'Content-Type: application/json',
        'Referer: https://www.jiocinema.com/',
        'content-version: V6',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
    ];

    $ch = initCurl($url, $defaultHeaders, $data);
    $response = curl_exec($ch);
    curl_close($ch);

    $resp = json_decode($response, true);

    if (isset($resp['authToken'], $resp['deviceId'], $resp['userId'])) {
        $_SESSION['Accesstoken'] = $resp['authToken'];
        $_SESSION['Deviceid'] = $resp['deviceId'];
        $_SESSION['Uniqueid'] = $resp['userId'];
        $_SESSION['refreshtoken'] = $resp['refreshTokenId'];
        return $resp['authToken'];
    } else {
        throw new Exception("Failed to obtain guest token");
    }
}

function refreshToken($reftoken) {
    $url = "https://auth-jiocinema.voot.com/tokenservice/apis/v4/refreshtoken";
    $data = '{"appName":"RJIL_JioCinema","appVersion":"5.1.1","deviceId":"3968183400","refreshToken": "' . $reftoken . '"}';
    $defaultHeaders = [
        'Accept: application/json, text/plain, */*',
        'Content-Type: application/json',
        'Referer: https://www.jiocinema.com/',
        'content-version: V6',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'
    ];

    $ch = initCurl($url, $defaultHeaders, $data);
    $response = curl_exec($ch);
    curl_close($ch);

    $resp = json_decode($response, true);

    if (isset($resp['authToken'], $resp['refreshTokenId'], $resp['userId'])) {
        $_SESSION['Accesstoken'] = $resp['authToken'];
        $_SESSION['refreshtoken'] = $resp['refreshTokenId'];
        $_SESSION['Uniqueid'] = $resp['userId'];
        return $resp['authToken'];
    } else {
        throw new Exception("Failed to refresh token");
    }
}

function fetchVideo($id, $retry = true) {
    if (isset($_SESSION['Accesstoken'], $_SESSION['Deviceid'], $_SESSION['Uniqueid'])) {
        $headers = [
            'Accesstoken: ' . $_SESSION['Accesstoken'],
            'Deviceid: ' . $_SESSION['Deviceid'],
            'Uniqueid: ' . $_SESSION['Uniqueid'],
            'Appname: RJIL_JioCinema',
            'Content-Type: application/json',
            'Versioncode: 570',
            'X-Platform: androidweb',
            'X-Apisignatures: o668nxgzwff',
            'X-Platform-Token: web',
            'User-Agent: Mozilla/5.0 (Linux; Android 9; vivo 1916) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Mobile Safari/537.36',
            'Sec-CH-UA-Mobile: ?1',
            'Sec-CH-UA: "Not A;Brand";v="99", "Chromium";v="100", "Google Chrome";v="100"',
            'Sec-CH-UA-Platform: "Android"',
            'Referer: https://www.jiocinema.com/',
            'Origin: https://www.jiocinema.com',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Accept-Language: en-US,en;q=0.9,hi;q=0.8'
        ];
    } else {
        $token = guestToken();
        $headers = [
            'Accesstoken: ' . $token,
            'Deviceid: ' . $_SESSION['Deviceid'],
            'Uniqueid: ' . $_SESSION['Uniqueid'],
            'Appname: RJIL_JioCinema',
            'Content-Type: application/json',
            'Versioncode: 570',
            'X-Platform: androidweb',
            'X-Apisignatures: o668nxgzwff',
            'X-Platform-Token: web',
            'User-Agent: Mozilla/5.0 (Linux; Android 9; vivo 1916) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Mobile Safari/537.36',
            'Sec-CH-UA-Mobile: ?1',
            'Sec-CH-UA: "Not A;Brand";v="99", "Chromium";v="100", "Google Chrome";v="100"',
            'Sec-CH-UA-Platform: "Android"',
            'Referer: https://www.jiocinema.com/',
            'Origin: https://www.jiocinema.com',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'Accept-Language: en-US,en;q=0.9,hi;q=0.8'
        ];
    }

    $apiUrl = 'https://apis-jiovoot.voot.com/';
    $url = $apiUrl . 'playback/v1/' . $id;

    $body = [
        "4k" => false,
        "ageGroup" => "18+",
        "appVersion" => "3.4.0",
        "bitrateProfile" => "xhdpi",
        "capability" => [
            "drmCapability" => [
                "aesSupport" => "yes",
                "fairPlayDrmSupport" => "yes",
                "playreadyDrmSupport" => "none",
                "widevineDRMSupport" => "yes"
            ],
            "frameRateCapability" => [
                [
                    "frameRateSupport" => "30fps",
                    "videoQuality" => "1440p"
                ]
            ]
        ],
        "continueWatchingRequired" => true,
        "dolby" => false,
        "downloadRequest" => false,
        "hevc" => false,
        "kidsSafe" => false,
        "manufacturer" => "Android",
        "model" => "Android",
        "multiAudioRequired" => true,
        "osVersion" => "9",
        "parentalPinValid" => true,
        "x-apisignatures" => "o668nxgzwff",
        "deviceRange" => "",
        "networkType" => "3g",
        "deviceMemory" => 2048
    ];

    $body = json_encode($body);

    $ch = initCurl($url, $headers, $body);
    $response = curl_exec($ch);
    if ($response === false) {
        echo json_encode(["error" => curl_error($ch)]);
        curl_close($ch);
        return;
    }

    curl_close($ch);

    $jsonResponse = json_decode($response, true);

    if (isset($jsonResponse['code']) && $jsonResponse['code'] == 419 && $retry) {
        if (isset($_SESSION['refreshtoken'])) {
            $token = refreshToken($_SESSION['refreshtoken']);
            $headers['Accesstoken'] = $token;
            return fetchVideo($id, false);
        } else {
            echo json_encode(["error" => "Token refresh required but no refresh token available."]);
            return;
        }
    } elseif (isset($jsonResponse['code']) && $jsonResponse['code'] != 200) {
        echo json_encode(["error" => $jsonResponse['message']]);
        return;
    }

    return parseResponse($jsonResponse);
}

function parseResponse($jsonResponse) {
    if (!isset($jsonResponse['data'])) {
        return ["error" => "No data found in API response"];
    }

    $name = $jsonResponse['data']['name'] ?? null;
    $images = $jsonResponse['data']['images'] ?? '';
    $logo = "https://v3img.voot.com/$images";
    $dash_url = "";
    $license_key = "";

    if (isset($jsonResponse['data']['playbackUrls']) && is_array($jsonResponse['data']['playbackUrls'])) {
        $playbackUrls = $jsonResponse['data']['playbackUrls'];

        foreach ($playbackUrls as $url) {
            if ($url['streamtype'] === 'dash') {
                $dash_url = str_replace('\/', '/', $url['url']);
            }
            if (!empty($url['licenseurl'])) {
                $license_key = str_replace('\/', '/', $url['licenseurl']);
            }
        }

        if (empty($license_key)) {
            $license_key = '';
        }
    }

    $logo = str_replace('\/', '/', $logo);
    $dash_url = str_replace('\/', '/', $dash_url);

    return [
        "name" => $name,
        "logo" => $logo,
        "dash_url" => $dash_url,
        "licenseurl" => $license_key
    ];
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(["error" => "ID parameter is missing or empty"]);
    exit;
}

$id = $_GET['id'];

$data = fetchVideo($id);

if (is_array($data)) {
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(["error" => "Failed to fetch video data"]);
}
?>
