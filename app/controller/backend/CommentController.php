<?php
namespace app\controller\backend;
use app\model\CommentModel;
use core\Controller;
use vendor\Pager;
class CommentController extends Controller
{
	//显示列表页
	public function index()
	{
		//查出所有的评论数
		$where='2 > 1';
		$pageSize = 3;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		//进行分页
		$pager = new Pager(CommentModel::create()->getCount($where), $pageSize, $page, 'index.php', array(
			'p' => 'backend',
			'c' => 'Comment',
			'a' => 'index',
		));
		$pagerButtons = $pager->showPage();
		//var_dump($pagerButtons);die;
		$start=($page-1) * $pageSize;
		$comments=CommentModel::create()->findAllWithJoin($where,$start,$pageSize);
		//加载HTML
		$this->loadHtml('comment/index',array(
			'comments'=>$comments,
			'pagerButtons'=>$pagerButtons,
			));
	}
	//删除一条记录
	public function delete()
	{
		$id=$_GET['id'];
		if(CommentModel::create()->deleteById($id)){
			$this->redirect('?p=backend&c=Comment&a=index','删除成功');
		}else{
			$this->redirect('?p=backend&c=Comment&a=index','删除失败');
		}
	}
}