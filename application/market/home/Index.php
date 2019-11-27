<?php
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 路人甲乙科技有限公司 [ http://www.lurenjiayi.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://lurenjiayi.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | @author menghui <1690611599@qq.com>
// +----------------------------------------------------------------------
namespace app\market\home;

use app\market\model\HistorySeacher as HistoryModel;
use app\market\model\StockSubAccountSelf;
use app\market\model\UpdatePostion;
use app\member\model\Member;
use app\member\model\Member as MemberModel;
use think\Db;

class Index extends Common
{

    protected function _initialize()
    {    	
        parent::_initialize();
        $token = $this->request->param("token");
        $mid   = isLogin($token);
    }
    /**
     * 交易端提供刷新使用
     */

    public function _empty()
    {

        $this->assign("mid", is_member_signin());
        $member           = is_member_signin() ? Member::getMemberInfoByID(is_member_signin()) : [];
        $member['mobile'] = count($member) == 0 ? "" : $member['mobile'];
        $this->assign("mobile", $member['mobile']);
        $this->assign("platform", config('web_operation_platform'));
        return $this->fetch("index");
    }

    public function getType()
    {
        $code = input('code');
        $type = Db::name('stock_list')->where('code', $code)->value('type');
        return $this->success("", "", ['type' => $type]);
    }

    //本地数据库股票查询
    public function stock_search()
    {
        $req = request();
        $key = $req::instance()->param('key');
        if ($key === null) {
            return json(['status' => 0, 'message' => '缺少参数key，查询失败']);
        }
        if (preg_match("/\d/", $key)) {
            $map['code'] = ['like', "%$key%"];
        }
        if (preg_match("/[A-Za-z]/", $key)) {
            $key           = strtoupper($key);
            $map['pinyin'] = ['like', "%$key%"];
        }
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $key) > 0) {
            $map['title'] = ['like', "%$key%"];
        }
        $map['status'] = 1;
        $data          = Db::name('stock_list')->field('code,title,pinyin')->where($map)->limit(10)->select();

        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        $res = null;
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $res[$k]['code'] = $v['code'];
                $res[$k]['name'] = $v['title'];
                $res[$k]['pin']  = $v['pinyin'];
            }
        }
        return json(['data' => $res, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * market 实时行情接口函数
     * 可通过get或post方式访问返回json格式数据
     * @code 必传参数 股票代码 如果查询上证指数需要用sh000001
     */
    public function market()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['data' => null, 'status' => 0, 'message' => '缺少参数code，操作失败']);
        }
        $data = z_market($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        $p_range             = $data['currency'] - $data['yesterday_price'];
        $data['price_range'] = round($p_range, 2);
        $data['price_rate']  = round(($p_range / $data['yesterday_price'] * 100), 2);

        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);

    }
    /*
     * 沪深股票涨跌实时排名接口函数
     * 可通过get或post方式访问返回json格式数据
     * $page 必传参数 页数
     * $num 每页显示数量
     * $asc 顺序 0为降序(涨幅榜) 1 为升序(降幅榜)
     */
    public function top()
    {
        $req  = request();
        $page = $req::instance()->param('page');
        if ($page == null) {
            $page = 1;
        }
        $num = $req::instance()->param('num');
        if ($num == null) {
            $num = 20;
        }
        $asc = $req::instance()->param('asc');
        if ($asc == null) {
            $asc = 0;
        }
        $data = sina_stock_top($page, $num, $asc);
        if (!$data) {
            return json(['status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 涨幅前十板块
     */
    public function top10()
    {
        $data = z_top10();
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 行业涨幅板块
     */
    public function sinahy()
    {
        $data = sinahy();
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 涨幅前个股
     */
    public function stock_top10()
    {
        $data = z_stock_top10();
        if (empty($data)) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 跌幅前个股
     */
    public function stock_bot10()
    {
        $data = z_stock_bot10();
        if (empty($data)) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }

    /*
     * 板块列表
     */
    public function industry()
    {
        $data = Db::name('stock_industry')->select();
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 板块内股票详情
     * industry_code 行业板块代码
     */
    public function industry_detail()
    {
        $req           = request();
        $industry_code = $req::instance()->param('industry_code');
        $page          = $req::instance()->param('page');
        $page          = empty($page) ? 1 : $page;
        $num           = $req::instance()->param('num');
        $num           = empty($num) ? 20 : $num;
        $total         = Db::name('stock_industry_detail')->where(["industry_code" => $industry_code])->count('id');
        if ($industry_code === null) {
            return json(['data' => null, 'status' => 0, 'message' => '缺少参数industry_code，操作失败']);
        }
        $start      = $page * $num - $num;
        $total_page = ceil($total / $num);
        $data       = Db::name('stock_industry_detail')
            ->where(["industry_code" => $industry_code])
            ->limit($start, $num)
            ->select();
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'page' => $page, 'num' => $num, 'total_page' => $total_page, 'status' => 1, 'message' => '操作成功']);
    }
    /*
     * 跌幅前十板块
     */
    public function bot10()
    {
        $data = z_bot10();
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);

    }
    /*
     * market_bat 批量实时行情接口函数
     * 可通过get或post方式访问返回json格式数据
     * @code 必传参数 多个股票代码，代码间用逗号隔开 如果查询上证指数需要用sh000001
     */
    public function market_bat()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['data' => null, 'status' => 0, 'message' => '缺少参数code，操作失败']);
        }
        $data = z_market_bat($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        foreach ($data as $k => $v) {
            $now                     = z_market($v['code']);
            $p_range                 = $now['currency'] - $now['yesterday_price'];
            $data[$k]['price_range'] = round($p_range, 2);
            $data[$k]['price_rate']  = round(($p_range / $now['yesterday_price'] * 100), 2);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }

    /*
     * add_my_select 添加我的自选函数
     * @uid 必传参数 用户在用户表id
     * @name 必传参数 股票名称
     * @code 必传参数 股票代码
     */
    public function add_my_select()
    {
        if (is_member_signin() === 0) {
            return json(['status' => 0, 'message' => '请登录！']);
        }
        $req  = request();
        $uid  = $req::instance()->param('uid');
        $name = $req::instance()->param('name');
        $code = $req::instance()->param('code');
        $self = new StockSubAccountSelf();

        if (!empty($name) && !empty($code) && $uid == MID) {
            $ret = $self->myadd($uid, $code); //查找是否存在
            if ($ret) {
                return json(['status' => 0, 'message' => '此股票已存在，添加失败']);
            }
            $res = $self->addmyselect($uid, $name, $code);
        } else {
            $res = false;
        }
        if (!$res) {
            return json(['status' => 0, 'message' => '添加失败']);
        }
        $data = $self->myadd($uid, $code);
        if (!$data) {
            return json(['status' => 0, 'message' => '系统错误']);
        }
        unset($data['uid']);
        return json(['status' => 1, 'message' => '添加成功', 'data' => $data]);
    }

    /*
     * 删除我的自选函数
     * @id 我的自选股票id
     */
    public function del_my_select()
    {
        if (is_member_signin() === 0) {
            json(['status' => 0, 'message' => '请登录！']);
        }
        $uid  = MID;
        $req  = request();
        $code = intval($req::instance()->param('code'));
        if ($code === null) {
            return json(['status' => 0, 'message' => '缺少参数code，操作失败']);
        } else {
            $self = new StockSubAccountSelf();
            $res  = $self->delmyselectbycode($uid, $code);
        }
        if (!$res) {
            return json(['status' => 0, 'message' => '删除失败']);
        }
        return json(['status' => 1, 'message' => '删除成功']);
    }

    /*
     * 删除我的自选函数 array 批量
     * @id 我的自选股票id
     */
    public function del_my_select_arr()
    {
        if (is_member_signin() === 0) {
            json(['status' => 0, 'message' => '请登录！']);
        }
        $uid = MID;
        if (!uid) {
            return json(['status' => 0, 'message' => '请登录后操作']);
        }
        $req     = request();
        $code    = trim($req::instance()->param('code'));
        $codeArr = explode(',', $code);
        if (!is_array($codeArr)) {
            return json(['status' => 0, 'message' => '参数获取错误']);
        }
        if ($codeArr === null) {
            return json(['status' => 0, 'message' => '缺少参数code，操作失败']);
        } else {
            $self = new StockSubAccountSelf();
            foreach ($codeArr as $k => $v) {
                $delcode = intval($v);
                $res     = $self->delmyselectbycode($uid, $delcode);
            }
        }
        if (!$res) {
            return json(['status' => 0, 'message' => '删除失败']);
        }
        return json(['status' => 1, 'message' => '删除成功']);
    }

    /*
     * my_select 我的自选函数
     * @uid 必传参数 用户在用户表id
     * 新增 根据自定义排序 sort
     */
    public function my_select()
    {
        if (is_member_signin() === 0) {
            json(['status' => 0, 'message' => '请登录！']);
        }
        $req = request();
        $uid = intval($req::instance()->param('uid'));

        if ($uid === null || $uid != MID) {
            return json(['data' => null, 'status' => 0, 'message' => '参数错误，操作失败']);
        }
        $data = Db::name('stock_subaccount_self')
            ->where('uid=' . $uid)
            ->order("sort asc")
            ->select();
        if (!$data || count($data) === 0) {
            return json(['data' => null, 'status' => 0, 'message' => '没有数据']);
        }
        $code = "";
        foreach ($data as $k => $v) {
            $code = $code . $v["gupiao_code"] . ',';
        }
        $code = substr($code, 0, -1);
        $info = z_market_bat($code);
        foreach ($data as $k => $item) {
            foreach ($info as $kk => $vv) {

                //涨跌幅
                $p_range                 = $vv['currency'] - $vv['yesterday_price'];
                $data[$k]['price_range'] = round($p_range, 2);
                $data[$k]['price_rate']  = round(($p_range / $vv['yesterday_price'] * 100), 2);

                if ($data[$k]["gupiao_code"] === $vv["code"]) {
                    $data[$k] = array_merge($data[$k], $vv);
                    break;
                }
            }
            unset($data[$k]['gupiao_code']);
            unset($data[$k]['gupiao_name']);
            unset($data[$k]['uid']);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }

    /*
     * A股股票列表
     */
    public function stock_list()
    {
        $req  = request();
        $page = $req::instance()->param('page');
        if ($page === null || $page < 1) {
            $page = 1;
        }
        $data['total'] = Db::name('stock_list')->where(['status' => 1])->count();
        $pnum          = 30;
        //$star = ($page - 1) * $pnum;
        $arr        = [];
        $stock_list = cache("stock_list");
        if (!$stock_list) {
            $data_arr = Db::name('stock_list')->field('id')->where(['status' => 1])->select();
            $id_arr   = [];
            foreach ($data_arr as $k => $v) {
                array_push($id_arr, $v['id']);
            }
            shuffle($id_arr);
            $stock_list = $id_arr;
            cache("stock_list", $id_arr);
        }
        $start = 30 * $page - 30;
        $end   = 30 * $page;
        for ($i = $start; $i <= $end; $i++) {
            $arr[$i] = $stock_list[$i];
        }
        //$res = Db::name('stock_list')->field('title,code,pinyin')->where(['status' => 1])->where('id', 'in', $arr)->limit($star, $pnum)->select();
        $res = Db::name('stock_list')->field('title,code,pinyin')->where(['status' => 1])->where('id', 'in', $arr)->select();
        if ($res) {
            if (!$res || count($res) === 0) {
                return $res;
            }
            $code = "";
            foreach ($res as $k => $v) {
                $code = $code . $v["code"] . ',';
            }
            $code = substr($code, 0, -1);
            $info = z_market_bat($code);
            $ret  = array();
            $n    = 0;
            foreach ($res as $k => $item) {
                foreach ($info as $kk => $vv) {
                    if ($res[$k]["code"] === $vv["code"]) {
                        if ($vv['current_price'] === "0.00" && $vv['yesterday_price'] === "0.00") {
                            break;
                        }
                        $ret[$n] = array_merge($res[$k], $vv);
                        $n++;
                        break;
                    }
                }
            }
            $data['list'] = $ret;
            $data['page'] = $page;
            $data['pnum'] = $pnum;
            return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
        }
        return json(['data' => null, 'status' => 0, 'message' => '操作失败']);
    }

    /*
     * 股票日K线数据
     * @code 必传参数 股票代码 如果查询上证指数需要用sh000001
     */
    public function day_k()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['status' => 0, 'message' => '缺少参数code，查询失败']);
        }
        $data = z_day_k($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        //均价线
        $now = z_market($code);
        foreach ($data as $k => $v) {
            $data[$k]['price_equal'] = round(($now['turnover'] / $now['volume'] * 100), 2);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);

    }

    /*
     * 股票周K线数据
     * @code 必传参数 股票代码 如果查询上证指数需要用sh000001
     */
    public function week_k()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['status' => 0, 'message' => '缺少参数code，查询失败']);
        }
        $data = z_week_k($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        //均价线
        $now = z_market($code);
        foreach ($data as $k => $v) {
            $data[$k]['price_equal'] = round(($now['turnover'] / $now['volume'] * 100), 2);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);

    }

    /*
     * 股票月K线数据
     * @code 必传参数 股票代码 如果查询上证指数需要用sh000001
     */
    public function month_k()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['status' => 0, 'message' => '缺少参数code，查询失败']);
        }
        $data = z_month_k($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        //均价线
        $now = z_market($code);
        foreach ($data as $k => $v) {
            $data[$k]['price_equal'] = round(($now['turnover'] / $now['volume'] * 100), 2);
        }
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);

    }

    /*
     * 股票分时K线数据
     * @code 必传参数 股票代码 如果查询上证指数需要用sh000001
     */
    public function minute_k()
    {
        $req  = request();
        $code = $req::instance()->param('code');
        if ($code === null) {
            return json(['status' => 0, 'message' => '缺少参数code，查询失败']);
        }
        $data = z_minute_k($code);
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        //均价线
        $now = z_market($code);
        foreach ($data as $k => $v) {
            $data[$k]['price_equal'] = round(($now['turnover'] / $now['volume'] * 100), 2);
        }

        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }

    /*
     * 股票关键字查询数据
     * @key 必传参数 key可以是股票数字代码
     * 也可以是股票拼音代码 当为汉字时只有新浪数据有效
     */
    public function search_keyword()
    {
        $req = request();
        $key = $req::instance()->param('key');
        if ($key === null) {
            return json(['status' => 0, 'message' => '缺少参数key，查询失败']);
        }
        $data     = z_search_keyword($key);
        $list_res = Db::name('stock_list')->field('code')->where(['status' => 1])->select();
        $res      = [];
        foreach ($list_res as $k => $v) {
            $res[$k] = $v['code'];
        }
        foreach ($data as $k => $v) {
            if (!in_array($v['code'], $res)) {
                unset($data[$k]);
                continue;
            }
            if (strlen($v['pin']) >= 5) {
                unset($data[$k]);
            }
            if (strlen($v['code']) == 5) {
                unset($data[$k]);
            }
        }
        if (!$data) {
            return json(['data' => $data, 'status' => 0, 'message' => '操作失败']);
        }
        $data = array_values($data);
        return json(['data' => $data, 'status' => 1, 'message' => '操作成功']);
    }

    public function actlogin()
    {
        if (!module_config("member.member_is_login")) {
            $this->error("系统关闭了登录", url('/'));
        }
        $data   = $this->request->post();
        $result = $this->validate($data, 'Member.signin');
        if (true !== $result) {
            return json(["msg" => $result, "code" => 0]);
        }
        $MemberModel = new MemberModel;
        $mid         = $MemberModel->login($data['mobile'], $data['password']);
        if ($mid) {
            return json(["msg" => '登录成功', "code" => 1, "data" => is_member_signin()]);
        } else {
            return json(["msg" => $MemberModel->getError(), "code" => 0]);
        }
    }

    /**
     * 退出登录
     */

    public function signout()
    {
        $data = $this->request->post();
        if ($data['action'] == 'signout') {
            session(null);
            return json(["message" => "退出成功", "status" => 1]);
        }
        return json(["message" => "退出失败", "status" => 0]);
    }

    /**
     * 获取子账号信息
     */
    public function getSubAccount()
    {
        $token = $this->request->param("token");
        $mid   = isLogin($token);
        if (!$mid) {
            return json(['status' => 0, 'message' => '登陆后才能进行查看']);
        }

        $account     = Db::name('stock_subaccount')->field('id,uid,sub_account,status')->where(['status' => 1, 'uid' => $mid])->select();
        $time        = time() + 604800;
        $borrow_info = Db::name('stock_borrow')
            ->field('stock_subaccount_id')
            ->where(['status' => 2, 'member_id' => $mid])
            ->where('end_time', '<', $time)
            ->select();
        if (!empty($borrow_info)) {
            $res = [];
            foreach ($borrow_info as $k => $v) {
                array_push($res, $v['stock_subaccount_id']);
            }
            foreach ($account as $key => $value) {
                if (in_array($value['id'], $res)) {
                    unset($account[$key]);
                }
            }
            $account = array_values($account);
        }
        return json(['data' => $account, 'status' => 1, 'message' => '子账号信息']);
    }
    /**
     * 搜索历史查询
     * $history 记录
     */
    public function getHistory_secher()
    {
        $history = Db::name('stock_history')->field('*')->where(['status' => 1])->order('count desc')->limit(9)->select();
        return json(['data' => $history, 'status' => 1, 'message' => '子账号信息']);
    }

    /*
     * 保存查询记录
     * 如果登陆保存token
     */
    public function addHistory()
    {
        $req          = request();
        $code         = $req::instance()->param('code');
        $code_title   = $req::instance()->param('code_title');
        $mid          = MID;
        $HistoryModel = new HistoryModel;
        $res          = $HistoryModel->add_histtory($code, $code_title, $mid);

        if ($res) {
            return json(['status' => 1, 'message' => '操作成功']);
        } else {
            return json(['status' => 0, 'message' => '操作失败']);
        }
    }

    /**
     * 我的自选 拖动|| 置顶
     */
    public function setSelf_postion()
    {
        $req         = request();
        $settop      = $req::instance()->param('arr'); //字符串
        $update_slef = new UpdatePostion();
        $res         = $update_slef->update_slef_postion($settop);
        if ($res) {
            return json(['status' => 1, 'message' => '操作成功']);
        } else {
            return json(['status' => 0, 'message' => '操作失败']);
        }
    }
}
