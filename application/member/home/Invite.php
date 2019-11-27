<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2018 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | @author 伊冰华 <2851336094@qq.com>
// +----------------------------------------------------------------------
namespace app\member\home;
use app\member\model\Member as MemberModel;
use think\db;
use think\Request;
class Invite extends Common
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
		$mid = MID;
		
		$user = get_agents_info($mid);
		$agent_pro = $user['agent_pro'];
		$agent_rate = agents_back_rate($mid);
		
		if($user['agent_rate']){
			$rate = $user['agent_rate'];
		}else{
			$rate =agents_back_rate($mid);
		}

		$plu_rate = $rate;

        $this->assign('agent_id',$user['agent_id']);
		$this->assign('agent_rate',$agent_rate);
		$this->assign('agent_pro',$agent_pro);
		$this->assign('plu_rate',$plu_rate);
        $this->assign('active', 'invite');
    }

    /**
     * 邀请人奖励记录
     * @return mixed
     */
    public function index()
    {

        $mid = is_member_signin();
        if(!$mid){
            $this->error('请登陆后进行申请', URL('/login'));
        }
        $data=$res = Db::name('member_invitation_relation as r')
            ->join('member','member.id=r.invitation_mid','LEFT')
            ->field('r.id,r.mid,r.create_time,r.invitation_mid,member.name,member.mobile,member.agent_id,member.agent_far,member.agent_pro,member.id,member.agent_rate')
            ->where(['mid'=>$mid])
            ->paginate();
			
	
		$count_m= Db::name('member_invitation_relation')->where(['mid'=>$mid])->count();//邀请用户人数
  
        $count= Db::name('agents_back_money')->where(['mid'=>$mid])->sum('affect');//已赚取佣金
        $count = round($count,2);
        $rande=$this->rande($mid);
        $url=$this->http().$_SERVER['HTTP_HOST']."/register/id/".$rande.".html";//邀请链接
        $qrcode=$this->http().$_SERVER["SERVER_NAME"]."/member/invite/view";//二维码
        $this->assign('qrcode',$qrcode);
        $this->assign('url',$url);
		$this->assign('count_m',$count_m);
        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('share',config('share_title').config('share_content'));

        $this->assign('rate', config('rebate'));
        return $this->fetch();
    }

    public function view()
    {
        $mid=MID;
        if(empty($mid)){
            $mid=$this->isLogin();
        }
        $rande=$this->rande($mid);
        $url=$this->http().$_SERVER["SERVER_NAME"]."/register/id/".$rande.".html";
        plugin_action('Qrcode/Qrcode/generate', [$url, APP_PATH.'test.png']);
    }
    /*
     * 产生id混淆数
     */
    protected function rande($mid){
        return mt_rand(10,99).$mid.mt_rand(100,999);
    }
    //返回网址前缀
    protected function http(){
        return empty($_SERVER['HTTP_X_CLIENT_PROTO']) ? 'http://' : $_SERVER['HTTP_X_CLIENT_PROTO'] . '://';
    }
	/*
	*查看邀请用户
	*/
	public function lookup(){
		$mid = MID;
		$user= get_agents_info($mid);

		//if(!$user['agent_pro'])  return json(['status' => 0, 'message' => '代理已经停用,不能设置其他代理状态']);

		$req=request();
		$look_uid = intval($this->request->param("look_uid"));
		$type_id = intval($this->request->param("type_id"));
		$rate = intval($this->request->param("agentrate"));

		 $data=$res = Db::name('member_invitation_relation as r')
            ->join('member','member.id=r.invitation_mid','LEFT')
            ->field('r.id,r.mid,r.create_time,member.name,member.mobile,member.agent_id,member.agent_far,member.agent_pro,member.agent_rate')
            ->where(['r.mid'=>$look_uid])
            ->paginate();

		$this->assign('data',$data);
		$this->assign('type_id',$type_id);
		$this->assign('look_uid',$look_uid);
		$this->assign('rate',$rate);
         return $this->fetch();
    }
	/*
	*修改用户返佣比例
	*/
	public function changRate(){
		$req=request();
		$rate = intval($this->request->param("rate"));
		$chang_uid = intval($this->request->param("chang_uid"));

        $mid = MID;
        $user= get_agents_info($mid);
        if(!$user['agent_pro'])  return json(['status' => 0, 'message' => '代理已经停用,不能设置其他代理状态']);
        $agent_id = $user['agent_id'];

		if(!$mid){
			 return json(['status' => 0, 'message' => '登陆后才能进行操作']);
		}
		if(!$agent_id){
			 return json(['status' => 0, 'message' => '请先升级代理后才能设置邀请用户为代理']);
		}
		if(!$chang_uid){
			 return json(['status' => 0, 'message' => '请求参数错误']);
		}
		
		$data['agent_rate'] = $rate;
		$data['agent_far'] = $mid;
		$data['agent_pro'] = 1;
		$data['agent_time'] = 0;
		$data['agent_id'] = $agent_id + 1;
		
		
		$member = new MemberModel();
		$res= $member->where(['id'=>$chang_uid])->update($data);
		if($res){
			 return json(['status' => 1, 'message' => '设置成功']);
		}else{
			 return json(['status' => 0, 'message' => '修改失败']);
		}
    }
	/*
	*修改代理状态
	*/
	public function changStop(){
		$req=request();
		$chang_uid = intval($this->request->param("chang_uid"));
		$agent_pro = intval($this->request->param("agent_pro"));
		
		$mid = MID;
		$user= get_agents_info($mid);
		if(!$user['agent_pro'])  return json(['status' => 0, 'message' => '代理已经停用,不能设置其他代理状态']);
		$agent_id = $user['agent_id'];
		if(!$mid){
			 return json(['status' => 0, 'message' => '登陆后才能进行操作']);
		}
		if(!$agent_id){
			 return json(['status' => 0, 'message' => '请先升级代理后才能设置邀请用户为代理']);
		}
		if(!$chang_uid){
			 return json(['status' => 0, 'message' => '请求参数错误']);
		}
		
		$member = new MemberModel();
		$data['agent_pro'] = !$agent_pro;
		$res= $member->where(['id'=>$chang_uid])->update($data);
		if($res){
			 return json(['status' => 1, 'message' => '设置成功']);
		}else{
			 return json(['status' => 0, 'message' => '修改失败']);
		}
		
		
	}
    /**
     * 邀请奖励记录
     * @return mixed
     */
    public function award()
    {
        $mid = is_member_signin();
        if(!$mid){
            $this->error('请登陆后进行申请', URL('/login'));
        }

		 $count= Db::name('agents_back_money')->where(['mid'=>$mid])->sum('affect');//已赚取佣金
         $count = round($count,2);
        $data=$res = Db::name('agents_back_money')
            ->where(['mid'=>$mid])
            ->paginate();

		$count_m= Db::name('member_invitation_relation')->where(['mid'=>$mid])->count();//邀请用户人数
		$this->assign('count_m',$count_m);
        $rande=$this->rande($mid);
        $url=$this->http().$_SERVER["SERVER_NAME"]."/register/id/".$rande.".html";
        $this->assign('url',$url);
        $qrcode=$this->http().$_SERVER["SERVER_NAME"]."/member/invite/view";
        $this->assign('qrcode',$qrcode);
        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('rate', config('rebate'));
        $this->assign('share',config('share_title').config('share_content'));
        return $this->fetch();
    }
}