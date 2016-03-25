<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 10/22/2015
 * Time: 8:29 AM
 */

namespace api\modules\v1;


use OAuth2\RequestInterface;
use OAuth2\Response;
use OAuth2\ResponseInterface;

class Server extends \OAuth2\Server
{
    public function handleTokenRequest(RequestInterface $request, ResponseInterface $response = null)
    {
        $this->response = is_null($response) ? new Response() : $response;
        $this->getTokenController()->handleTokenRequest($request, $this->response);
        return $this->response;
    }

    public function handleRevokeRequest(RequestInterface $request, ResponseInterface $response = null)
    {
        $this->response = is_null($response) ? new Response() : $response;
        $this->getTokenController()->handleRevokeRequest($request, $this->response);
        return $this->response;
    }
}