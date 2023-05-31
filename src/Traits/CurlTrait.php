<?php

namespace Marketbiz\IPaymuLaravel\Traits;

use Marketbiz\IPaymuLaravel\Exceptions\Unauthorized;

trait CurlTrait
{

    /**
     * @param $config
     * @param $params
     *
     * @throws Unauthorized
     *
     * @return mixed
     */
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
        $signature = $this->genSignature($params);
        $timestamp = Date('YmdHis');
        $headers = array(
            'Content-Type: application/json',
            'va: ' . config('ipaymu.virtual_account'),
            'signature: ' . $signature,
            'timestamp: ' . $timestamp
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params, JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $request = curl_exec($ch);

        if ($request === false) {
            echo 'Curl Error: ' . curl_error($ch);
        } else {
            $result = json_decode($request, true);

            return $result;
        }

        curl_close($ch);
        exit;
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