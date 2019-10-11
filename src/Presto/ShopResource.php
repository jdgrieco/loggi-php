<?php

namespace Jdgrieco\LoggiPHP\Presto;

use Jdgrieco\LoggiPHP\Exceptions\ConfigurationException;
use Jdgrieco\LoggiPHP\LoggiClient;
use Jdgrieco\LoggiPHP\Contracts\ClientGraphQLContract;
use Jdgrieco\LoggiPHP\Presto\Entities\ShopEntity;
use Jdgrieco\LoggiPHP\Query;

class ShopResource
{
    /**
     * @var ClientGraphQLContract
     */
    private $client;

    /**
     * ShopResource constructor.
     *
     * @param ClientGraphQLContract|null $client
     *
     * @throws ConfigurationException
     */
    function __construct(ClientGraphQLContract $client = null)
    {
        if($client === null)
            $client = new LoggiClient();

        $this->client = $client;
    }

    /**
     * Get all Shops
     *
     * @return array
     */
    public function all()
    {
        $result = [];

        $query = new Query([
            'allShops' => [
                'edges' => [
                    'node' => ['id', 'name', 'pk']
                ]
            ]
        ]);

        $response = $this->client->executeQuery($query);

        foreach ($response['allShops']['edges'] as $row) {

            $shop = new ShopEntity();
            $shop->id = $row['node']['id'];
            $shop->pk = $row['node']['pk'];
            $shop->name = $row['node']['name'];

            $result[] = $shop;
        }

        return $result;
    }
}