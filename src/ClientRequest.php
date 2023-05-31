<?php

namespace Marketbiz\IPaymuLaravel;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Marketbiz\IPaymuLaravel\Exceptions\Unauthorized;

class ClientRequest
{
    function genSignature($data)
    {
        $body = json_encode($data, JSON_UNESCAPED_SLASHES);
        $requestBody  = strtolower(hash('sha256', $body));
        $secret       = config('ipaymu.api_key');
        $va           = config('ipaymu.virtual_account');
        $stringToSign = 'POST:' . $va . ':' . $requestBody . ':' . $secret;
        $signature    = hash_hmac('sha256', $stringToSign, $secret);

        return $signature;
    }

    public function request($config, $params)
    {
        $options = [
            'multipart' => [
                [
                'name' => 'product[]',
                'contents' => 'Baju'
                ],
                [
                'name' => 'qty[]',
                'contents' => '1'
                ],
                [
                'name' => 'price[]',
                'contents' => '10000'
                ],
                [
                'name' => 'description[]',
                'contents' => 'Baju1'
                ],
                [
                'name' => 'returnUrl',
                'contents' => 'https://ipaymu.com/return'
                ],
                [
                'name' => 'notifyUrl',
                'contents' => 'https://ipaymu.com/notify'
                ],
                [
                'name' => 'cancelUrl',
                'contents' => 'https://ipaymu.com/cancel'
                ],
                [
                'name' => 'referenceId',
                'contents' => 'ID1234'
                ],
                [
                'name' => 'weight[]',
                'contents' => '1'
                ],
                [
                'name' => 'dimension[]',
                'contents' => '1:1:1'
                ],
                [
                'name' => 'buyerName',
                'contents' => 'putu'
                ],
                [
                'name' => 'buyerEmail',
                'contents' => 'putu@mail.com'
                ],
                [
                'name' => 'buyerPhone',
                'contents' => '08123456789'
                ],
                [
                'name' => 'pickupArea',
                'contents' => '80117'
                ],
                [
                'name' => 'pickupAddress',
                'contents' => 'Jakarta'
                ]
            ]
        ];

        $signature = $this->genSignature($options);
        $timestamp = Date('YmdHis');
        $headers = array(
            'Content-Type: application/json',
            'va: ' . config('ipaymu.virtual_account'),
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        $client = new Client();

        $request = new Request('POST', $config, $headers);
        $res = $client->sendAsync($request, $options)->wait();
        echo $res->getBody();

    }

    /**
     * @param $response
     *
     * @throws Unauthorized
     *
     * @return mixed
     */
    private function responseHandler($response)
    {
        switch (@$response['Status']) {
            case '401':
                throw new Unauthorized();
            default:
                return $response;
        }
    }
}