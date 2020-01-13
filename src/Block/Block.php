<?php

namespace Jason\BlockChain\Block;

use Jason\BlockChain\Contracts\BlockInterface;
use Jason\BlockChain\Kernel\Exceptions\BlockChainException;

class Block implements BlockInterface
{

    /**
     * 区块头信息
     * @var array
     */
    protected $blockHeader;

    /**
     * 区块body信息
     * @var array
     */
    protected $blockBody;

    /**
     * 区块所属账户
     * @var string
     */
    protected $belongsToAccount;

    /**
     * 区块编号
     * @var integer
     */
    protected $blockHeight;

    /**
     * 随机数
     * @var string
     */
    protected $nonce;

    /**
     * 难度系数
     * @var integer
     */
    protected $difficulty;

    /**
     * 版本号
     * @var string
     */
    protected $version;

    /**
     * 区块生成的时间戳（秒）
     * @var integer
     */
    protected $timestamp;

    /**
     * 上一个区块的 Hash 值
     * @var string
     */
    protected $prevHash;

    /**
     * 当前区块的数据摘要 Hash 值
     * @var string
     */
    protected $hash;

    /**
     * 当前区块中，区块体数据的摘要值
     * 通过 Merkle Tree 算法取 Root Node 的值作为数据体的摘要值
     * @var string
     */
    protected $dataHash;

    /**
     * 账单数据，存储格式为JSON
     * @var array
     */
    protected $transactionData = [];

    /**
     * 账单数据生成的 Merkle Tree 数据数组
     * 每个树节点由三个属性构成 value 哈希值 leftChildNode 左子节点
     * rightChildNode 右子节点
     * @var array
     */
    protected $merkleTreeData = [];

    /**
     * 区块头属性列表
     * @var array
     */
    protected static $headerKeys = [
        'blockHeight',
        'nonce',
        'difficulty',
        'version',
        'timestamp',
        'prevHash',
        'hash',
        'dataHash',
    ];

    /**
     * 区块体属性列表
     * @var array
     */
    protected static $bodyKeys = [
        'transactionData',
        'merkleTreeData',
    ];

    /**
     * Notes: 创建一个区块
     * @Author: <C.Jason>
     * @Date: 2020/1/11 23:13
     * @param array $data
     * @return Block
     */
    public static function create(array $data)
    {
        $attributes = [];
        // 检查区块所有者
        if (!isset($data['belongsToAccount'])) {
            throw new BlockChainException('Invalid block owner');
        }

        // 检查区块头数据
        foreach (static::$headerKeys as $field) {
            if (!isset($data['header'][$field])) {
                throw new BlockChainException(sprintf('Required attributes [%s] from block header', $field));
            }

            $attributes[$field] = $data['header'][$field];
        }

        // 检查区块体数据
        foreach (static::bodyKeys as $field) {
            if (!isset($data['body'][$field])) {
                throw new BlockChainException(sprintf('Required attributes [%s] from block body', $field));
            }

            $attributes[$field] = $data['body'][$field];
        }

        // 根据区块数据创建一个区块实例
        $instance = new static;
        foreach ($attributes as $name => $value) {
            $instance->{$name} = $value;
        }
        $instance->belongsToAccount = $data['belongsToAccount'];

        return $instance;
    }

    /**
     * Notes: 获取区块头数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getHeader()
    {
        return $this->blockHeader;
    }

    /**
     * Notes: 获取区块体数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getBody()
    {
        return $this->blockBody;
    }

    /**
     * Notes: 获取区块完整数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getBlockData()
    {
        return [
            'header'           => $this->getHeader(),
            'body'             => $this->getBody(),
            'belongsToAccount' => $this->belongsToAccount,
        ];
    }

    /**
     * Notes: 获取所属账户
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return string
     */
    public function getAccount()
    {
        return $this->belongsToAccount;
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }

    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        }
    }

}
