<?php
    namespace app\member\model;
    use think\model;
    use think\Db;
    class Irecord extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MEMBER_INVITATION_RECORD__';

        // 自动写入时间戳
        protected $autoWriteTimestamp = true;

        /**
         * 保存推荐奖励
         * @param  [type] $data [description]
         * @return [type]       [description]
         * @author gs101
         */
        public static function saveData($data)
        {
            $sdata['mid'] = $data['mid'];
            $sdata['money'] = $data['money']*100;//单位分
            $sdata['remark'] = $data['remark'];
            $sdata['create_time'] = time();
            $result = self::create($sdata);
            if($result->id){
                return ['status'=>1, 'message'=>'注册成功'];
            }else{
                return ['status'=>0, 'message'=>'奖励失败'];
            }
        }
    
    }
?>
