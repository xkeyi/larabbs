<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'change-locale'],
], function ($api) {
    $api->get('version', function () {
        return response('this is version v1');
    });

    /** 登录相关 */
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');

        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');

        // 刷新 token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除 token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
    });

    /** 其他 */
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        /** 游客可以访问的接口 */
        // 分类
        $api->get('categories', 'CategoriesController@index')->name('api.categories.index');

        // 话题
        $api->get('topics', 'TopicsController@index')->name('api.topics.index');
        $api->get('topics/{topic}', 'TopicsController@show')->name('api.topics.show');
        $api->get('users/{user}/topics', 'TopicsController@userIndex')->name('api.users.topics.index');

        // 回复
        $api->get('topics/{topic}/replies', 'RepliesController@index')->name('api.topics.replies.index');
        $api->get('users/{user}/replies', 'RepliesController@userIndex')->name('api.users.replies.index');

        // 推荐资源
        $api->get('links', 'LinksController@index')->name('api.links.index');
        // 活跃用户
        $api->get('actived/users', 'UsersController@activedIndex')->name('api.actived.users.index');

        /** 需要 token 验证的接口 */
        // 中间件也可以使用 auth:api，但是这个验证不通过时返回 500 而不是 401
        // 在控制器中获取用户 auth:api(框架自带): auth('api')->user();api.auth(dingo/api)：$this->user()
        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')->name('api.user.show');
            // 编辑登录用户信息(patch:部分修改资源，提供部分资源信息;put 替换某个资源，需提供完整的资源信息)
            $api->patch('user', 'UsersController@update')->name('api.user.update');
            // 图片资源上传
            $api->post('images', 'ImagesController@store')->name('api.images.store');

            // 话题
            $api->post('topics', 'TopicsController@store')->name('api.topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')->name('api.topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destroy')->name('api.topics.destroy');

            // 回复
            $api->post('topics/{topic}/replies', 'RepliesController@store')->name('api.topics.replies.store');
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('api.topics.replies.destroy');

            // 当前登录用户消息通知
            $api->get('user/notifications', 'NotificationsController@index')->name('api.user.notifications.index');
            $api->get('user/notifications/stats', 'NotificationsController@stats')->name('api.user.notifications.stats');
            $api->patch('user/read/notifications', 'NotificationsController@read')->name('api.user.notifications.read');

            // 当前登录用户权限
            $api->get('user/permissions', 'PermissionsController@index');
        });
    });
});

$api->version('v2', function ($api) {
    $api->get('version', function () {
        return response('this is version v2');
    });
});
