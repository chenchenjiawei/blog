<?php
namespace app\model;
use core\Model;
class CategoryModel extends Model
{
	public static $table='category';
	public function findAllWithJoin($where='2 > 1')
	{
		$sql=<<<SQL

SELECT `category`.*,count(`article`.`category_id`) AS article_count
FROM `category`
LEFT JOIN `article` ON `category`.`id`=`article`.`category_id` WHERE $where GROUP BY `category`.`id`;
SQL;
		return $this->getAll($sql);
	}
	//无限极分类
	public function limitlessLevelCategory($categories, $parentId=0,$level=0)
	{
		static $limitlessCategories=array();
		foreach($categories as $category){
			if($category->parent_id==$parentId){
				$category->level = $level;
				$limitlessCategories[]=$category;
				$this->limitlessLevelCategory($categories,$category->id,$level+1);
			}
		}
		return $limitlessCategories;
	}

}