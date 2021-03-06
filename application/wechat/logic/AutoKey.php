<?php
/**
 *
 * 自动|关键词回复 - 逻辑层
 *
 * @package   NiPHPCMS
 * @category  wechat\logic\
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: AutoKey.php v1.0.1 $
 * @link      http://www.NiPHP.com
 * @since     2016/11/25
 */
namespace app\wechat\logic;

use think\Lang;
use think\Request;
use think\Cache;
use app\wechat\logic\Common;
use app\admin\model\Reply as ModelReply;

class AutoKey extends Common
{

    public function reply($key)
    {
        $data = $this->getKey($key);
        if (empty($data)) {
            $data = $this->getAuto();
        }
        return $data;
    }


    /**
     * 关键词回复
     * @access public
     * @param
     * @return array
     */
    public function getKey($key)
    {
        $map = [
            'type' => 0,
            'keyword' => ['LIKE', '%' . $key . '%'],
            'lang' => Lang::detect()
        ];

        $model = new ModelReply;

        $result =
        $model->field(true)
        ->where($map)
        ->order('sort DESC, id DESC')
        ->cache(!APP_DEBUG)
        ->select();

        return $this->toReply($result);
    }

    /**
     * 自动回复内容信息
     * @access public
     * @param
     * @return array
     */
    public function getAuto()
    {
        $map = [
            'type' => 1,
            'lang' => Lang::detect()
        ];

        $model = new ModelReply;

        $result =
        $model->field(true)
        ->where($map)
        ->order('sort DESC, id DESC')
        ->cache(!APP_DEBUG)
        ->select();

        return $this->toReply($result);
    }
}
