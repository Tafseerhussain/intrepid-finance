<?php

abstract class Mx_Api
{
    use Tell_Trait_Property;

    const ENDPOINT_TEST = 'https://int-api.mx.com';

    const ENDPOINT_LIVE = 'https://api.mx.com';

    protected $config = [
        'api_key'   => NULL,
        'client_id' => NULL,
        'test_mode' => TRUE,
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, Tell::config('mx'), $config);
    }

    protected function getEndpoint()
        : string
    {
        return $this->config['test_mode'] ? static::ENDPOINT_TEST : static::ENDPOINT_LIVE;
    }

    protected function getRequest()
        : Tell_Client_Request
    {
        $client = new Tell_Client_Request();

        $client->headers([
            'Accept'        => 'application/vnd.mx.api.v1+json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . $this->getAuth(),
        ]);

        return $client;
    }

    private function getAuth()
        : string
    {
        return base64_encode($this->config['client_id'] . ':' . $this->config['api_key']);
    }
}
