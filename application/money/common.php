<?php
use think\Db;

/*
* 返回代理商信息
*/
if (!function_exists('get_agents_info')) {
	function get_agents_info($mid)
	{
		$user = Db::name('member')->field('id,agent_id,agent_pro,agent_far,agent_rate')->where('id', $mid)->find();
		return $user;
	}
}
?>