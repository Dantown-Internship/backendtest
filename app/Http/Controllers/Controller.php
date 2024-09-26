<?php

namespace App\Http\Controllers;

use App\Contracts\FeaturesContract;

abstract class Controller
{
    public function process( FeaturesContract $feature, array $params = [] ) : mixed
    {
        return $feature->handle( $feature->action, $params);
    }
}
