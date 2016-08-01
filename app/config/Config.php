<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/6/23
 * Time: 20:20
 */
namespace app\config;
//数据库配置信息
class Config
{
    public static $database=array(
        'type'=>'mysql',
        'host'=>'localhost',
        'port'=>'3306',
        'charset'=>'utf8',
        'uset'=>'root',
        'pass'=>'',
        'dbname'=>'blog48',
    );
}