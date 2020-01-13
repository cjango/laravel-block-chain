<?php

namespace Jason\BlockChain\Account;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['account'] = function ($app) {
            return new Account($app);
        };
    }

}
