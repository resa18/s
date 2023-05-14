<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class ArticlesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $date = $request->date;
        $source = $request->source;
        $category = $request->category;
        $page = $request->page;


        $url = "";
        $key = "";
        if ($source == 'guardian') {
            $url = env("GUARDIAN_URL", "");
            $key = env("GUARDIAN_KEY", "");

            $url = $url . "/search?page=" . $page . "&from-date=" . $date . "&to-date=" . $date. "&api-key=" . $key;
            if($category != ""){
                $url = $url . "&q=" . $category;
            }
            $json = json_decode(file_get_contents($url), true);
            return response()->json([
                'success' => true,   
                'data'   => $json['response']['results'],
                'total'   => $json['response']['total']
            ], 200);
        }else if  ($source == 'tnt') {
            $url = env("TNT_URL", "");
            $key = env("TNT_KEY", "");

            $url = $url . "?page=" . $page . "&begin_date=" . str_replace("-","",$date) . "&end_date=" . str_replace("-","",$date). "&api-key=" . $key;
            if($category != ""){
                $url = $url . "&q=" . $category;
            }
            $json = json_decode(file_get_contents($url), true);
            return response()->json([
                'success' => true,   
                'data'   => $json['response']['docs'],
                'total'   => $json['response']['meta']['hits']
            ], 200);
        }else if  ($source == 'newsapi') {
            $url = env("NEWSAPI_URL", "");
            $key = env("NEWSAPI_KEY", "");
            $pageSize = 10;
            $url = $url . "/everything?page=" . $page . "&pageSize=" . $pageSize . "&from=" . $date . "&to=" . $date . "&apiKey=" . $key;
            if($category != ""){
                $url = $url . "&q=" . $category;
            }
            $json = json_decode(file_get_contents($url), true);
            return response()->json([
                'success' => true,   
                'data'   => $json['articles'],
                'total'   => $json['totalResults']
            ], 200);
        }
       

    }
}