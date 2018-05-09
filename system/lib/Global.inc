<?php
/**
 * Created by PhpStorm.
 * User: llmmoe
 * Date: 2018/2/28
 * Time: 17:17
 */

function base_url() {
    return BASE_URL;
}
function viewUrl() {
    return BASE_URL.'application/view/';
}
function set($data) {
    if(isset($data) and !empty($data))
        return true;
    return false;
}
function update_path() {
    return UPLOAD_PATH;
}

function _print($data) {
    if(Empty($data)) return;
    echo $data;
}