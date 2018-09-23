<?php

namespace Base\Helpers;

class Session{

    public static function flashMessage($status, $message){
        self::add('status', $status);
        self::add('message', $message);
    }

    public static function renderMessage(){
        if(self::get('status') && self::get('message')){
            $html = '<div class="alert alert-dismissable alert-'.self::get('status').'" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.self::get('message').'</div>';
            echo $html;
            self::remove('status');
            self::remove('message');
        }
    }

    public static function add($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function remove($key){
        unset ($_SESSION[$key]);
    }

    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return;
    }


}
