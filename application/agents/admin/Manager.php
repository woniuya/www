<?php
// +----------------------------------------------------------------------
// | 系统框架
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站：http://www.lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\agents\admin;
use app\admin\controller\Admin;
use app\member\model\Member as MemberModel;
use app\common\builder\ZBuilder;
use think\Hook;
use think\Db;
class Manager extends Admin{
    /*
     * 代理商列表
     */
    public function agent_list(){
        cookie('__forward__', $_SERVER['REQUEST_URI']);
        // 获取查询条件
        $map = $this->getMap();  
        $order = $this->getOrder();
//        $data_list = MemberModel::getAgentList($map,$order);
        $data_list = Db::name('member')
            ->where($map)
            ->where('agent_id = 1')
            ->order($order)
            ->paginate()
            ->each(function($item, $key){
                $count=Db::name('member_invitation_relation')->where(["mid"=>$item['id']])->count();
                $item['count_num'] = $count ? $count : 0;
                return $item;
            });
        // 分页数据
        $page = $data_list->render();
        return ZBuilder::make('table')
            ->setTableName('member')
            ->setSearch(['name' => '姓名', 'mobile'=>'手机号'],'','',true) // 设置搜索框
            ->addOrder('create_time,id desc') // 添加排序
            ->hideCheckbox()
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['mobile', '手机号'],
                ['name', '姓名'],
                ['agent_id','代理等级', 'text','',[0=>'普通会员',1=>'一级代理',2=>'二级代理',3=>'三级代理']],
                ['count_num', '名下会员数','link', url('application_invite', ['search_field' => 'mobile', 'keyword' => '__mobile__'])],
                ['agent_pro', '代理状态', [0=>'停止', 1=>'正常']],
                ['create_time', '注册时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
			->addTopButton('add') // 批量添加顶部按钮
            ->addRightButtons(['edit']) // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }
    /*
     * 新增代理商
     */
    public function add(){
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['pid']=0;
			//设置代理 不设置rate 默认后台配置rate
			 if ($data['agent_rate'] == 0) {
                $data['agent_rate'] = config('agent_back_rate');
            }
            if($data['agent_rate']>100){
                $this->error('返佣比例过高，请重新设置');
            }
            if($data['agent_rate']<0){
                $this->error('返佣比例过低，请重新设置');
            }
            $result = $this->validate($data, 'agents.create');
            true !== $result  && $this->error($result);
			//判断是否符合设置代理情况 已经是下级代理 则不能设置一级代理
			$user = MemberModel::getMemberInfoByMobile($data['mobile']);
			//几种不能设置一级代理商的情况 不存在 已经是代理商 上级代理是代理商
			if(empty($user))  $this->error('该会员信息未找到');
			if($user['agent_id']) $this->error('该会员已经是代理商，不能重复添加');
			if(!$user['id_card']) $this->error('未实名不能进行设置代理商操作');
			if($user['agent_far']) {
				$agent_in = MemberModel::getMemberInfoByID($user['agent_far']);
				if($agent_in['agent_id']){
					$this->error('该用户不允许添加为一级代理商');
				}
			}
			$data['id'] = $user['id'];
			$data['agent_time'] = 0;//取消返佣期限限制
            if (MemberModel::update($data)) {
                $member = MemberModel::get($data['id']);
                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['mobile']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $agent_rate = config('agent_back_rate');
        $agent_list[0]="一级代理商";
		$html = "<div class='form-group dowmList' id='dowmList'></div>";
		return ZBuilder::make('form')
            ->setPageTitle('添加代理商') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['text:5', 'mobile', '用户名/手机号', ''],
				['text:5', 'agent_rate', '返佣比例', '',$agent_rate],
				['radio', 'agent_pro', '代理状态', '', [0=>'禁用', 1=>'启用'],'1'],
				['hidden', 'agent_id', '1'],
            ])
			->js('downlist')
			->css('downlist')
			->setExtraHtml($html, 'form_top')
			->addStatic('name', '代理商级别', '', '一级代理商')
            ->fetch();
    }
	public function getLikeMobile($mobile){
		$map = " agent_id = 0 and mobile like '%{$mobile}%'";
		 $member=Db::name('member')->field('id,mobile')->where($map)->limit(10)->select();
		return $member;
	}
     /*
     * 代理商编辑
     */
    public function edit($id=null){
        if ($id === null) $this->error('缺少参数');
      // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['pid']=0;
            // 验证
			//设置代理 不设置rate 默认后台配置rate
			 if ($data['agent_rate'] == 0) {
                $data['agent_rate'] = config('agent_back_rate');
            }
            if($data['agent_rate']>100){
                $this->error('返佣比例过高，请重新设置');
            }
            if($data['agent_rate']< 0){
                $this->error('返佣比例过低，请重新设置');
            }

            $result = $this->validate($data, 'agents.create');
            true !== $result  && $this->error($result);

            if (MemberModel::update($data)) {
                $member = MemberModel::get($data['id']);
                Hook::listen('member_edit', $member);
                // 记录行为
                action_log('member_edit', 'member', $member['id'], UID, $data['mobile']);
                $this->success('编辑成功', cookie('__forward__'));
            } else {
                $this->error('编辑失败');
            }
        }
        $info = MemberModel::where(['id'=>$id])->find();
        $arr=[0=>'普通会员',1=>'一级代理',2=>'二级代理',3=>'三级代理'];
        if(!empty($info)){
            $info['class']=$arr[$info['agent_id']];
        }
        if($info['agent_id']==1){
            return ZBuilder::make('form')
                ->setPageTitle('编辑代理商') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['static:12', 'mobile', '用户名/手机号', ''],
                    ['static:12', 'name', '姓名', ''],
                    ['static:12','class','代理等级', '', ],
                    ['text:12', 'agent_rate', '返佣比例', ''],
                    ['radio:6', 'agent_pro', '代理状态', '', ['禁用', '启用']],
                    ['hidden', 'agent_id', '1'],
                    ['hidden', 'id', ''],
                ])
                ->setFormData($info) // 设置表单数据
                ->fetch();
        }else{
            return ZBuilder::make('form')
                ->setPageTitle('编辑代理商') // 设置页面标题
                ->addFormItems([ // 批量添加表单项
                    ['static:6', 'mobile', '用户名/手机号', ''],
                    ['static:6', 'name', '姓名', ''],
                    ['static:6','class','代理等级', '', ],
                    ['text:6', 'agent_rate', '返佣比例', ''],
                    ['radio:6', 'agent_pro', '代理状态', '', ['禁用', '启用']],
                    ['hidden', 'agent_id', '1'],
                    ['hidden', 'id', ''],
                ])
                ->setFormData($info) // 设置表单数据
                ->fetch();
        }

    }
    /*
     * 盈利分成明细
     */
    public function agent_share(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($map['r.create_time'][1][0])){
            $beginday=date('Ymd',time()-2592000);//30天前
        }else{
            $beginday=date('Ymd',strtotime($map['r.create_time'][1][0]));
        }
        if(empty($map['r.create_time'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['r.create_time'][1][1]));
        }
        $info=Db::view('member u',"mobile")
            ->view('agents_back_money r','*','r.mid=u.id','right')
            ->where($map)
            ->order($order)
            ->paginate()
            ->each(function($item, $key){
               // $item['money_a'] = $item['money_a']/100;
                return $item;
            });

        $page=$info->render();
        if(empty($_SERVER["QUERY_STRING"])){
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"],0,-5)."_export";
        }else{
            $excel_url=substr(http().$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"],0,-5)."_export.html?".$_SERVER["QUERY_STRING"];
        }
        $btn_excel = [
            'title' => '导出EXCEL表',
            'icon'  => 'fa fa-fw fa-download',
            'href'  => url($excel_url,'','')
        ];
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
				['mobile', '代理手机号'],
                ['affect_mobile', '奖励来源'],
                ['money_a', '资金管理费'],
                ['rate', '分成比例'],
                ['affect', '佣金金额'],
                ['info', '信息'],
                ['create_time', '发生时间','datetime'],
            ])

            ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
            ->addTopButton('custom', $btn_excel)
            ->setSearch(['mobile' => '代理手机号'],'','',true) // 设置搜索参数
            ->setRowList($info) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
    public function agent_share_export(){
        $map = $this->getMap();
        $order = $this->getOrder();

        // 数据列表
        $xlsData = $info=Db::name('member')
            ->alias('u')
            ->join('agents_back_money r','r.mid=u.id','RIGHT')
            ->field('u.mobile,r.*')
            ->where($map)
            ->order($order)
            ->paginate();
        $test=[];
        foreach ($xlsData as $k=>$v){
            $v['create_time']=date('Y-m-d H:i:s',$v['create_time']);
            $test[$k]['create'] =  $v['create_time'];
            $test[$k] =$v;
        }

        $title="佣金分成明细";
        $arrHeader = array('代理手机号','奖励来源','资金管理费','分成比例','佣金金额','信息','发生时间');
        $fields = array('mobile','affect_mobile','money_a','rate','affect','info','create_time');
        export($arrHeader,$fields,$test,$title);
    }
    /*
     * 代理提现记录
     */
    public function application_record(){
        $map = $this->getMap();
        $order = $this->getOrder();
        if(empty($map['r.create_time'][1][0])){
            $beginday=date('Ymd',time()-2592000);//30天前
        }else{
            $beginday=date('Ymd',strtotime($map['r.create_time'][1][0]));
        }
        if(empty($map['r.create_time'][1][1])){
            $endday=date('Ymd',time());
        }else{
            $endday=date('Ymd',strtotime($map['r.create_time'][1][1]));
        }
        $info=Db::view('member u',"id,mobile,agent_id")
            ->view('money_withdraw r','*','r.mid=u.id','right')
            ->where($map)
            ->where(['r.status'=>1])
            ->where('u.agent_id','<>',0)
            ->order($order)
            ->paginate();
        $page=$info->render();
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '代理手机号'],
                ['money', '提现金额',"callback","money_convert"],
                ['fee', '手续费',"callback","money_convert"],
                ['create_time', '申请时间','datetime'],
                ['agent_id', '代理等级', 'text','',[0=>'普通会员',1=>'一级代理',2=>'二级代理',3=>'三级代理']],
            ])
            ->addTimeFilter('r.create_time', [$beginday, $endday]) // 添加时间段筛选
            ->setSearch(['mobile' => '代理手机号'],'','',true) // 设置搜索参数
            ->setRowList($info) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
    }
	/*
     * 邀请用户
     */
    public function application_invite(){
        $map = $this->getMap();
        $order = $this->getOrder();

            $search_field = input('param.search_field/s', '');
            $getMobile =  input('param.keyword/s', '');
            if($getMobile  && $search_field=='invitation_mid'){
                $info=Db::name('member')->field('id,mobile,name,agent_id,create_time')->where('mobile',$getMobile)->find();
                $map['invitation_mid'] = $info['id'];
            }

        $info = Db::name('member_invitation_relation r')
            ->join('member m','m.id = r.mid')
            ->where($map)
            ->order($order)
            ->paginate()
            ->each(function($item, $key){
                $arr=Db::name('member')->field('id,mobile,name,agent_id,create_time')->where('id',$item['invitation_mid'])->find(); //16 17
                $item['name'] = $arr['name'];
                $item['invitation_mid'] = $arr['mobile'];
                $item['agent_id'] = $arr['agent_id'];
                $item['create_time'] = $arr['create_time'];
            return $item;
        });

        $page=$info->render();
        return ZBuilder::make('table')
            ->hideCheckbox()
            ->addColumns([ // 批量添加列
                ['mobile', '邀请人'],
                ['invitation_mid', '被邀请人','text',''],
                ['name', '姓名'],
                ['create_time', '注册时间','datetime'],
                ['agent_id', '代理等级', 'text','',[0=>'普通会员',1=>'一级代理',2=>'二级代理',3=>'三级代理']],
            ])
            ->setSearch(['mobile' => '邀请人','invitation_mid'=>'被邀请人'],'','',true) // 设置搜索参数
            ->setRowList($info) // 设置表格数据
            ->setPages($page) // 设置分页数据
            ->fetch();
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
}