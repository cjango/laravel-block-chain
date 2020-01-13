<?php

namespace Jason\BlockChain\Account;

use Jason\BlockChain\Kernel\Exceptions\BlockChainException;
use Exception;
use Pimple\Container;

class Account
{

    /**
     * 配置文件
     * @var array
     */
    protected $config;

    /**
     * 私钥
     * @var string
     */
    protected $privateKey;

    /**
     * 公钥
     * @var string
     */
    protected $publicKey;

    /**
     * 钱包地址
     * @var string
     */
    protected $address;

    /**
     * 初始化，主要是为了注入配置文件
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->config = $app->config;
    }

    /**
     * Notes: 设置私钥
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:46
     * @param $privateKey
     * @return $this
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Notes: 设置公钥
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:46
     * @param $publicKey
     * @return $this
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Notes: 通过OPENSSL公钥，私钥来生成一个地址
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:46
     * @return $this
     * @throws Exception
     */
    public function createAddress()
    {
        // 创建新的密钥
        $res = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        // 提取私钥
        openssl_pkey_export($res, $this->privateKey);
        // 生成公钥
        $publicArr = openssl_pkey_get_details($res);
        if ($publicArr === false) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->publicKey = $publicArr['key'];

        $tmpAddress = sprintf('%s%s',
            $this->config['version'],
            hash('ripemd160', hash('sha256', $this->publicKey))
        );

        // 根据上一步得到的哈希值进行校验码的计算并拼接到尾部
        $address = sprintf('%s%s', $tmpAddress,
            $this->generateVerifyCode($tmpAddress)
        );

        // 最后将拼接了版本号、校验位的内容进行一次 sha256 哈希得到最终地址
        $this->address = sprintf('0x%s', hash('sha256', $address));

        return $this;
    }

    /**
     * Notes: 根据临时地址计算校验位字符串
     * 对地址进行两次 sha256 哈希计算后得到的前八位十六进制字符
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:47
     * @param String $tmpAddress 通过公钥计算出的带有主链版本号的地址
     * @return bool|string
     */
    protected function generateVerifyCode($tmpAddress)
    {
        return substr(hash('sha256', hash('sha256', $tmpAddress)), 0, 8);
    }

    /**
     * Notes: 转账
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:47
     * @param string $account 目标账户地址
     * @param array $resourceData 转账数据
     */
    function transfer(string $account, array $resourceData)
    {

    }

    /**
     * Notes: 将私钥数据保存到文件
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:53
     * @param string $filename
     * @return bool
     */
    public function savePrivateKey(string $filename = null)
    {
        $filename = $filename ?: $this->config['privateKeyPath'];

        return $this->saveToFile($filename, $this->privateKey);
    }

    /**
     * Notes: 将公钥数据保存到文件
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:53
     * @param string $filename
     * @return bool
     */
    public function savePublicKey(string $filename = null)
    {
        $filename = $filename ?: $this->config['publicKeyPath'];

        return $this->saveToFile($filename, $this->publicKey);
    }

    /**
     * Notes: 将数据保存到文件
     * @Author: <C.Jason>
     * @Date: 2020/1/11 22:55
     * @param string $filename
     * @param string $data
     * @return bool
     * @throws BlockChainException
     */
    public function saveToFile(string $filename, string $data)
    {
        $dir = dirname($filename);
        if (!is_dir($dir)) {
            throw new BlockChainException(sprintf('Dir no exist: [%s]', $dir));
        }

        return file_put_contents($filename, $data) !== false;
    }

}
