<?php

/**
 * Created by PhpStorm.
 * User: fruit
 * Date: 1/24/2019
 * Time: 12:20 PM
 */

const PUBLIC_DIRECTORY = '..\\public\\';
const CHARACTERS = '0123456789qwertyuiopasdfghjklzxcvbnm';
const CHARACTERS_LENGTH = 36;

function generateRandomString($len) {
    $result = '';
    for ($i = 0; $i < $len; $i++) {
        $result .= CHARACTERS[random_int(0, CHARACTERS_LENGTH - 1)];
    }
    return $result;
}

function generateFileName($dir, $ext, $len=16) {
    do {
        $name = generateRandomString($len).'.'.$ext;
    } while(file_exists(PUBLIC_DIRECTORY."$dir\\$name"));
    return "$dir/$name";
}

function convertLoginToEmail($login) {
    return $login.'@gmail.com';
}