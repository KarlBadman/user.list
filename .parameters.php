<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "UL_COUNT_PAGE"  =>  Array(
            'NAME' => GetMessage('UL_COUNT_PAGE'),
            'TYPE' => 'STRING',
            "DEFAULT"=>10,
            'PARENT' => 'BASE',
        ),
        "CACHE_TIME"  =>  Array(),
        "AJAX_MODE" => array(),
    )
);