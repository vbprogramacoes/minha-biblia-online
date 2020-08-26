<?php
use Spatie\SchemaOrg\Schema;
use App\PrismicApi;
use Prismic\Api;
use Prismic\Predicates;

function create_path($path)
{
    $map = ['á' => 'a','à' => 'a','ã' => 'a','â' => 'a','é' => 'e','ê' => 'e','í' => 'i','ó' => 'o','ô' => 'o','õ' => 'o','ú' => 'u','ü' => 'u','ç' => 'c','Á' => 'A','À' => 'A','Ã' => 'A','Â' => 'A','É' => 'E','Ê' => 'E','Í' => 'I','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ú' => 'U','Ü' => 'U','Ç' => 'C', ' ' => '-'];

    $path = strtolower(strtr($path, $map));
    return $path;
}

function create_share_urls($url, $title, $description, $img)
{
    # Share urls
    $facebook = 'https://www.facebook.com/sharer/sharer.php?u='.$url;
    $whatsapp = 'whatsapp://send?text='.$url;
    $linkedin = 'https://linkedin.com/shareArticle?url='.$url.'&title='.$title;
    $pinterest = 'https://pinterest.com/pin/create/bookmarklet?media='.$img.'&url='.$url.'&description='.$title;
    $twitter = 'https://twitter.com/share?url='.$url.'&text='.$title;
    $email = 'mailto:?subject='.$title.'&body='.$title.': \n'.$url;

    $share = (object) [
        'facebook' => $facebook,
        'whatsapp' => $whatsapp,
        'linkedin' => $linkedin,
        'pinterest' => $pinterest,
        'twitter' => $twitter,
        'email' => $email,
    ];

    return $share;
}

function get_verses($data){
    $verses = [];
    foreach($data->body as $slice){
        if(in_array($slice->slice_type, ['verse', 'verse1', 'verse2', 'verse3'])){

            if(!empty($slice->primary->group_verses)){
                $version = null;
                $book = null;
                $chapter = null;
                $content = '';
                foreach($slice->items as $item){
                    if(!$version){
                        $version = $item->verse->version;
                    }
                    if(!$book){
                        $book = $item->verse->book;
                    }
                    if(!$chapter){
                        $chapter = $item->verse->chapter;
                    }
                    $content = $content.$item->verse->content.' ';
                }

                $firstVerse = $slice->items[0];
                if(count($slice->items) === 1){
                    $title = $book.' '.$chapter.':'.$firstVerse->verse->verse;
                    $uri = $firstVerse->verse->uri;
                }else{
                    $lastVerse = end($slice->items);
                    $title = $book.' '.$chapter.':'.$firstVerse->verse->verse.'-'.$lastVerse->verse->verse;

                    $uri = explode('/', $firstVerse->verse->uri);
                    array_pop($uri);
                    $uri = implode('/', $uri);
                }




                array_push($verses, (object)[
                    'verse' => (object)[
                        'version' => $version,
                        'title' => $title,
                        'content' => $content,
                        'thumbnail' => null,
                        'uri' => $uri
                    ]
                ]);
            }else{
                array_push($verses, ...$slice->items);
            }
        }
    }
    return $verses;
}

function meta($request, $title, $description, $canonical, $img, $breadcrumb, $schema)
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

function schema_breadcrumb($array)
{
    $breacrumbSchema = [];
    $i=1;
    foreach($array as $key => $value){
        $breacrumbSchema[] = Schema::ListItem()
            ->position($i)
            ->name($key)
            ->item(url($value));
        $i++;
    }
    $breadcrumbList = Schema::BreadcrumbList()
        ->itemListElement($breacrumbSchema)
        ->toScript();

    return $breadcrumbList;
}

function schema_logo(){
    return Schema::organization()
        ->name('Nova Bíblia')
        ->url( url('/') )
        ->logo( url('/images/favicon-lg.png') )
        ->contactPoint(
            Schema::ContactPoint()
                ->telephone('+55 11 93247-4692')
                ->contactType('technical support')
        )
        ->toScript();
}

function schema_webpage($title, $description, $url){
    return Schema::webPage()
            ->url($url)
            ->name($title)
            ->description($description)
            ->potentialAction(
                Schema::searchAction()
                    ->target(url('/busca'))
                    ->query('q=search_term_string')
            )->toScript();
}

function schema_audio($title, $description, $url){
    return Schema::AudioObject()
            ->contentUrl($url)
            ->encodingFormat('audio/mpeg')
            ->description($description)
            ->name($title)->toScript();
}

function check_redirect($uri){
    $document = Api::get('https://biblia.prismic.io/api/v2')->query(
        [Predicates::at('my.redirect.origin', $uri)],
        ['pageSize' => 1]
    );
    if($document->results_size){
        header("Location: ".$document->results[0]->data->destiny, 301);
        exit;
    }
}
?>