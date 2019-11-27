<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui
// +----------------------------------------------------------------------

namespace app\member\home;
use app\stock\model\Borrow as BorrowModel;

use think\db;
/**
 * 我的配资控制器
 * @package app\member\home
 */
class Mock extends Common
{
    /*
     * 我的配资页
     * @return [type] [description]
     */
    public function index()
    {
        $data_list = BorrowModel::getmock(MID);//dump($data_list);
        $flag="mock";
        $this->assign('borrow', $data_list);
        $this->assign('active', 'mock');
        $this->assign('flag', $flag);
        return $this->fetch(); // 渲染模板
    }


    /*
     * 配资详情页
     * @return [type] [description]
     */
    public function details()
    {
        $mid=MID;
        $borrow_id = $this->request->get('id');
        $result = BorrowModel::getBorrowDetail($borrow_id);
        if(empty($result)){$this->error('配资没有找到');}
        $sub_id=$result->stock_subaccount_id;
        $count=Db::name('stock_position')->where(['sub_id'=>$sub_id])->where(['buying'=>0])->count();
        if($count>0){
            $this->assign('tbn',"查看实盘交易");
        }else{
            $this->assign('tbn',"去交易");
        }
        $this->assign('active', 'mock');
        $this->assign('result', $result);
        $this->assign('id', $borrow_id);
        return $this->fetch(); // 渲染模板
    }
}