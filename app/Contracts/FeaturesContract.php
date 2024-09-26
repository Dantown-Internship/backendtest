<?php

namespace App\Contracts;

use App\Actions\BaseAction;

abstract class FeaturesContract {

    public BaseAction $action;

    abstract public function handle( BaseAction $action, array $params = [] );

}