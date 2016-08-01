<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/22
 * Time: 21:32
 */
namespace app\controller\backend;
use app\model\UserModel;
class UserController extends \core\Controller
{
    //用户是否登录
    public function __construct()
    {
        $this->denyAccess();
    }
    //显示用户列表
    public function index()
    {
        $users=UserModel::create()->findAll();
        $data = array(
            'users' => $users,
        );
        $this->loadHtml('user/index',$data);
    }
    //显示添加列表
    public function add()
    {
        if(empty($_POST)){
            $this->loadHtml('user/add');
        }else{
            if(empty($_POST['Username'])){
                return $this->redirect('?p=backend&c=User&a=add','用户名不能为空，添加失败');
            }elseif(empty($_POST['Password'])){
                return $this->redirect('?=backend&c=User&a=add','密码不能为空，添加失败');
            }
            if(UserModel::create()->add(array(
                'name'=>$_POST['Username'],
                'nickname'=>$_POST['Nickname'],
                'email'=>$_POST['Email'],
                'password'=>md5($_POST['Password']),
            ))){
                $this->redirect('?p=backend&c=User&a=index','添加成功');
            }else{
                $this->redirect('?p=backend&c=User&a=add','添加失败');
            }
}
}
    //删除用户
    public function delete()
    {
        $id=$_GET['id'];
        if(UserModel::create()->deleteById($id)){
            $this->redirect('?p=backend&c=User&a=index','删除成功');
        }else{
            $this->redirect('?p=backend&c=User&a=index','删除失败，请稍后重试');
        }
    }
    //修改用户表
    public function update()
    {
        $id=$_GET['id'];
        if(empty($_POST)){
            $user=UserModel::create()->findById($id);
            $this->loadHtml('user/update',array('user'=>$user));
        }else{
            if(UserModel::create()->updateById($id,array(
                'name'=>$_POST['Username'],
                'nickname'=>$_POST['Nickname'],
                'email'=>str_replace('<script>','',str_replace('</script>','',$_POST['Email'])),
                'password'=>md5($_POST['Password']),
            ))){
                $this->redirect('?p=backend&c=User&a=index','修改成功');
            }else{
                $this->redirect('?p=backend&c=User&a=update&id={$id}','修改失败');
            }
        }
    }
    //注册用户表

}