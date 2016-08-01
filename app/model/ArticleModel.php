<?php
namespace app\model;
use core\Model;
class ArticleModel extends Model
{
	public static $table='article';
	public function findAllWithJoin($where = '2 > 1',$start = false,$pageSize = false)
	{
		$sql = "SELECT `article`.*, `category`.`name` AS category_name, `user`.`name` AS user_name,count(`comment`.`id`) AS comment_count
                FROM `article`
                LEFT JOIN `category` ON `article`.`category_id`=`category`.`id`
                LEFT JOIN `user` ON `article`.`user_id`=`user`.`id`
                LEFT JOIN `comment` ON `article`.`id`=`comment`.`article_id`
                where {$where}
                GROUP BY `article`.`id`";
        if($start !== false){
        	$sql.=" LIMIT {$start},{$pageSize}";
        }
		return $this->getAll($sql);
	}
	// 查询出一个文章的详细信息
	public function findOneWithJoin($id)
	{
		$sql = <<<SQL
SELECT `article`.*, `user`.`name` AS user_name, `category`.`name` AS category_name, count(`comment`.`id`) AS comment_count
FROM article
LEFT JOIN `user` ON `user`.`id`=`article`.`user_id`
LEFT JOIN `category` ON `article`.`category_id`=`category`.`id`
LEFT JOIN `comment` ON `comment`.`article_id`=`article`.`id`
WHERE `article`.`id`='{$id}' GROUP BY `article`.`id`
SQL;
		return $this->getOne($sql);
	}

	// 阅读数 +1的函数
	public function addReadCount($id)
	{
		$sql = <<<SQL
UPDATE article SET read_count=read_count+1 WHERE id='$id'
SQL;
		return $this->exec($sql);
	}

	// 点赞 +1的函数
	public function addGoodCount($id)
	{
		$sql = <<<SQL
UPDATE `article` SET `good`=`good`+1 WHERE `id`=$id
SQL;
		return $this->exec($sql);
	}
	public function getStatusLabel(){
		if($this->status==1){
			return '草稿';
		}else if($this->status==2){
			return '公开';
		}else if($this->status==3){
			return '隐藏';
		}
	}
}