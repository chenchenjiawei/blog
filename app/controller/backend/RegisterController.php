<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/7/2
 * Time: 18:58
 */

namespace app\controller\backend;



class RegisterController
{

        public function register()
    {
        if(empty($_POST))
        {
            require './app/view/backend/user/register.html';
        }else{
            var_dump($_POST);die;
            if(UserModel::create()->add(array(
                'name'=>$_POST['Username'],
                'nickname'=>$_POST['Nickname'],
                'email'=>$_POST['Email'],
                'password'=>md5($_POST['Password']),
            ))){
                $this->redirect('?p=backend&c=User&a=index','注册成功');
            }else{
                $this->redirect('?p=backend&c=User&a=add','注册失败');
            }
        }
    }

}