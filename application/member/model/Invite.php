<?php
    namespace app\member\model;
    use think\helper\Hash;
    use app\member\model\Role as RoleModel;
    use think\model;
    use think\Db;
    
    class Invite extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MEMBER_INVITATION_RELATION__';

        // 自动写入时间戳
        protected $autoWriteTimestamp = true;

        /**
         * 保存推荐关系数据
         * @param  [type] $data [description]
         * @return [type]       [description]
         * @author gs101
         */
        public static function saveData($data)
        {
            $sdata['invitation_mid'] = $data['id'];
            $sdata['mid'] = $data['mid'];
            $sdata['create_time'] = time();
            $result = self::create($sdata);
            if($result->id){
                return ['status'=>1, 'message'=>'注册成功'];
            }else{
                return ['status'=>0, 'message'=>'注册失败'];
            }
        }
    
    }
?>
