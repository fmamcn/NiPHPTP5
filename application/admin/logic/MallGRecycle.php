<?php
/**
 *
 * 商品回收站 - 商城 - 逻辑层
 *
 * @package   NiPHPCMS
 * @category  admin\logic\
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: MallGRecycle.php v1.0.1 $
 * @link      http://www.NiPHP.com
 * @since     2017/01/03
 */
namespace app\admin\logic;

use think\Model;
use think\Request;
use think\Lang;
use think\Config;
use think\Cache;
use app\admin\model\MallGoods as ModelMallGoods;
use app\admin\model\MallGoodsPromote as ModelMallGoodsPromote;
use app\admin\model\MallGoodsAlbum as ModelMallGoodsAlbum;

class MallGRecycle extends Model
{
    protected $request = null;

    protected function initialize()
    {
        parent::initialize();

        $this->request = Request::instance();
    }

    /**
     * 列表数据
     * @access public
     * @param
     * @return array
     */
    public function getListData()
    {
        $map = ['g.lang' => Lang::detect()];
        if ($key = $this->request->param('key')) {
            $map['g.name'] = ['LIKE', '%' . $key . '%'];
        }

        $goods = new ModelMallGoods;
        $goods->view('mall_goods g', 'id,name,thumb,price,is_pass,is_show,is_com,is_top,is_hot,sort')
        ->view('mall_type t', ['name'=>'type_name'], 't.id=g.type_id')
        ->view('mall_brand b', ['name'=>'brand_name'], 'b.id=g.brand_id', 'LEFT')
        ->view('mall_goods_promote p', ['promote_price', 'promote_start_time', 'promote_end_time'], 'p.goods_id=g.id', 'LEFT')
        ->where($map)
        ->order('id DESC');

        $result =
        $goods->onlyTrashed()->paginate();

        $list = [];
        foreach ($result as $key => $value) {
            $value = $value->toArray();
            $value['price'] = to_yen($value['price']);
            $value['promote_price'] = to_yen($value['promote_price']);
            $list[] = $value;
        }

        $page = $result->render();

        return ['list' => $list, 'page' => $page];
    }

    /**
     * 删除数据
     * @access public
     * @param
     * @return boolean
     */
    public function remove()
    {
        $id = $this->request->param('id/f');
        $map = ['id' => $id];

        $goods = new ModelMallGoods;
        $result =
        $goods->onlyTrashed()
        ->where($map)
        ->delete();

        // 促销信息
        $map = ['goods_id' => $goods_id];
        $promote = new ModelMallGoodsPromote;
        $promote->where($map)
        ->delete();

        // 商品相册
        $album = new ModelMallGoodsAlbum;
        // 删除相册重新写入
        $album->where($map)
        ->delete();

        return $result ? true : false;
    }
}
