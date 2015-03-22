    <?php

/**
    Google plugin for the PHP Fat-Free Framework

    The contents of this file are subject to the terms of the GNU General
    Public License Version 3.0. You may not use this file except in
    compliance with the license. Any of the license terms and conditions
    can be waived if you get permission from the copyright holder.

    Copyright (c) 2009-2011 F3::Factory
    Bong Cosca <bong.cosca@yahoo.com>

        @package Google
        @version 2.0.0
**/

//! Collection of Google API adaptors
class Yandex extends Base {
  
    /**
        Generate static map using Google Maps API
            @param $center string
            @param $zoom integer
            @param $size string
            @param $type string
            @param $format string
            @param $language string
            @param $markers array
            @public
    **/
    static function
        staticmap(   
            $ll='29.717833,60.013785',
            $zoom='15',
            $l='map',
            $size='450,150',
            $key='ADN6SE4BAAAAbuYrDwIA8n3YPS5mkHuJYhJ65uHMZayQ25kAAAAAAAAAAACD5RiZKirkeJ6E5PdBJyHMVrKctw==',
            $pt=''
            ) {
                
        $map= Web::http(
            'GET http://static-maps.yandex.ru/1.x/',
            http_build_query(
                    array(
                        'll'=>$ll,
                        'z'=>$zoom,
                        'l'=>$l,
                        'size'=>$size,
                        'key'=>$key,
                        'pt'=>$pt,
                    )
            )
        );
        return $map;
    }
  
}
