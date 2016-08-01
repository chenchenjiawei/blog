<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/22
 * Time: 22:12
 */
namespace app\controller\backend;
class FrameController extends \core\Controller
{

    //显示框架
    public function frame()
    {
        $this->denyAccess();
        $this->loadHtml('frame/frame');
    }
    //加载顶部
    public function top()
    {
        $this->denyAccess();
        $this->loadHtml('frame/top');
    }
    //加载菜单栏
    public function menu()
    {
        $this->denyAccess();
        $this->loadHtml('frame/menu');
    }
    //加载内容栏
    public function content()
    {
        $this->denyAccess();
        $this->loadHtml('frame/content');
    }
}