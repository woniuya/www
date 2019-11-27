<?php
    namespace app\money\model;
    use think\helper\Hash;
    use app\money\model\Role as RoleModel;
    use think\model;
    use think\Db;
    
    class Record extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MONEY_RECORD__';

        protected function setCreateIpAttr($value)
        {
            return get_client_ip(1);    
        }
        protected $type = [
                '1'=>'充值成功',
                '2'=>'提现冻结',
                '3'=>'提现成功',
                '4'=>'提现失败',
                '5'=>'撤销提现',
                '6'=>'提现退回',
                '7'=>'追加保证金',
                '8'=>'冻结保证金',
                '9'=>'返还保证金',
                '10'=>'邀请人推广返佣',
                '11'=>'代理商佣金入账',
                '12'=>'代理商分成入账',
                '13'=>'提取佣金',
                '14'=>'提取分成',
                '15'=>'终止配资',
                '16'=>'扣配资管理费',
                '17'=>'扣递延费',
                '18'=>'后台转账',
                '19'=>'管理员操作',
                '20'=>'配资结算',
                '21'=>'配资审核不通过',
                '22'=>'配资审核通过',
                '23'=>'申请配资续期',
                '24'=>'扩大配资审核通过',
                '25'=>'扩大配资审核未通过',
                '26'=>'追加保证金审核通过',
                '27'=>'追加保证金审核未通过',
                '28'=>'配资续期审核通过',
                '29'=>'配资续期审核未通过',
                '30'=>'配资提前终止审核通过',
                '31'=>'配资提前终止审核未通过',
                '32'=>'按月配资手续费自动扣款',
                '33'=>'冻结金额',
                '34'=>'扣除金额',
                '85'=>'提取盈利'
                ];
        public function getTypeAttr($value)
        {
            
            return $this->type[$value];
        }
        public function getTypeAll(){
           return $this->type;
    }

        /**
         * 添加资金记录
         * @param [type] $mid     [description] 会员id
         * @param [type] $affect  [description] 影响金额
         * @param [type] $type    [description] 资金类型
         * @param [type] $account [description] 剩余可用资金
         */
        public function saveData($mid, $affect, $account, $type, $info='')
        {
            $record['mid'] = $mid;
            $record['affect'] = $affect;
            $record['account'] = $account;
            $record['type'] = $type; 
            $record['info'] = $info; 
            $record['create_time'] = time();
            $record['create_ip'] = get_client_ip(1);
            $res = self::create($record); // 资金记录

            return $res;
        }

        public static function getAll($map=[], $order='',$listRows=20)
        {
            $data_list = self::view('money_record r', true)
                        ->view('member m', 'mobile, name,id_card,create_time as m_time', 'm.id=r.mid', 'left')
                        ->where($map)
                        ->order($order)
                        ->paginate($listRows, false, ['query' => request()->param(),])
                        ->each( function($item, $key){
                            $item->account = money_convert($item->account);
                            $item->affect = money_convert($item->affect);
                            $item->type = $item->type;
                        });
            return $data_list;
        }

    }
?>
