<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/23
 * Time: 12:15
 */
namespace app\controller\backend;
use app\model\UserModel;
use vendor\Captcha;
class LoginController extends \core\Controller
{
  public function login(){
      if(empty($_POST)){
          //显示登录的表单
          $this->loadHtml('login/login');
      }else{
          //获取表单的提交值
          //var_dump($_POST);die();
          //判断验证码是否正确
          if(strtolower($_POST['edtCaptcha'])!=$_SESSION['trueCaptcha']){
            return $this->redirect('?p=backend&c=Login&a=login','验证码错误');
          }
          //判断用户名和密码是否正确
          $username=addslashes($_POST['username']);
          $password=addslashes($_POST['password']);
          $user=UserModel::create()->find("`password`='{$password}'AND `name`='{$username}'");

          if($user==false){
              $_SESSION['loginSuccess']=false;
              $this->redirect('?p=backend&c=Login&a=login','登录失败');
          }else{
              $_SESSION['uid']=$user->id;
              $_SESSION['user']=array(
                'id'=>$user->id,
                'name'=>$user->name,
                'nickname'=>$user->nickname,
                'email'=>$user->email,
                'register_time'=>$user->register_time,
                'current_time'=>time(),
                'current_ip'=>$_SERVER['REMOTE_ADDR'],
                );
               //传递一个登录成功的标志
              $_SESSION['loginSuccess']=true;
              $this->redirect("?p=frontend&c=Article&a=index",'登录成功');
          }
      }
  }
  //输出验证码图片
  public function captcha()
  {
    $obj=new Captcha();
    $obj->generateCode();
    $_SESSION['trueCaptcha']= $obj->getCode();//获取图片上验证码的内容
  }
    //退出登录
  public function logout(){
    unset($_SESSION);
    session_destroy();
    $this->redirect('?p=backend&c=Login&a=login','退出成功');
  }
}