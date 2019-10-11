<?php

namespace Jdgrieco\LoggiPHP\Contracts;

use Jdgrieco\LoggiPHP\Mutation;
use Jdgrieco\LoggiPHP\Query;

interface ClientGraphQLContract
{
    /**
     * Execute query
     *
     * @param Query $query
     *
     * @return array
     */
    public function executeQuery(Query $query);

    /**
     * Execute mutation
     *
     * @param Mutation $mutation
     *
     * @return array
     */
    public function executeMutation(Mutation $mutation);
}