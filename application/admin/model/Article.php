<?php
/**
 *
 * 文章表 - 数据层
 *
 * @package   NiPHPCMS
 * @category  admin\model\
 * @author    失眠小枕头 [levisun.mail@gmail.com]
 * @copyright Copyright (c) 2013, 失眠小枕头, All rights reserved.
 * @version   CVS: $Id: Article.php v1.0.1 $
 * @link      http://www.NiPHP.com
 * @since     2016/10/29
 */
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Article extends Model
{
	use SoftDelete;
	protected $deleteTime = 'delete_time';

	protected $name = 'article';
	protected $autoWriteTimestamp = true;
	protected $field = [
		'id'            => 'int',
		'title',
		'keywords',
		'description',
		'content',
		'thumb',
		'category_id'   => 'int',
		'type_id'       => 'int',
		'is_pass'       => 'int',
		'is_com'        => 'int',
		'is_top'        => 'int',
		'is_hot'        => 'int',
		'sort'          => 'int',
		'hits'          => 'int',
		'comment_count' => 'int',
		'username',
		'origin',
		'user_id'       => 'int',
		'url',
		'is_link'       => 'int',
		'show_time'     => 'int',
		'create_time'   => 'int',
		'update_time'   => 'int',
		'delete_time'   => 'int',
		'access_id'     => 'int',
		'lang'
	];
}