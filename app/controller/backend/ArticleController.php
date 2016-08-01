<?php
namespace app\controller\backend;

use app\model\ArticleModel;
use app\model\CategoryModel;
use core\Controller;
use vendor\Pager;
class ArticleController extends Controller
{
	//添加文章
	public function add(){
		if(empty($_POST)){
			$categories=CategoryModel::create()->findAll();
			$categories=CategoryModel::create()->limitlessLevelCategory($categories);
			$this->loadHtml('article/add',array(
				'categories'=>$categories,
				));
		}else{
			//var_dump($_POST);die;
			//获取表单的提交值
			if(ArticleModel::create()->add(array(
					'category_id'=>$_POST['CateID'],
					'user_id'=>$_SESSION['uid'],
					'title'=>$_POST['Title'],
					'Content'=>$_POST['Content'],
					'status'=>$_POST['Status'],
					'date'=>strtotime($_POST['PostTime']),
					'label'=>$_POST['Label'],
					'top'=>isset($_POST['isTop'])?1:2,
				))){
				$this->redirect('?p=backend&c=Article&a=index','添加成功');
			}else{
				$this->$redirect('?p=backend&c=Article&a=add','添加失败');
			}
		}
	}
	//文章列表页
	public function index()
	{ 
		//获取搜索的关键字
		$where='2 > 1';
		//分类
		$categoryId=isset($_REQUEST['category']) ? $_REQUEST['category'] :0;
		if($categoryId){
			$where.=" AND `article`.`category_id`={$categoryId}";
		}
		//状态1：草稿2：公开3：隐藏
		$status=isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
		if($status){
			$where.=" AND `article`.`status` = {$status}";
		}
		//置顶
		$top=isset($_REQUEST['istop']) ?$_REQUEST['istop'] : 0;
		if($top){
			$where.=" AND `article`.`top` = 1";
		}
		//搜索
		$search=isset($_REQUEST['search'])? $_REQUEST['search'] : '';
		if($search){
			$where.=" AND `article`.`title` LIKE '%{$search}%'";
		}
		$pageSize = 1;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//进行分页
		$pager = new Pager(ArticleModel::create()->getCount($where), $pageSize, $page, 'index.php', array(
            'p' => 'backend',
            'c' => 'Article',
            'a' => 'index',
            'category' => $categoryId,
            'status' => $status,
            'istop' => $top,
            'search' => $search,
        ));
        $pagerButtons = $pager->showPage();
		//var_dump($pagerButtons);die;
		$start=($page-1) * $pageSize;
		$articles=ArticleModel::create()->findAllWithJoin($where,$start,$pageSize);
		$categories=CategoryModel::create()->findAll();
		$categories=CategoryModel::create()->limitlessLevelCategory($categories);
		$this->loadHtml('article/index',array(
			'articles'=>$articles,
			'categories'=>$categories,
			'categoryId' => $categoryId,
            'status' => $status,
            'top' => $top,
            'search' => $search,
            'pagerButtons'=>$pagerButtons,
			));
	}
	public function delete()
	{
		$id=$_GET['id'];
		if(ArticleModel::create()->deleteById($id)){
			$this->redirect('?p=backend&c=Article&a=index','删除成功');
		}else{
			$this->redirect('?p=backend&c=Article&a=index','删除失败');
		}
	}
	public function update()
	{
		$id=$_GET['id'];
		if(empty($_POST)){
			$this->loadHtml('article/update');
		}
	}
	public function content()
	{
		$id=$_GET['id'];
		$contents=ArticleModel::create()->findById($id);
		$this->loadHtml('./article/content',array('contents'=>$contents,));
	}
}
