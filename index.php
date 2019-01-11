<?php

namespace scrappyfun;

require "vendor/autoload.php";

use Goutte\Client;

$client = new Client();

$main_site = "https://1cak.com";
$path = "/trending";

$crawler = getDataCrawler($client, $main_site, $path);

$page = 10;

if(isset($_GET['count'])){
    $page = $_GET['count'];
}

$count_page = 1;

class trending {}

$object = new trending();

$arr = [];

printResult($client, $main_site, $count_page, $page, $crawler);


function printResult($client, $main_site, $count_page, $page, $crawler)
{
    $crawler->filter('td > a > img')->each(function($node) use ($count_page){
        appendImage($node->attr('src'));
    });
    
    $crawler->filter('a')->each(function($node) use($count_page, $page, $main_site, $client) {
         if($node->attr('id')=='next_page_link'){
            $next_page = $node->attr('href');
            if($count_page<$page){
                $crawler = getDataCrawler($client, $main_site, $next_page);
                $count_page++;
                printResult($client, $main_site, $count_page, $page, $crawler);
            }
         }
    });

}

function getDataCrawler($client, $main_site, $path)
{
    $object = $client->request('GET', $main_site.$path);

    return $object;
}


function appendImage($image)
{
    global $arr;
    array_push($arr, $image);
}

$object->success = true;
$object->data = $arr;

print json_encode($object);