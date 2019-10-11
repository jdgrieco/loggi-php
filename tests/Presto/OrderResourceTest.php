<?php

namespace Jdgrieco\LoggiPHP\Tests\Presto;

use Jdgrieco\LoggiPHP\Contracts\ClientGraphQLContract;
use Jdgrieco\LoggiPHP\Presto\Entities\EstimateEntity;
use Jdgrieco\LoggiPHP\Presto\Entities\LocationEntity;
use Jdgrieco\LoggiPHP\Presto\Entities\ShopEntity;
use Jdgrieco\LoggiPHP\Presto\OrderResource;
use PHPUnit_Framework_TestCase;

class OrderResourceTest extends PHPUnit_Framework_TestCase
{
    public function testEstimation()
    {
        $clientGraphQL = $this->getMockBuilder(ClientGraphQLContract::class)->getMock();

        $clientGraphQL->method('executeQuery')->willReturn([
            'estimate' => [
                'normal' => [
                    'cost' => 9.9
                ]
            ]
        ]);

        $orderResource = new OrderResource($clientGraphQL);

        $from = new ShopEntity();
        $from->id = 1234;

        $to = new LocationEntity();
        $to->latitude = -19.8579253;
        $to->longitude = -43.94522380000001;

        $result = $orderResource->estimation($from, $to);

        $this->assertInstanceOf(EstimateEntity::class, $result);
        $this->assertEquals(9.9, $result->price);
    }
}