<?php

namespace App;

use Illuminate\Http\Request;

class PrismicApi
{
    public static function getHeaderMenu($request){
        $document = $request->attributes->get('api')->getSingle('header_menu');

        if (!$document) {
            return False;
        }else{
            return $document->data;
        }
    }
}
