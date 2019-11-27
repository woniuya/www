<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author 路人甲乙
// +----------------------------------------------------------------------

namespace app\money\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\admin\model\Config as ConfigModel;
use app\admin\model\Module as ModuleModel;
use think\Db;

/**
 * 充值管理控制器
 * @package app\money\admin
 */
class withdrawbankset extends Admin
{
    /**
     * 提现银行设置
     *
     *
     * @return mixed
     */
    public function index()
    {
        $data_list = Db('admin_config')->where('name', 'web_bank')->value('value');
        //值转数组
        $array = preg_split('/[,;\r\n]+/', trim($data_list, ",;\r\n"));
        if (strpos($data_list, '|')) {
            $res = array();
            $is = 0;
            foreach ($array as $key => $val) {
                $res[$is] = explode('|', $array[$key]);
                foreach ($res[$is] as $key1 => $val1) {
                    list($k, $v) = explode(':', $val1);
                    $res[$is][$k] = $v;
                    unset($res[$is][$key1]);
                }
                $is++;
            }
        }
        $result = array();
        foreach ($res as $k => $v) {
            $result[$k] = array_keys($res[$k]);
            unset($result[$k][1]);
            $result[$k]['code'] = $result[$k][0];
            unset($result[$k][0]);
            $result[$k]['name'] = $res[$k][$result[$k]['code']];
            $result[$k]['img'] = $res[$k]['img'];
            $result[$k]['id'] = $k+1;
        }
        // dump($result);
        $css = <<<EOF
           <style>
               .tab-pane{
                    width:70%;
               }
           </style>
EOF;
        return ZBuilder::make('table')

            ->addColumns([ // 批量添加数据列
                ['id', 'id'],
                ['code', '银行缩写'],
                ['name', '银行名称'],
                ['img', '银行LOGO','picture'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add') // 批量添加顶部按钮
            ->addRightButton('edit')
            ->addRightButton('delete')
            ->setRowList($result)
            ->setExtraCss($css)
            ->fetch();
    }
   public function add(){
       if ($this->request->isPost()) {
           $data = $this->request->post();
           $newdata = array();
           $data['code'] = strtoupper($data['code']);
           $newdata[$data['code']] = $data['name'];
           $newdata['img'] = $data['bank_img'];

           $data_list = Db('admin_config')->where('name', 'web_bank')->value('value');
           //值转数组
           $array = preg_split('/[,;\r\n]+/', trim($data_list, ",;\r\n"));
           if (strpos($data_list, '|')) {
               $res = array();
               $is = 0;
               foreach ($array as $key => $val) {
                   $res[$is] = explode('|', $array[$key]);
                   foreach ($res[$is] as $key1 => $val1) {
                       list($k, $v) = explode(':', $val1);
                       $res[$is][$k] = $v;
                       unset($res[$is][$key1]);
                   }
                   $is++;
               }
           }
           array_push($res, $newdata);
            // 数组转字符串
           $z = 0;
           foreach ($res as $keys => $values) {
               foreach ($values as $keys1 => $values1) {
                   if ($z > 1) {
                       $z = 0;
                   }
                   $res[$keys][$z] = $keys1 . ':' . $values1;
                   unset($res[$keys][$keys1]);
                   $z++;
               }
           }
           foreach ($res as $keys => $values) {
               $res[$keys] = implode('|', $res[$keys]);
           }
           $res = implode(PHP_EOL, $res);
           $result_up = Db::name('admin_config')->where('name', 'web_bank')->setField('value', $res);

           if ($result_up === 1) {
               $this->success('添加成功', 'index');
           } else {
               $this->error($result_up);
           }
       }
       // 使用ZBuilder快速创建表单
       return ZBuilder::make('form')
           ->setPageTitle('新增')// 设置页面标题
           ->addFormItems([ // 批量添加表单项
               ['text', 'code', '银行代号', '必填'],
               ['text', 'name', '银行名称', '必填'],
               ['image', 'bank_img', '银行图标', '必填']
           ])
           ->fetch();

   }
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数', null , '_close_pop');

        $data_list = Db('admin_config')->where('name', 'web_bank')->value('value');
        //值转数组
        $array = preg_split('/[,;\r\n]+/', trim($data_list, ",;\r\n"));

        $id = --$id;

        if (strpos($data_list, '|')) {
            $res = array();
            $is = 0;
            foreach ($array as $key => $val) {
                $res[$is] = explode('|', $array[$key]);
                foreach ($res[$is] as $key1 => $val1) {
                    list($k, $v) = explode(':', $val1);
                    $res[$is][$k] = $v;
                    unset($res[$is][$key1]);
                }
                $is++;
            }

        }
        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $data['code'] = strtoupper($data['code']);
            $do_arr=array();
            $do_arr[$data['code']] = $data['name'];
            $do_arr['img'] = $data['img'];
            $res[$data['id']]=$do_arr;
            //数组还原字符串
            $z = 0;
            foreach ($res as $keys => $values) {
                foreach ($values as $keys1 => $values1) {
                    if ($z > 1) {
                        $z = 0;
                    }
                    $res[$keys][$z] = $keys1 . ':' . $values1;
                    unset($res[$keys][$keys1]);
                    $z++;
                }
            }
            foreach ($res as $keys => $values) {
                $res[$keys] = implode('|', $res[$keys]);
            }
            $res = implode(PHP_EOL, $res);
            //数组还原字符串结束
            $result_up = Db::name('admin_config')->where('name', 'web_bank')->setField('value', $res);
            if(false === $result_up){
                $this->error($result_up);
            }else {
                $this->success('处理成功','index');
            }
        }

        $result = array();
        foreach ($res as $k => $v) {
            $result[$k] = array_keys($res[$k]);
            unset($result[$k][1]);
            $result[$k]['code'] = $result[$k][0];
            unset($result[$k][0]);
            $result[$k]['name'] = $res[$k][$result[$k]['code']];
            $result[$k]['img'] = $res[$k]['img'];
            $result[$k]['id'] = $k;
        }
        //dump($result[$id]);exit;
        return ZBuilder::make('form')
            ->setPageTitle('修改银行卡')// 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['text', 'code', '银行缩写'],
                ['text', 'name', '银行名称',],
                ['image', 'img', '银行图标']
            ])

