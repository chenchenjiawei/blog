<?php
/**
 * 传智播客：高端PHP培训
 * 网站：http://www.itcast.cn
 */
namespace app\controller\frontend;

use app\model\ArticleModel;
use app\model\CommentModel;
use core\Controller;

class CommentController extends Controller
{
    public function add()
    {
        $this->denyAccess();
        //var_dump($_POST);die;
        //判断验证码是否正确
        if(strtolower($_POST['captcha'])!=$_SESSION['trueCaptcha']){
            return $this->redirect("?p=frontend&c=Article&a=content&id={$_GET['article_id']}",'验证码错误');
        }
        if (CommentModel::create()->add(array(
            'user_id' => $_SESSION['uid'],
            'article_id' => $_GET['article_id'],
            'time' => time(),
            'content' => $_POST['txaArticle'],
            'parent_id' => $_POST['inpRevID'],
        ))) {
            // 添加成功
            $this->redirect("?p=frontend&c=Article&a=content&id={$_GET['article_id']}", '评论成功');
        } else {
            // 添加失败
            $this->redirect("?p=frontend&c=Article&a=content&id={$_GET['article_id']}", "评论失败，请稍后再试。");
        }
    }
}