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
use app\common\builder\ZBuilder;
use think\Hook;
use think\Db;
class Agentset extends Admin{
    /*
     *
     */
    public function index(){

        $data_list['member_back_rate'] = config('member_back_rate');
        $data_list['member_back_time'] = config('member_back_time');
        $data_list['agent_back_rate'] = config('agent_back_rate');
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $counts = count($data);
            $newdata = array_keys($data);
            $z='';
            for($i=0;$i<$counts;$i++){
                Db::name('admin_config')->where('name',$newdata[$i])->setField('value',$data[$newdata[$i]]);
               $z++;
            }
            if($z==3){
                cache('system_config', null);
                $this->success('处理成功', null, 'index');

            }else{
                $this->error('处理失败');

            }


        }
        return ZBuilder::make('form')

            ->setPageTitle('代理返利设置')// 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['text', 'member_back_rate', '普通用户佣金比例', '普通用户佣金比例member_back_rate (单位 %)'],
                ['text', 'member_back_time', '普通用户返佣期限', '普通用户返佣期限 member_back_time (单位 月)'],
                ['text', 'agent_back_rate', '一级代理返佣比例', '一级代理返佣比例 (单位 %)']
            ])
            ->setFormData($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

}