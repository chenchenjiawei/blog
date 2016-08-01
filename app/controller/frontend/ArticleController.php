<?php

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/30
 * Time: 19:11
 */
namespace app\controller\frontend;
use core\Controller;
use app\model\ArticleModel;
use app\model\CategoryModel;
use app\model\CommentModel;
use vendor\Pager;
use vendor\Captcha;
class ArticleController extends Controller
{
    public function index()
    {
        //接受search传来的值
        $search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
        $categoryId=isset($_REQUEST['category_id'])?$_REQUEST['category_id']:'';
        $where= '2 > 1';
        if($search){
            //用户传递过来的搜索条件
            $where.=" AND `article`.`title`  LIKE '%{$search}%'";
        }
        if($categoryId){
            //用户传过来的分类条件
            $where.= " AND  `article`.`category_id` = '{$categoryId}'";
        }
        //分页
        $pageSize=3;
        $currentPage = isset($_GET['page'])? $_GET['page'] :1;
        $pager=new Pager(ArticleModel::create()->getCount($where),$pageSize,$currentPage,
            'index.php',array(
                'p'=>'frontend',
                'c'=>'Article',
                'a'=>'index',
             'categoryId'=>$categoryId,
            ));
        //生成分页连接
        $pagerLinks=$pager->showPage();
        //开始的行数
        $start=($currentPage-1)*$pageSize;
        //查出所有文章
        $articles=ArticleModel::create()->findAllWithJoin($where,$start,$pageSize);
        //显示无限极分类
        $categories=CategoryModel::create()->findAll();
        $categories=CategoryModel::create()->limitlessLevelCategory($categories);
        $this->s->assign(array(
            'articles'=>$articles,
            'categories'=>$categories,
            'search'=>$search,
            'categoryId'=>$categoryId,
            'pagerLinks'=>$pagerLinks,
        ));
        $this->s->display('frontend/article/index.html');

    }
    public function content()
    {
        // 接收$id=$_GET['id'];
        $id = $_GET['id'];

        // 阅读数+1 update article set read_count=read_count+1 where id=$id
        ArticleModel::create()->addReadCount($id);

        // 调用ArticleModel查询出文章的所有字段，包括作者的名字，分类的名字，评论的数量
        $article = ArticleModel::create()->findOneWithJoin($id);
        // 调用CommentModel查询文章id为$id的所有的评论，包括评论的作者名字
        $comments = CommentModel::create()->findAllWithJoin("`comment`.`article_id`='{$id}'");
        // 对文章的评论做无限极分类
        $comments = CommentModel::create()->limitlessLevelComment($comments);
        // 调用smarty的assign方法将$article, $comments传递到视图里
        //对分类进行无限极分类
        $categories=CategoryModel::create()->findAll();
        $categories=CategoryModel::create()->limitlessLevelCategory($categories);
        $this->s->assign(array(
            'article' => $article,
            'comments' => $comments,
            'categories'=>$categories,
        ));
        // 调用smarty的display方法关联app/view/frontend/article/content.html
        $this->s->display('frontend/article/content.html');
    }

    // 点赞的函数只有在内容页才能点赞
    public function good()
    {
        $id = $_GET['article_id'];
        $url = "?p=frontend&c=Article&a=content&id={$id}";
        if (isset($_SESSION["flag_{$id}"]) && $_SESSION["flag_{$id}"]) {
            // 表示用户已经赞过
            return $this->redirect($url, "已经赞过，不能重复点赞。");
        }
        if (ArticleModel::create()->addGoodCount($id)) {
            // 点赞成功
            $_SESSION["flag_{$id}"] = true;
            $this->redirect($url, "点赞成功");
        } else {
            // 点赞失败
            $this->redirect($url, "点赞失败，请稍后再试");
        }
    }
    //输出验证码图片
    public function captcha()
    {
        $obj=new Captcha();
        $obj->generateCode();
        $_SESSION['trueCaptcha']= $obj->getCode();//获取图片上验证码的内容
    }
}