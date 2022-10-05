<?php

namespace App\Services;


use App\Models\ShortUrl;

class TinyUrlService
{
    public function getShortUrl($url, $provider) {

        $api_url = 'https://tinyurl.com/api-create.php?url=' . $url;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $api_url);

        $new_url = curl_exec($curl);
        curl_close($curl);

        $short_url = new ShortUrl();
        $short_url->long_url = $url;
        $short_url->short_url = $new_url;
        $short_url->provider = $provider;
        $short_url->save();

        return response()->json([
            'url' => $url,
            'link' => $new_url,
        ], 201);
    }
}
