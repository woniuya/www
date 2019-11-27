<?php
	namespace plugins\UcpaasSMS;
	use app\common\controller\Plugin;

	class UcpaasSms extends Plugin
	{

		public $info = [
			'name' 		  =>'UcpaasSms',
			'title'       =>'创蓝短信',
			'identifier'  => 'ucpaassms.zhangjili.plugin',
			'icon'        => 'fa fa-fw fa-envelope',
			'author'      => '张继立',
			'author_url'  => 'http://www.lurenjiayi.com',
			'version'     => '1.0.0',
			'description' => '创蓝253短信接口。',
		];
		public function install()
		{
			return true;
		}

		public function uninstall()
		{
			return true;
		}
	}

?>