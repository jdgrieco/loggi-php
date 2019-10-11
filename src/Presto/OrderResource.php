<?php

namespace Jdgrieco\LoggiPHP\Presto;

use Jdgrieco\LoggiPHP\Contracts\ClientGraphQLContract;
use Jdgrieco\LoggiPHP\Exceptions\ResponseException;
use Jdgrieco\LoggiPHP\LoggiClient;
use Jdgrieco\LoggiPHP\Presto\Entities\EstimateEntity;
use Jdgrieco\LoggiPHP\Presto\Entities\LocationEntity;
use Jdgrieco\LoggiPHP\Presto\Entities\ShopEntity;
use Jdgrieco\LoggiPHP\Query;

class OrderResource
{
    /**
     * @var ClientGraphQLContract
     */
    private $client;

    /**
     * OrderResource constructor.
     *
     * @param ClientGraphQLContract|null $client
     *
     * @throws \Jdgrieco\LoggiPHP\Exceptions\ConfigurationException
     */
    function __construct(ClientGraphQLContract $client = null)
    {
        if ($client === null) {
            $client = new LoggiClient();
        }

        $this->client = $client;
    }

    /**
     * Estimate Cost
     *
     * @return EstimateEntity
     * @throws ResponseException
     */
    public function estimation(ShopEntity $from, LocationEntity $to)
    {
        $query = new Query(
            [
                'estimate(shopId: ' . $from->id . ', packagesDestination: [{lat: ' . $to->latitude . ', lng: ' . $to->longitude . '}])' => [
                    'packages' => [
                        'error',
                    ],
                    'normal'   => [
                        'cost',
                        'distance',
                        'eta',
                    ],
                ],
            ]
        );

        $response = $this->client->executeQuery($query);

        if (!isset($response['estimate'])) {
            throw new ResponseException('Estimate not found.');
        }

        if (isset($response['estimate']['packages'][0]['error'])) {
            throw new ResponseException($response['estimate']['packages'][0]['error']);
        }

        $estimate = new EstimateEntity();

        $estimate->price = $response['estimate']['normal']['cost'];

        return $estimate;
    }
}