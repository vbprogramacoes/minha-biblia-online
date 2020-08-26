<?php

namespace App\Http\Controllers;
use App\PrismicApi;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $api;
    public function __construct(){
        $this->api = 'https://api.novabiblia.com.br';
    }

    public function show(Request $request){
        $document = $request->attributes->get('api')->getByUID('home', 'minha-biblia-online');
        $data = $document->data;
        $api = $this->api.'/books/acf';
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $apiData = file_get_contents($api, false, stream_context_create($arrContextOptions));
        $apiData = json_decode($apiData);
        $data->books = $apiData->books;
        $data->version = $apiData->version;

        $title = $data->page_title;
        $description = $data->meta_description;
        $canonical =url('/');
        $img = url('/images/favicon.jpg');
        
        $schema = [
            schema_logo(),
            schema_webpage($title, $description, $canonical)
        ];
        $page = $this->meta($request, $title, $description, $canonical, $img, $schema);

        return view('home', compact('page', 'data'));
    }

    private function meta($request, $title, $description, $canonical, $img, $schema)
    {
        return (object) [
            'canonical' => $canonical,
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'img' => $img,
            'header_menu' => PrismicApi::getHeaderMenu($request),
            'schema' => $schema
        ];
    }
}
