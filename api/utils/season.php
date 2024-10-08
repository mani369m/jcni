<?php

function getSeason($show) {
    $season = array();
    $offSet = 1;
    $finalpg = false;
    $headers = array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'Accept: application/json',
    );

    do {
        $url = sprintf('https://content-jiovoot.voot.com/psapi/voot/v1/voot-web/content/generic/season-by-show?sort=season:desc&id=%s&page=%s&responseType=common', $show, $offSet);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['error' => 'Request Error: ' . curl_error($ch)];
        }

        curl_close($ch);
        $jd = json_decode($response, true);

        $items = $jd['result'] ?? [];
        $timezone = new DateTimeZone('Asia/Kolkata');
        $currentDateTime = new DateTime('now', $timezone);
        $currentDateTimeFormatted = $currentDateTime->format('h:ia jS F Y');

        foreach ($items as $item) {
            $title = $item['seasonName'];
            $item_id = $item['seasonId'];
            $thumb = str_replace(' ', '%20', $item['seo']['ogImage']);
            $season_number = $item['season'];
            $genres = implode(', ', $item['genres']);

            $default_description = "Congratulations! You have arrived at the right place. Here, you can enjoy your favorite TV shows without any ads or subscriptions, completely free. Thank you for being with us \u{1F5A4}. Join our family to benefit from more new projects in the future by visiting our telegram channel @sardariptv.";

            $season[] = array(
                'title' => $title,
                'thumb' => $thumb,
                'description' => $default_description,
                'seasonid' => $item_id,
                'season' => $season_number,
                'genre' => $genres,
                'Now' => $currentDateTimeFormatted
            );
        }

        $totals = isset($jd['totalAsset']) ? $jd['totalAsset'] : 1;
        $itemsLeft = $totals - $offSet * 10;

        if ($itemsLeft > 0) {
            $finalpg = false;
            $offSet += 1;
        } else {
            $finalpg = true;
        }

    } while (!$finalpg);

    $season = array_reverse($season);

    return $season;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = getSeason($id);

    foreach ($result as &$item) {
        $item['description'] = strip_tags($item['description']);
    }

    header('Content-Type: application/json');
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

?>
