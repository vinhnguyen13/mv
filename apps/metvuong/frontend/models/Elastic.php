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
use yii\base\Exception;

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
            'index' => 'listing',
            'type' => 'store',
            'id' => '28',
            'body' => [
                'doc' => [
                    'title' => 'Ứng dụng công nghệ Holongram vào trình diễn dự án tại Việt Nam'
                ]
            ]
        ];
        // Update doc at /my_index/my_type/my_id
        $response = $this->client->update($params);
        return $response;
    }

    public function search(){
        $params = [
            'index' => 'listing',
            'type' => 'store',
            'body' => [
                'query' => [
                    /*'match' => [
                        'title' => 'long'
                    ]*/
                    /*'bool' => [
                        'must' => [
                            [ 'match' => [ 'title' => 'long' ] ],
                        ]
                    ]*/
                    /*'filtered' => [
                        'filter' => [
                            'term' => [ 'title' => 'long' ]
                        ],
                        'query' => [
                            'match' => [ 'title' => 'long' ]
                        ]
                    ]*/
                    /*'filtered' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'long',
                                "default_operator" => "OR",
                                "fields" => ["title"]
                            ]
                        ]
                    ],
                    'regexp' => [
                        'title' => [
                            'value' => '.*long.*'
                        ]
                    ],*/
                    /*'filtered' => [
                        'query' => [
                            'query_string' => [
                                'query' => 'Ứng dụng công nghệ', // words want to find
                                "default_operator" => "OR", // OR/AND query words
                                "fields" => ["title", "content"], // select fields
//                                "default_field" => "_all", // all filed
                            ]
                        ]
                    ],*/
                    'regexp' => [
                        'title' => [
                            'value' => '.*long.*'
                        ]
                    ]
                ]
            ]
        ];
        $results = $this->client->search($params);
        return $results;
    }

    public function findOne($index, $type, $id, $function = 'getSource'){
        /**
         * must have 2 field
         */
        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
        ];
        // Document will be indexed to my_index/my_type/my_id
        try{

            if($this->client->transport->getConnection()->ping()){
                $chk = $this->client->exists($params);
                if(!empty($chk)){
        //            $result = $client->get($params);
                    $result = $this->client->$function($params);
                    return $result;
                }
                return false;
            }
        }catch (Exception $e){

        }
    }


    public function userData(){
        try{
            $this->connect();
            if($this->client->transport->getConnection()->ping()){
                $params = [
                    'index' => 'users',
                    'type' => 'store',
                    'id' => '28',
                    'body' => [
                        'doc' => [
                            'title' => 'Ứng dụng công nghệ Holongram vào trình diễn dự án tại Việt Nam'
                        ]
                    ]
                ];
                $this->client->index($params);
            }
        }catch (Exception $e){

        }
    }

}