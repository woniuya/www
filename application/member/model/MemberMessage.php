<?php
    namespace app\member\model;
    use think\model;
    use think\Db;
    class MemberMessage extends Model
    {
        
        // 设置当前模型对应的完整数据表名称
        protected $table = '__MEMBER_MESSAGE__';

        // 自动写入时间戳
        protected $autoWriteTimestamp = true;

		/**
         * 新增站内信信息
         * @param int $mid 会员ID
         * @param string $tiyle 站内信标题
         * @param array $info 站内信内容
         * @param int $type （预留）
         * @author 路人甲乙
         * @return mixed
         */
        public function addInnerMsg($mid=null,$title,$info,$type=0)
        {
            $data['mid'] = $mid;
            $data['title'] = $title;
            $data['info'] = $info;
            $data['type'] = $type;
            $data['status'] = 0;//站内信查看状态，默认为未查看0

            $result = self::create($data);

            return $result;
        }
        public static function getAll($map=[], $order='', $listRow)
        {
            $data_list = self::view('member_message mm', true)
                ->view('member m', 'mobile, name, id_card', 'm.id = mm.mid')
                ->where($map)
                ->order($order)
                ->paginate($listRow, false, [
                    'query' => input('param.')
                ]);
            return $data_list;
        }
    
    }
?>
