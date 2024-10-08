<?php

function getAllEpisodes($show)
{
    $episodes = array();
    $base_url = 'https://content-jiovoot.voot.com/psapi/voot/v1/voot-web/content/generic/series-wise-episode';
    $params = array(
        'sort' => 'episode:asc',
        'id' => $show,
        'responseType' => 'common'
    );

    $offset = 1;
    $hasMorePages = true;

    while ($hasMorePages) {
        $params['page'] = $offset;
        $url = $base_url . '?' . http_build_query($params);
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            curl_close($ch);
            return $episodes;
        }

        curl_close($ch);
        $json_response = json_decode($response, true);

        if (!$json_response || !isset($json_response['result'])) {
            echo 'Error: Invalid JSON response';
            return $episodes;
        }

        $items = $json_response['result'];

        if (empty($items)) {
            $hasMorePages = false;
        } else {
            foreach ($items as $item) {
                $fullTitle = isset($item['seo']['title']) ? $item['seo']['title'] : '';
                $episodePart = '';
                $middlePart = '';

                if (preg_match('/Season (\d+) Episode (\d+)/', $fullTitle, $matches)) {
                    $seasonNumber = $matches[1];
                    $episodeNumber = $matches[2];
                    $episodePart = 'S' . $seasonNumber . 'E' . $episodeNumber;
                }

                $titleParts = explode(' - ', $fullTitle);
                if (isset($titleParts[0])) {
                    $middleParts = explode(':', $titleParts[0]);
                    if (isset($middleParts[1])) {
                        $middlePart = trim($middleParts[1]);
                    }
                }

                $finalTitle = trim($episodePart . ' ' . $middlePart);
                $eid = isset($item['id']) ? $item['id'] : '';
                $thumb = isset($item['seo']['ogImage']) ? $item['seo']['ogImage'] : '';

                $episode = array(
                    'title' => $finalTitle,
                    'thumb' => $thumb,
                    'eid' => $eid
                );

                $episodes[] = $episode;
            }

            $offset++;
        }
    }

    return $episodes;
}

$id = isset($_GET['id']) ? $_GET['id'] : 'default_id';
$episodes = getAllEpisodes($id);

header('Content-Type: application/json');
echo json_encode($episodes, JSON_PRETTY_PRINT);
?>
