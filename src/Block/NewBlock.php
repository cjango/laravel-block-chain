<?php

namespace Jason\BlockChain\Block;

use Exception;
use Pimple\Container;

class NewBlock
{

    protected $app;

    protected $block;

    /**
     * 区块存储的原始数据
     * @var array
     */
    protected $transactionData;

    protected $belongsToAccount;

    /**
     * 区块的数据结构
     * @var array
     */
    protected $blockStructure = [
        'header'           => [
            'blockHeight',
            'nonce',
            'difficulty',
            'version',
            'timestamp',
            'prevHash',
            'hash',
            'dataHash',
        ],
        'body'             => [
            'transactionData',
            'merkleTreeData',
        ],
        'belongsToAccount' => '',
    ];

    public function __construct(Container $app)
    {
        $this->app = $app;

        $this->block = new Block();
    }

    public function setTransactionData($transactionData)
    {
        $this->transactionData = $transactionData;

        return $this;
    }

    public function setAccount($belongsToAccount)
    {
        $this->belongsToAccount = $belongsToAccount;

        return $this;
    }

    public function handler()
    {
        $this->block->blockHeight = $this->getNewBlockHeight();
        $this->block->nonce       = $this->getNonce();
        $this->block->difficulty  = $this->app->config['difficulty'];
        $this->block->version     = $this->app->config['version'];
        $this->block->timestamp   = time();
        $this->block->prevHash    = $this->getPrevHash();
        $this->block->hash        = $this->generateBlockHash();
        $this->block->dataHash    = $this->generateDataHash();

        return $this->block;
        //        return Block::create([
        //            'body'             => [
        //                'transactionData' => $this->transactionData,
        //                'merkleTreeData'  => $this->transactionData,
        //            ],
        //            'belongsToAccount' => $this->belongsToAccount,
        //        ]);
    }

    /**
     * 生成区块哈希值
     * @return string
     */
    protected function generateBlockHash()
    {
        $data = $this->block->getHeader();
        unset($data['hash']);
        unset($data['blockHeight']);

        ksort($data);
        $hashStr = http_build_query($data);

        return '0x' . hash('sha256', hash('sha256', $hashStr));
    }

    /**
     * Notes: 计算区块体数据的摘要哈希值，计算采用 Merkle Tree 算法取根节点值作为最终的哈希值
     * @Author: <C.Jason>
     * @Date: 2020/1/6 11:27 上午
     * @param array $data
     * @return string
     * @throws Exception
     */
    protected function generateDataHash($data = [])
    {
        // 如果传入数据为空时，根据区块体交易数据计算 Merkle Tree 的
        // 叶子节点哈希值列表
        if (empty($data)) {
            foreach ($this->transactionData as $value) {
                if (!is_string($value)) {
                    throw new Exception('Invalid transaction type!');
                }
                $data[]['value'] = hash('sha256', $value);
            }
        }

        // @see getParentHashListForMerkleTree()
        $result = $this->getParentHashListForMerkleTree($data);

        // 未计算到根节点
        if (count($result) > 1) {
            return $this->generateDataHash($result);
        }

        $this->merkleTreeData = $result;

        return '0x' . $result[0]['value'];
    }

    /**
     * 计算上一层哈希列表
     * 相邻节点计算父节点哈希值
     * 最后一个没有相邻节点时，使用自身值拷贝进行计算父节点哈希
     * @param array $data 当前层级数据列表
     * 每个节点的数据结构
     * [
     *     'value' => '哈希值',
     *     'leftChildNode' => 左子节点,
     *     'rightChildNode' => 右子节点,
     * ]
     * @return array
     */
    protected function getParentHashListForMerkleTree($data)
    {
        if (empty($data)) {
            return [['value' => hash('sha256', '')]];
        }

        $result = [];
        $i      = 0;
        while ($i < count($data)) {
            $tmpValue = isset($data[$i + 1]['value'])
                ? $data[$i]['value'] . $data[$i + 1]['value']
                : $data[$i]['value'] . $data[$i]['value'];

            $result[] = [
                'value'          => hash('sha256', $tmpValue),
                'leftChildNode'  => $data[$i],
                'rightChildNode' => isset($data[$i + 1]) ? $data[$i + 1] : null,
            ];

            $i += 2;
        }

        return $result;
    }

    /**
     * Notes: 获取当前新区块编号
     * @Author: <C.Jason>
     * @Date: 2020/1/6 11:29 上午
     */
    protected function getNewBlockHeight()
    {

    }

    /**
     * Notes: 生成随机数
     * @Author: <C.Jason>
     * @Date: 2020/1/11 23:42
     * @return string
     */
    protected function getNonce()
    {
        return uniqid();
    }

    protected function getPrevHash()
    {

    }

    /**
     * 向区块链尾部添加一个区块
     * @return void
     */
    protected function pushToBlockchain()
    {
        $block = $this->getBlockData();
    }

}
