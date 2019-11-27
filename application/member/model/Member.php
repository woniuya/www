<?php
namespace app\member\model;

use think\Db;
use think\helper\Hash;
use think\model;

class Member extends Model
{

    // 设置当前模型对应的完整数据表名称
    protected $table = '__MEMBER__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswdAttr($value)
    {
        return Hash::make((string) $value);
    }

    public function setPaywdAttr($value)
    {
        return Hash::make((string) $value);
    }

    // 获取注册ip
    public function setAddIpAttr()
    {
        return get_client_ip(1);
    }

    /**
     * 自动登录
     * @param object $member 用户对象
     * @author 路人甲乙 <4853332099@qq.com>
     * @return bool|int
     */
    public function autoLogin($member)
    {
        // 记录登录SESSION和COOKIES
        $auth = array(
            'mid'             => $member->id,
            'mobile'          => $member->mobile,
            'last_login_time' => $member->last_login_time,
            'last_login_ip'   => get_client_ip(1),
        );
        session('member_auth', $auth);
        session('member_auth_sign', data_auth_sign($auth));
        return $member->id;
    }

    /**
     * 用户登录
     * @param string $mobile 手机号
     * @param string $password 密码
     * @author 张继立 <404851763@qq.com>
     * @return bool|mixed
     */
    public function login($mobile = '', $password = '')
    {
        $mobile   = trim($mobile);
        $password = trim($password);

        if (preg_match("/^1\d{10}$/", $mobile)) {
            // 手机号登录
            $map['mobile'] = $mobile;
        } else {
            $this->error = '请用手机号码登录';
            return false;
        }

        $map['status'] = 1;
        $map['is_del'] = 0;
        // 查找用户
        $member = $this::get($map);
        if (!$member) {
            $this->error = '用户不存在或被禁用！';
        } else {
            if (!Hash::check((string) $password, $member['passwd'])) {
                $this->error = '用户名或密码错误！';
            } else {
                $mid = $member['id'];
                // 更新登录信息
                $member['last_login_time'] = request()->time();
                $member['last_login_ip']   = get_client_ip(1);
                if ($member->save()) {
                    // 自动登录
                    return $this->autoLogin($this::get($mid));
                } else {
                    // 更新登录信息失败
                    $this->error = '登录信息更新失败，请重新登录！';
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * 根据会员ID获取会员基本信息
     * @param array $id 会员ID
     * @author 路人甲乙
     * @return mixed
     */
    public static function getMemberInfoByID($id = null)
    {
        $where['m.id']     = $id; //会员ID
        $where['m.status'] = 1;   //会员状态
        $data              = self::view('member m', true)
            ->view("money", 'account,freeze,operate_account,bond_account', 'money.mid=m.id', 'left')
            ->where($where)
            ->find();
        return $data;
    }
    /**
     * 根据会员手机号获取会员基本信息
     * @param array $id 会员ID
     * @author 路人甲乙
     * @return mixed
     */
    public static function getMemberInfoByMobile($mobile = null)
    {
        $where['m.mobile'] = $mobile; //会员手机号
        $where['m.status'] = 1;       //会员状态
        $data              = self::view('member m', true)
            ->view("money", 'account,freeze,operate_account,bond_account', 'money.mid=m.id', 'left')
            ->where($where)
            ->find();
        return $data;
    }
    /**
     * 保存注册数据
     * @param  [type] $data [description]
     * @return [type]       [description]
     * @author 张继立 <404851763@qq.com>
     */
    public static function saveData($data)
    {
        $sdata['mobile']      = $data['mobile'];
        $sdata['passwd']      = $data['password'];
        $sdata['paywd']       = substr($data['mobile'], -6, 6);
        $sdata['pid']         = 0;
        $sdata['agent_far']   = intval($data['agent_far']);
        $sdata['create_ip']   = get_client_ip(1);
        $sdata['create_time'] = time();

        $result = self::create($sdata);
        if ($result->id) {
            Db('money')->insert(['mid' => $result->id]);
            $sdata['id'] = $result->id;
            return ['status' => 1, 'message' => '注册成功', 'data' => $sdata];
        } else {
            return ['status' => 0, 'message' => '注册失败'];
        }
    }
}
