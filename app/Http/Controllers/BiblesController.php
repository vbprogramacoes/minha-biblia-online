<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrismicApi;

class BiblesController extends Controller
{
    private $api;
    public function __construct(){
        //$this->api = 'http://localhost:8002';
        $this->api = 'https://api.novabiblia.com.br';
    }

    // Home
    public function home(Request $request){
        $api = $this->api.'/versions';
        $data = $this->getData($api);
        
        $title = 'Bíblia Online';
        $description = 'Leia a Bíblia nas principais versões das Escrituras Sagradas. Compare vários versículos da Bíblia de uma só vez.';
        $canonical = url('/biblia');
        $img = url('/images/favicon.jpg');

        $breadcrumb = [
            'Home' => '/',
            'Bíblia Online' => '/biblia'
        ];

        $schema = [
            schema_logo(),
            schema_breadcrumb($breadcrumb),
            schema_webpage($title, $description, $canonical)
        ];

        $page = $this->meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema);

        return view('bible.index', compact('page', 'data'));
    }

    // Version
    public function version(Request $request, $version){
        $this->validateVersion($version);

        $api = $this->api.'/books/'.$version;
        $data = $this->getData($api);

        $title = 'Bíblia '.$data->version->abbr.' - '.$data->version->name;
        $title = $version === 'vc' ? 'Bíblia Católica' : $title;

        $description = 'Leia '.$data->version->abbr.' - Bíblia '.$data->version->name.' na versão {versão} online. Compare também com outras versões da Bíblia';
        $canonical = url(create_path('/biblia/'.$data->version->abbr));
        $img = url('/images/favicon.jpg');

        $breadcrumb = [
            'Home' => '/',
            'Bíblia Online' => $canonical
        ];

        $schema = [
            schema_logo(),
            schema_breadcrumb($breadcrumb),
            schema_webpage($title, $description, $canonical)
        ];

        $page = $this->meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema);

        return view('bible.version', compact('page', 'data'));
    }

    // Book
    public function book(Request $request, $version, $book){
        $this->validateVersion($version);

        $api = $this->api.'/books/'.$version.'/'.$book;
        $data = $this->getData($api);

        $api = $this->api.'/books/'.$version;
        $booksData = file_get_contents($api);
        $booksData = json_decode($booksData);
        $data->books = $booksData->books;

        $title = $data->book->name.' - Bíblia '.$data->version->abbr.' - '.$data->version->name;
        $title = $version === 'vc' ? 'Bíblia Católica - '.$data->book->name : $title;

        $description = 'Leia o livro de '.$title.' online.  Veja e compare cada versículo de '.$title.' com as principais versões da Bíblia. Veja também a nossa Bíblia em Áudio.';
        $canonical = create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name);
        $img = url('/images/favicon.jpg');

        $breadcrumb = [
            'Home' => '/',
            'Bíblia Online' => '/biblia',
            $data->version->abbr => create_path('/biblia/'.$data->version->abbr),
            $data->book->name => create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name)
        ];

        $schema = [
            schema_logo(),
            schema_breadcrumb($breadcrumb),
            schema_webpage($title, $description, $canonical)
        ];

        $page = $this->meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema);

        return view('bible.book', compact('page', 'data'));
    }

    // Chapter
    public function chapter(Request $request, $version, $book, $chapter){
        $this->validateVersion($version);

        $api = $this->api.'/verses/'.$version.'/'.$book.'/'.$chapter;
        $data = $this->getData($api);

        $api = $this->api.'/books/'.$version;
        $booksData = file_get_contents($api);
        $booksData = json_decode($booksData);
        $data->books = $booksData->books;

        $title = $data->book->name.' '.$data->chapter->num.' - Bíblia '.$data->version->abbr.' - '.$data->version->name;
        $title = $version === 'vc' ? 'Bíblia Católica - '.$data->book->name.' '.$data->chapter->num : $title;

        $description = 'Leia o capítulo '.$data->chapter->num.' de '.$data->book->name;
        $canonical = url(create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name.'/'.$data->chapter->num));
        $img = url('/images/favicon.jpg');
        $audio = create_path('https://audio.novabiblia.com.br/v1/'.$data->version->abbr.'/'.$data->book->name.'/'.$data->chapter->num.'.mp3');

        $breadcrumb = [
            'Home' => '/',
            'Bíblia Online' => '/biblia',
            $data->version->abbr => create_path('/biblia/'.$data->version->abbr),
            $data->book->name => create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name),
            $data->chapter->num => $canonical
        ];

        $schema = [
            schema_logo(),
            schema_breadcrumb($breadcrumb),
            schema_webpage($title, $description, $canonical),
            schema_audio($title, $description, $audio)
        ];

        $page = $this->meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema);
        $page->share = create_share_urls($page->canonical, $page->title, $page->meta_description, $page->img);

        return view('bible.chapter', compact('page', 'data'));
    }

    // Verse
    public function verse(Request $request, $version, $book, $chapter, $verse){
        $this->validateVersion($version);

        $api = $this->api.'/verses/'.$version.'/'.$book.'/'.$chapter.'/'.$verse;
        $data = $this->getData($api);

        $api = $this->api.'/books/'.$version;
        $booksData = file_get_contents($api);
        $booksData = json_decode($booksData);
        $data->books = $booksData->books;

        $title = $data->book->name.' '.$data->chapter->num.':'.$data->verse->num.' - Bíblia '.$data->version->abbr.' - '.$data->version->name;
        $title = $version === 'vc' ? 'Bíblia Católica - '.$data->book->name.' '.$data->chapter->num.':'.$data->verse->num : $title;

        $description = $data->book->name.' '.$data->chapter->num.':'.$data->verse->num.' - '.$data->version->abbr.' - Bíblia '.$data->version->name;
        $canonical = url(create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name.'/'.$data->chapter->num.'/'.$data->verse->num));
        $img = url(create_path('/images/verses/v1/'.$data->version->abbr.'/'.$data->book->name.'/'.$data->chapter->num.'/'.$data->verse->num.'.jpg'));

        $breadcrumb = [
            'Home' => '/',
            'Bíblia Online' => '/biblia',
            $data->version->abbr => create_path('/biblia/'.$data->version->abbr),
            $data->book->name => create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name),
            $data->chapter->num => create_path('/biblia/'.$data->version->abbr.'/'.$data->book->name.'/'.$data->chapter->num),
            $data->verse->num.' ' => $canonical
        ];

        $schema = [
            schema_logo(),
            schema_breadcrumb($breadcrumb),
            schema_webpage($title, $description, $canonical)
        ];

        $page = $this->meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema);
        $page->share = create_share_urls($page->canonical, $page->title, $page->meta_description, $page->img);

        return view('bible.verse', compact('page', 'data'));
    }


    private function getData($api)
    {
        $headers = get_headers($api);
        if($headers[0] == 'HTTP/1.1 500 Internal Server Error'){
            abort('404');
        }

        $data = file_get_contents($api);
        $data = json_decode($data);

        return $data;
    }

    private function validateVersion($version)
    {
        if($version === 'kjv'){
            header("Location: /biblia/kji", 301);
            exit;
        }
        if(in_array($version, ['ntlh', 'vfl'])){
            abort('404');
        }
    }

    private function meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema)
    {
        return (object) [
            'canonical' => $canonical,
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'img' => $img,
            'header_menu' => PrismicApi::getHeaderMenu($request),
            'breadcrumb' => $breadcrumb,
            'schema' => $schema
        ];
    }
}
