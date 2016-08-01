<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/22
 * Time: 21:39
 */
namespace core;
class Application
{
    public static function run()
    {
        //设置网页字符集
        self::setCharset();
        //定义路由常量
        self::defineRouteConst();
        //定义路径常量
        self::definePathConst();
        //定义自动加载
        self::defineAutoloader();
        //开启SESSION
        self::openSession();
        //分发路由
        self::dispatchRoute();
    }
    protected static function setCharset()
    {
        header("content-type:text/html;charset=utf-8");
    }
    protected static function defineRouteConst()
    {
        $p=isset($_GET['p'])?$_GET['p']:'frontend';
        define('PLATFORM',$p);
        $c=isset($_GET['c'])?$_GET['c']:'Article';
        define('CONTROLLER',$c);
        $a=isset($_GET['a'])?$_GET['a']:'index';
        define('ACTION',$a);
    }
    protected static function definePathConst()
    {
        define('VIEW_PATH','./app/view');
        define('CONFIG_PATH','./app/config');
    }
    protected static function loadClass($className)
    {
        $fileName= str_replace('\\','/',$className).".php";
        if(is_file($fileName)){
            require $fileName;
        }
    }
    protected static function defineAutoloader()
    {
        spl_autoload_register('self::loadClass');
    }
    protected static function openSession()
    {
        session_start();
    }
    protected static function dispatchRoute()
    {
        $c=CONTROLLER .'controller';
        $c="app\\controller\\" .PLATFORM ."\\".$c;
        $obj=new $c();
        $a=ACTION;
        $obj->$a();

    }
}