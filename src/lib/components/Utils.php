<?php

namespace barephrame\src\lib\components;

class Utils {
    static function URL(string $url):array {
        $p_url = explode('/', $url);
        $f_url = [];
        foreach($p_url as $k) {
            $k = trim($k);
            if($k == '') continue;
            $f_url[] = $k;
        }

        return $f_url;
    }
    static function INI(string $path):array|bool {
        return parse_ini_file($path, true);
    }
}