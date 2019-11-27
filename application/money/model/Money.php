<?php
    namespace app\money\model;
    use think\helper\Hash;
    use app\money\model\Role as RoleModel;
    use think\model;
    use think\Db;
    
    class Money extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MONEY__';


        public static function  getAll($map=[], $order='')
        {
            $data_list = self::view('money', true)
                        ->view('member', 'mobile, name, id_card', 'member.id=money.mid', 'left')
                        ->where($map)
                        ->order($order)
                        ->paginate()
                        ->each( function($item, $key){
                            $item->account = money_convert($item->account);
                            $item->freeze = money_convert($item->freeze);
                            $item->operate_account = money_convert($item->operate_account);
                            $item->bond_account = money_convert($item->bond_account);
                        });
       
            return $data_list;

        }

        public static function getMoney($mid)
        {
            $result = Db('money')->where(['mid'=>$mid])->find();
            if(empty($result)){
                $result['account']=0;
                $result['freeze']=0;
                $result['operate_account']=0;
                $result['bond_account']=0;
                $result['total']=0;
            }else{
                $result['total'] = bcadd($result['bond_account'], bcadd($result['account'], $result['freeze']));
            }
            return $result;
        }

        /*
         * 申请时冻结资金
         * @mid 会员ID
         * @minfo 会员资金信息
         * @money 冻结金额
         * */
        public static function money_freeze($mid,$freeze,$account)
        {
            return self::where(['mid'=>$mid])->update(['freeze'=>$freeze,'account'=>$account]);
        }
        /*
         * 申请通过更新资金
         *
         */
        public static function money_up($mid,$mmoney){
            return self::where(["mid"=>$mid])->update($mmoney);
        }

    }
?>
