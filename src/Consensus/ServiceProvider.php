<?php

namespace Jason\BlockChain\Consensus;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['consensus'] = function ($app) {
            // return new Client($app);
        };
    }

}
