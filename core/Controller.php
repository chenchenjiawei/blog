<?php 
namespace core;
use vendor\Smarty;
class Controller{
	protected $s;
	public function __construct(){
		$this->createSmartyObject();
	}
	protected function createSmartyObject()
	{
		$s = new Smarty();
		// templates_c放到系统的临时目录
		$s->setCompileDir(sys_get_temp_dir() . '/templates_c');
		// configs 放到 app/config
		$s->setConfigDir('./app/config');
		// templates 放到 app/view
		$s->setTemplateDir(VIEW_PATH);
		$s->left_delimiter = '<{';
		$s->right_delimiter = '}>';

		$this->s = $s;
	}

	//判断用户是否登录
	protected function denyAccess()
	{
		if(isset($_SESSION['loginSuccess'])&&$_SESSION['loginSuccess']){

		}else{
			return $this->redirect("?p=backend&c=Login&a=login",'登录失败');
		}
	}
	//输出提示信息并等待几秒跳转到指定页面
	protected function redirect($url, $message = "", $time = 3, $type = 1)
	{
		// $type == 1显示用户友好的提示信息，$type==2显示用户不友好的提示信息
		if ($type == 2) {
			header("Refresh: {$time}; url={$url}");
			echo $message;
		} else {
			// 显示用户友好的提示信息
			require VIEW_PATH . '/tip.html';
		}
	}
	//加载html文件，并且传输指定的参数
	public function loadHtml($htmlName,$data=array())
	{
		foreach($data as $variableName=>$variableValue){
			$$variableName=$variableValue;
		}
		require VIEW_PATH ."/".PLATFORM ."/{$htmlName}.html";
	}
}

 ?>