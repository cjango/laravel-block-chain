<?php

namespace Jason\BlockChain\Laravel;

use Jason\BlockChain\BlockChain;
use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade
{

    public static function getFacadeAccessor()
    {
        return BlockChain::class;
    }

}
