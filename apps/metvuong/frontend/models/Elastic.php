<?php
/**
 * https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_overview.html
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 11/17/2015
 * Time: 1:56 PM
 */

namespace frontend\models;


use Elasticsearch\ClientBuilder;

class Elastic
{
    protected $client = null;
    public function __construct(){
        $this->connect();
    }

    public function connect(){
        $hosts = [
            '127.0.0.1:9200',         // IP + Port
            '127.0.0.1',              // Just IP
            'local.yii2demo:9200', // Domain + Port
            'local.yii2demo',     // Just Domain
        ];
        if(empty($this->client)){
            $this->client = ClientBuilder::create()           // Instantiate a new ClientBuilder
            ->setHosts($hosts)      // Set the hosts
            ->build();              // Build the client object
        }
        return $this->client;
    }

    public function index(){
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id',
            'body' => [ 'testField' => 'abc']
        ];
        // Document will be indexed to my_index/my_type/my_id
        $response = $this->client->index($params);
        return $response;
    }

    public function delete(){
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id'
        ];
        // Delete doc at /my_index/my_type/my_id
        $response = $this->client->delete($params);
        return $response;
    }

    public function update(){
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'id' => 'my_id',
            'body' => [
                'doc' => [
                    'new_field' => 'abc'
                ]
            ]
        ];
        // Update doc at /my_index/my_type/my_id
        $response = $this->client->update($params);
        return $response;
    }

    public function search(){
        $params = [
            'index' => 'my_index',
            'type' => 'my_type',
            'body' => [
                'query' => [
                    'match' => [
                        'testField' => 'abc'
                    ]
                ]
            ]
        ];
        $results = $this->client->search($params);
        return $results;
    }

}