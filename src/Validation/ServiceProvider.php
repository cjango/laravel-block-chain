<?php

namespace Jason\BlockChain\Validation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['validation'] = function ($app) {
            // return new Client($app);
        };
    }

}
