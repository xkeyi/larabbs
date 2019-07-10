<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Auth;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    public function store(Request $request, Reply $reply)
    {
        $this->validate($request, [
            'content' => 'required|min:2',
            'topic_id' => 'required|exists:topics,id',
        ], [
            'topic_id.exists' => '话题不存在！',
        ]);

        $reply->content = $request->content;
        $reply->topic_id = $request->topic_id;
        $reply->user_id = Auth::id();

        $reply->save();

        return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        return back()->with('success', '评论删除成功！');
    }
}
