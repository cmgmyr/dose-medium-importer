<?php

namespace Med\Services;

use GuzzleHttp\Client as GuzzleClient;

class ApiService
{
    /**
     * ApiService constructor.
     */
    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => getenv('API_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
//                'User-Agent' => getenv('API_USER_AGENT'),
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
