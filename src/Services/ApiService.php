<?php

namespace Med\Services;

use GuzzleHttp\Client as GuzzleClient;

class ApiService
{

    /**
     * ApiService constructor.
     *
     * @param null|string $url
     */
    public function __construct($url = null)
    {
        $this->client = new GuzzleClient([
            'base_uri' => $url ?: getenv('DOSE_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
            ],
        ]);
    }

    /**
     * Make a request to the api.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     *
     * @return mixed
     */
    public function makeRequest($method, $endpoint, array $data = [])
    {
        $response = $this->client->request($method, $endpoint, $data);

        return json_decode($response->getBody()->getContents(), true);
    }
}
