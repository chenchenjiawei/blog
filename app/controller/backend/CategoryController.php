<?php
namespace app\controller\backend;
use core\Controller;
use app\model\CategoryModel;
class CategoryController extends Controller
{   
	public function __construct(){
		parent::__construct();
		$this->denyAccess();
	}
	//显示列表页
	public function index()
	{	//查询出所有数据
		$categories=CategoryModel::create()->findAllWithJoin();
		//$categories=CategoryModel::create()->findAll();
		$categories=CategoryModel::create()->limitlessLevelCategory($categories);
		$this->loadHtml('category/index',array(
			//'category'=>$category,
			'categories'=>$categories,
			));
	}
	//显示添加的表单
	public function add()
	{
		if(empty($_POST)){
			//显示添加的表单
			$categories=CategoryModel::create()->findAll();
			$categories=CategoryModel::create()->limitlessLevelCategory($categories);
			$this->loadHtml('category/add',array(
				'categories'=>$categories,
				));
		}else{
			if(CategoryModel::create()->add(array(
				'name'=>$_POST['Name'],
				'nickname'=>$_POST['Alias'],
				'sort'=>$_POST['Order'],
				'parent_id'=>$_POST['ParentID'],
				))){
				$this->redirect('?p=backend&c=Category&a=index','添加成功');
			}else{
				$this->redirect('?p=backend&c=Category&a=add','添加失败');
			}
		}
	}
	public function delete()
	{
		$id=$_GET['id'];
		//判断一下是不是父类元素
		if(CategoryModel::create()->getCount("parent_id='{$id}'")>0){
			return $this->redirect('?p=backend&c=Category&a=index','不允许删除有子分类的分类');

		}else{
			if(CategoryModel::create()->deleteById($id)){
				$this->redirect('?p=backend&c=Category&a=index','删除成功');
			}else{
				$this->redirect('?p=backend&c=Category&a=index','删除失败');
			}
		}
	}
	public function update()
	{
		$id=$_GET['id'];
		if(empty($_POST)){
			//回显数据
			$category=CategoryModel::create()->findById($id);
			$categories=CategoryModel::create()->findAll();
			$categories=CategoryModel::create()->limitlessLevelCategory($categories);
			$this->loadHtml('category/update',array(
				'category'=>$category,
				'categories'=>$categories,
				));
		}else{
			$flag=CategoryModel::create()->updateById($id,array(
				'name'=>$_POST['Name'],
				'nickname'=>$_POST['Alias'],
				'sort'=>$_POST['Order'],
				'parent_id'=>$_POST['ParentID']
				));
			if($flag===1 || $flag===0){
				//修改成功
				$this->redirect('?p=backend&c=Category&a=index','修改成功');
			}else{
				$this->redirect('?p=backend&c=Category&a=update&id={$id}','修改失败');
			}
		}
	}
}