            ->setFormData($result[$id])// 设置表单数据
            ->fetch();

    }

    public function delete($id = null){
        $id = $this->request->param('ids');
        if ($id === null) $this->error('缺少参数', null , '_close_pop');
        $id=--$id;

        $data_list = Db('admin_config')->where('name', 'web_bank')->value('value');
        //值转数组
        $array = preg_split('/[,;\r\n]+/', trim($data_list, ",;\r\n"));

        if (strpos($data_list, '|')) {
            $res = array();
            $is = 0;
            foreach ($array as $key => $val) {
                $res[$is] = explode('|', $array[$key]);
                foreach ($res[$is] as $key1 => $val1) {
                    list($k, $v) = explode(':', $val1);
                    $res[$is][$k] = $v;
                    unset($res[$is][$key1]);
                }
                $is++;
            }
        }
         array_splice($res,$id,1);

        $z = 0;
        foreach ($res as $keys => $values) {
            foreach ($values as $keys1 => $values1) {
                if ($z > 1) {
                    $z = 0;
                }
                $res[$keys][$z] = $keys1 . ':' . $values1;
                unset($res[$keys][$keys1]);
                $z++;
            }
        }
        foreach ($res as $keys => $values) {
            $res[$keys] = implode('|', $res[$keys]);
        }

        $res = implode(PHP_EOL, $res);

        $result = Db::name('admin_config')->where('name', 'web_bank')->setField('value', $res);
        if($result===1){
            $this->success('删除成功','index');
        }else {
            $this->error($result);
        }
    }

}