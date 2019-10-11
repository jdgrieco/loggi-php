<?php

namespace Jdgrieco\LoggiPHP\Contracts;

use Jdgrieco\LoggiPHP\Query;

interface ClientGraphQLContract
{
    /**
     * Execute query
     *
     * @param Query $query
     * @return array
     */
    public function executeQuery(Query $query);
}