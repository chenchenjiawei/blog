<?php 
namespace core;
use app\config\Config;
class Model extends \vendor\PDOWrapper
{
	//配置数据库信息
	public function __construct()
	{
		parent::__construct(Config::$database);

	}
	//静态方法实例化
	public static function create($className = false)
	{
		if ($className === false) {
			$className = get_called_class();
		}
		//static 修饰的变量在函数内不会被初始化
		static $instance = array();
		if (!isset($instance[$className])) {
			$instance[$className] = new $className();
		}
		return $instance[$className];
	}
	//查询表中所有的记录
	public function findAll($where = '2 > 1')
	{
		$sql = "SELECT * FROM `" . static::$table . "` WHERE {$where}";
		return $this->getAll($sql);
	}
	 //通过ID查询一条记录
	public function findById($id)
	{
		$sql = "SELECT * FROM `" . static::$table . "` WHERE id='{$id}'";
		return $this->getOne($sql);
	}
	//通过条件查询一条数据
	public function find($where){
		$sql="SELECT * FROM  `".static::$table ."` WHERE {$where}";
		return $this->getone($sql);
	}
	//通过ID删除一条纪录
	public function deleteById($id)
	{
		$sql = "DELETE FROM `" . static::$table . "` WHERE id='{$id}'";
		return $this->exec($sql);
	}

	public function delete()
	{
		return $this->deleteById($this->id);
	}
	//添加记录
	public function add($data)
	{
		//array(
		//    '字段名字' => '字段的值',
		//    '字段2。。.' => '值...'
		//)

		$fields = "";
		$fieldValues = "";
		foreach ($data as $field => $fieldValue) {
			$fields .= "`{$field}`,";
			$fieldValues .= "'{$fieldValue}',";
		}
		$fields = rtrim($fields, ',');// 将字符串末尾的,去掉
		$fieldValues = rtrim($fieldValues, ',');
		$table = static::$table; //通过静态延时方法获取子类的对象属性
		$sql = "INSERT INTO `{$table}` ({$fields}) VALUES ({$fieldValues})";
		return $this->exec($sql);
	}
	//通过一条ID修改记录
	public function updateById($id, $data)
	{
		$sets = "";
		foreach ($data as $field => $fieldValue) {
			$sets .= "`{$field}`='{$fieldValue}',";
		}
		$sets = rtrim($sets, ',');
		$table = static::$table;
		$sql = "UPDATE `{$table}` SET {$sets} WHERE id='{$id}'";
		return $this->exec($sql);
	}

	//查询记录数
	public function getCount($where = '2>1')
	{
		$table = static::$table;
		$sql = "SELECT count(*) as c FROM `{$table}` WHERE {$where}";
				return $this->getOne($sql)->c;
	}
}