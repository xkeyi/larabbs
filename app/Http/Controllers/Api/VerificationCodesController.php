<?php

namespace App\Http\Controllers\Api;

use Cache;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        // if (!app()->environment('production')) {
        if (!config('easysms.send_enabled')) {
            $code = 1234;
        } else {
            // 生成 4 位随机数，左侧补 0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $easySms->send($phone, [
                    'template' => '83249',
                    'data' => [
                        'code' => $code,
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('juhe')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);

        // 做接口的思路与我们做网页应用不同，网站中处理验证码，通常是存入 session，注册的时候验证用户输入的验证码与 session 中的验证码是否相同。但是接口是无状态，相互独立的，处理这种相互关联，有先后调用顺序的接口时，常常是第一个接口返回一个随机的 key，利用这个 key 去调用第二个接口。

        // 缓存验证码，10 分钟过期
        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
