<?php

namespace app\api\controller;

use Throwable;
use ba\Captcha;
use ba\ClickCaptcha;
use think\facade\Config;
use app\common\facade\Token;
use app\common\controller\Frontend;
use app\api\validate\User as UserValidate;
use app\common\model\PublicStatus;
use app\common\model\RealNameAuth;
use app\common\model\User as ModelUser;

class User extends Frontend
{
    protected array $noNeedLogin = ['checkIn', 'logout'];

    protected array $noNeedPermission = ['index'];

    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * 会员签入(登录和注册)
     * @throws Throwable
     */
    public function checkIn(): void
    {
        $openMemberCenter = Config::get('buildadmin.open_member_center');
        if (!$openMemberCenter) {
            $this->error(__('Member center disabled'));
        }

        // 检查登录态
        if ($this->auth->isLogin()) {
            $this->success(__('You have already logged in. There is no need to log in again~'), [
                'type' => $this->auth::LOGGED_IN
            ], $this->auth::LOGIN_RESPONSE_CODE);
        }

        $userLoginCaptchaSwitch = Config::get('buildadmin.user_login_captcha');

        if ($this->request->isPost()) {
            $params = $this->request->post(['tab', 'email', 'mobile', 'username', 'password', 'keep', 'captcha', 'captchaId', 'captchaInfo', 'registerType']);

            // 提前检查 tab ，然后将以 tab 值作为数据验证场景
            if (!in_array($params['tab'] ?? '', ['login', 'register'])) {
                $this->error(__('Unknown operation'));
            }

            $validate = new UserValidate();
            try {
                $validate->scene($params['tab'])->check($params);
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }

            if ($params['tab'] == 'login') {
                if ($userLoginCaptchaSwitch) {
                    $captchaObj = new ClickCaptcha();
                    if (!$captchaObj->check($params['captchaId'], $params['captchaInfo'])) {
                        $this->error(__('Captcha error'));
                    }
                }
                $res = $this->auth->login($params['username'], $params['password'], !empty($params['keep']));
            } elseif ($params['tab'] == 'register') {
                // $captchaObj = new Captcha();
                // if (!$captchaObj->check($params['captcha'], $params[$params['registerType']] . 'user_register')) {
                //     $this->error(__('Please enter the correct verification code'));
                // }
      
                $res = $this->auth->register($params['username'], $params['password'], $params['mobile'], $params['email']);
            }

            if (isset($res) && $res === true) {
                $this->success(__('Login succeeded!'), [
                    'userInfo'  => $this->auth->getUserInfo(),
                    'routePath' => '/index'
                ]);
            } else {
                $msg = $this->auth->getError();
                $msg = $msg ?: __('Check in failed, please try again or contact the website administrator~');
                $this->error($msg);
            }
        }

        $this->success('', [
            'userLoginCaptchaSwitch'  => $userLoginCaptchaSwitch,
            'accountVerificationType' => get_account_verification_type()
        ]);
    }

    //退出登录
    public function logout(): void
    {
        if ($this->request->isPost()) {
            $refreshToken = $this->request->post('refreshToken', '');
            if ($refreshToken) Token::delete((string)$refreshToken);
            $this->auth->logout();
            $this->success();
        }
    }


    //用户实名认证1212211221
    public function userRealNameAuth() : void {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = false;
            $userAuth = new RealNameAuth();
            $userAuth->startTrans();
            try {
                $data['user_id'] = $this->auth->id;
                $result = $userAuth->save($data);
                $userAuth->commit();
            } catch (Throwable $e) {
                $userAuth->rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $this->success(__('Real-name authentication under review'));
            } else {
                $this->error(__('No rows were added'));
            }
        }
    }
    

    /**
     * 获取用户真实姓名认证信息
     */
    public function getUserInfo() : void {
        $res = ModelUser::field($this->auth->getAllowFields())->with(['isRz'])->find($this->auth->id);
        $res['status_txt'] = PublicStatus::$user_rz_status[$res['is_rz']] ?? '未知';
        $res['isRz']['status_txt'] = PublicStatus::$user_rz_status[$res['isRz']['status']] ?? '未知';
        $this->success(__('succeeded!'), [
            'userInfo'  => $res
        ]);
    }
}