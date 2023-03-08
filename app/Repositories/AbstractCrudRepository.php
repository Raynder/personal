<?php

namespace App\Repositories;

use App\Repositories\Traits\CrudMethods;
use App\Repositories\Contracts\CrudRepository;

abstract class AbstractCrudRepository extends BaseRepository implements CrudRepository
{
    use CrudMethods;
}
