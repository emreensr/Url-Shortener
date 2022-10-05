<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Services\BitlyService;
use App\Services\TinyUrlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UrlShortenerController extends Controller
{
    public function getShortUrl(UrlRequest $request)
    {
        if ($request->provider == 'tinyurl'){
                $service = new TinyUrlService();
                return $service->getShortUrl($request->url, $request->provider);
        }
        elseif ($request->provider == 'bitly'){
                 $service = new BitlyService();
                 return $service->getShortUrl($request->url, $request->provider);
        }
        else{
                 echo "Unknown provider";
        }

   }
}
