<?php
namespace AppBundle\Model;

use GuzzleHttp\Client;

abstract class BaseResource
{
    protected static $pathName;
    public static function cget(Client $client, int $offset = 0, int $pageSize = 25) {
        $response = $client->get(static::$pathName,[
            'query' => [
                'offset' => $offset,
                'limit' => $pageSize
        ]]);
        return json_decode($response->getBody(), true);
    }
    
    public static function get(Client $client, string $id) {
        $response = $client->get(static::$pathName.'/'.$id);
        return json_decode($response->getBody(), true);
    }
    
    public static function rget(Client $client, string $id, string $verbose = normal) {
        $response = $client->get(static::$pathName.'/'.$id.'/'.'roles', [
            'query' => [
                'verbose' => $verbose          
        ]]);
        return json_decode($response->getBody(), true);
    }
    
    public static function managersget(Client $client, string $id) {
        $response = $client->get(static::$pathName.'/'.$id.'/'.'managers');
        return json_decode($response->getBody(), true);
    }
     
    public static function membersget(Client $client, string $id) {
        $response = $client->get(static::$pathName.'/'.$id.'/'.'members');
        return json_decode($response->getBody(), true);
    } 
    
}