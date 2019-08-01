<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use Cache;
use Auth;
use App\Models\User;
use App\Models\Image;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清楚验证码缓存
        Cache::forget($request->verification_key);

        // return $this->response->created();
        return $this->response->item($user, new UserTransformer())
                            ->setMeta([
                                'access_token' => Auth::guard('api')->fromUser($user),
                                'token_type' => 'Bearer',
                                'expires_in' => Auth::guard('api')->factory()->getTTl() * 60,
                            ])
                            ->setStatusCode(201);
    }

    public function me()
    {
        // $user = Auth::guard('api')->user();
        $user = $this->user();

        return $this->response->item($user, new UserTransformer());
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }

        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }

    public function activedIndex(User $user)
    {
        $users = $user->getActiveUsers();

        return $this->response->collection($users, new UserTransformer());
    }

    public function weappStore(UserRequest $request)
    {
         $verifyData = Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回 401
            return $this->response->errorUnauthorized('验证码错误');
        }

        // 获取微信的 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($request->code);

        // 如果 openid 对应的用户已存在，报错 403
        $user = User::where('weapp_openid', $data['openid'])->first();

        if ($user) {
            return $this->response->errorForbidden('微信已绑定其他用户，请直接登录');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
        ]);

        // 清楚验证码缓存
        Cache::forget($request->verification_key);

        // return $this->response->created();
        return $this->response->item($user, new UserTransformer())
                            ->setMeta([
                                'access_token' => Auth::guard('api')->fromUser($user),
                                'token_type' => 'Bearer',
                                'expires_in' => Auth::guard('api')->factory()->getTTl() * 60,
                            ])
                            ->setStatusCode(201);
    }
}
