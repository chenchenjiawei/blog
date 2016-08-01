<?php
namespace app\model;
use core\Model;
class CommentModel extends Model
{
	public static $table='comment';
	public function findAllWithJoin($where = '2 > 1',$start = false,$pageSize = false)
	{
		$sql="SELECT `comment`.*,a.content AS parent_content,`user`.`name` AS user_name,`article`.title 
		FROM `comment` 
		LEFT JOIN `comment` as a ON `comment`.`parent_id`=a.id 
		LEFT JOIN `user` ON `comment`.`user_id`=`user`.id 
		LEFT JOIN `article` ON `comment`.`article_id`=`article`.`id` WHERE {$where} ";
		if($start !== false){
			$sql.=" LIMIT {$start},{$pageSize}";
		}
		return $this->getAll($sql);
	}
	//定义评论的无限极分类
	public function limitlessLevelComment($comments,$parentId=0)
	{
		$treeComments = array();
		foreach ($comments as $comment) {
			if ($comment->parent_id == $parentId) {
				// 寻找评论的子评论
				$comment->childrens = $this->limitlessLevelComment($comments, $comment->id);
				$treeComments[] = $comment;
			}
		}
		return $treeComments;
	}
}