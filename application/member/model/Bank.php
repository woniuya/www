<?php
    namespace app\member\model;
    use think\model;
    use app\member\home\Bank as bnk;
    use think\Db;
    class Bank extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MEMBER_BANK__';

        // 自动写入时间戳
        protected $autoWriteTimestamp = true;

        public static function saveData($data)
        {
            $data['create_ip'] = get_client_ip(1);
            $result = self::create($data);

            if($result->id){
                return ['status'=>1, 'message'=>'添加成功'];
            }else{
                return ['status'=>0, 'message'=>'添加失败'];
            }
        }
        /*
         * 保存编辑数据
         *
         */
        public static function upEdit($data)
        {
            $data['create_ip'] = get_client_ip(1);
            $result = self::where(['id'=>$data['id']])->update($data);
            if($result){
                return ['status'=>1, 'message'=>'修改成功'];
            }else{
                return ['status'=>0, 'message'=>'修改失败'];
            }
        }

        public static function getBank($mid)
        {
            $mid = intval($mid);
            $banks = self::where(['mid'=>$mid])->select();
            return $banks;
        }

        public static function bankInfo($bank_id)
        {      $bnk = new bnk;
            $bnk = $bnk->bankres();

            $id = intval($bank_id);
            $bank = self::where(['id'=>$id])->find();
            //$web_bank = config('web_bank');
			$bank['bank_name'] = $bank['bank'];
            $bank['bank'] = $bnk[$bank['bank']];
			$bank['provinces_id'] = $bank['province'];
			$bank['city_id'] = $bank['city'];
            $province = get_area(null, $bank['province']);
            $bank['provinces'] =$bank['province'];
            $bank['province'] = $province['name'];
            $city = get_area(null, $bank['city']);
            $bank['city'] = $city['name'];

            return $bank;
        }
        //
        public static function del_bank($id){
           return self::where(['id'=>$id])->delete();
        }
    
    }
?>
