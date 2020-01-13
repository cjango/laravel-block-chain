<?php

namespace Jason\BlockChain\Contracts;

interface BlockInterface
{

    /**
     * Notes: 获取区块头数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getHeader();

    /**
     * Notes: 获取区块体数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getBody();

    /**
     * Notes: 获取区块完整数据
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return array
     */
    public function getBlockData();

    /**
     * Notes: 获取所属账户
     * @Author: <C.Jason>
     * @Date: 2020/1/6 10:38 上午
     * @return string
     */
    public function getAccount();

}
