<?php

class ApiUtility
{
    // .hack//SIGN
    public static function objectify($data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::objectify($value);
            }
        }
        
        return (object) $data;
    }
    
    public static function getTokens($calls) {
        $tokens = NULL;
        
        self::_flatten_array(Hash::extract($calls, '{n}.tokens'), $tokens);
        
        return array_fill_keys(array_keys($tokens), '');
    }
    
    private static function _flatten_array($array, &$result) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                self::_flatten_array($value, $result);
            } else {
                $result[$key] = $value;
            }
        }
    }
}