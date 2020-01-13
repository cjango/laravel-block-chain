<?php

namespace Jason\BlockChain\Block;

use Pimple\Container;

class Client
{

    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function newBlock(array $transaction, string $address)
    {
        $generator = new NewBlock($this->app);
        $generator->setTransactionData($transaction);
        $generator->setAccount($address);

        return $generator->handler();
    }

}
