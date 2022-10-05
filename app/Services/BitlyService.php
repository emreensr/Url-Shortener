<?php

namespace App\Services;

use App\Http\Requests\UrlRequest;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Http;

class BitlyService
{

    protected $access_token;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('url_providers.apiUrl');
        $this->access_token = config('url_providers.access_token');
    }

    public function getShortUrl($url, $provider)
    {
        $response = Http::withHeaders(array(
                'Authorization'=>'Bearer '.$this->access_token,
                'Content-Type' => 'application/json'
                ))->get('https://api-ssl.bitly.com/v4/groups');

        if($response->status() == 200){
            $response = json_decode($response);
            $guid = $response->groups[0]->guid;
        }
        else{
            echo "Cannot get Guid";
        }

        $data = array(
            'long_url' => $url,
            'group_guid' => $guid
        );

        $postData = json_encode($data);

        $header = array(
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json',
        );

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        $result = json_decode($result);

        $short_url = new ShortUrl();
        $short_url->long_url = $url;
        $short_url->short_url = $result->link;
        $short_url->provider = $provider;
        $short_url->save();

        return response()->json([
            'url' => $url,
            'link' => $result->link
            ], 201);

    }
}
