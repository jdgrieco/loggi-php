<?php

namespace Jdgrieco\LoggiPHP;

use Jdgrieco\LoggiPHP\Contracts\ClientGraphQLContract;
use Jdgrieco\LoggiPHP\Exceptions\ConfigurationException;
use Jdgrieco\LoggiPHP\Exceptions\ResponseException;

class LoggiClient implements ClientGraphQLContract
{
    const SANDBOX    = 'https://staging.loggi.com/graphql';
    const PRODUCTION = 'https://www.loggi.com/graphql';

    /**
     * Email
     *
     * @var string
     */
    private $email;

    /**
     * Key
     *
     * @var string
     */
    private $key;

    /**
     * URL
     *
     * @var string
     */
    private $url;

    /**
     * LoggiClient constructor.
     *
     * @param string $environment
     * @param null   $email
     * @param null   $key
     *
     * @throws ConfigurationException
     */
    function __construct($environment = null, $email = null, $key = null)
    {
        /*
         * Setting URL
         */
        $this->url = $environment !== null ? $environment : getenv('LOGGI_API_URL');

        if (!$this->url) {
            $this->url = LoggiClient::PRODUCTION;
        }

        /*
         * Setting others params
         */
        $this->email = $email !== null ? $email : getenv('LOGGI_API_EMAIL');
        $this->key = $key !== null ? $key : getenv('LOGGI_API_KEY');

        /*
         * Check
         */
        if (!$this->email) {
            throw new ConfigurationException('Enter a email or set the environment variable LOGGI_API_EMAIL');
        }

        if (!$this->key) {
            throw new ConfigurationException('Enter a key or set the environment variable LOGGI_API_KEY');
        }

        if (!in_array($this->url, [LoggiClient::PRODUCTION, LoggiClient::SANDBOX], true)) {
            throw new ConfigurationException('Set a valid environment');
        }
    }

    /**
     * Execute query
     *
     * @param Query $query
     *
     * @return array
     * @throws ResponseException
     */
    public function executeQuery(Query $query)
    {
        return $this->request(['query' => (string)$query]);
    }

    /**
     * Execute mutation
     *
     * @param Mutation $mutation
     *
     * @return array
     * @throws ResponseException
     */
    public function executeMutation(Mutation $mutation)
    {
        return $this->request(['query' => (string)$mutation]);
    }

    /**
     * @param $data
     *
     * @return mixed
     * @throws ResponseException
     */
    private function request($data)
    {
        $curl = $this->createCurl();

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);

        $result = json_decode($response, true);

        curl_close($curl);

        if (isset($result['errors'])) {
            throw new ResponseException($result['errors'][0]['message']);
        }

        return $result['data'];
    }

    /**
     * @return resource
     */
    private function createCurl()
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl, [
                     CURLOPT_URL            => $this->url,
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_POST           => true,
                     CURLOPT_HTTPHEADER     => [
                         'Content-Type: application/json;charset=UTF-8',
                         'Authorization: ApiKey ' . $this->email . ':' . $this->key,
                     ],
                 ]
        );

        return $curl;
    }
}