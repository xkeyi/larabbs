<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use Auth;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['index', 'show'],
        ]);

        $this->middleware('verified')->except('index', 'show');
    }

    public function index(Request $request, User $user)
    {
        $topics = Topic::withOrder($request->order)->paginate();

        $active_users = $user->getActiveUsers();

        return view('topics.index', compact('topics', 'active_users'));
    }

    public function show(Request $request, Topic $topic)
    {
        // URL 矫正
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic)
    {
        $categories = Category::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function store(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'required|min:2',
            'body' => 'required|min:3',
            'category_id' => 'required|numeric',
        ], [
            'title.min' => '标题必须至少两个字符',
            'body.min' => '文章内容必须至少三个字符',
        ]);

        // Auth::user()->topics()->create([]);

        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '帖子创建成功！');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);

        $categories = Category::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $request->validate([
            'title' => 'required|min:2',
            'body' => 'required|min:3',
            'category_id' => 'required|numeric',
        ], [
            'title.min' => '标题必须至少两个字符',
            'body.min' => '文章内容必须至少三个字符',
        ]);

        $topic->update($request->all());

        return redirect()->to($topic->link())->with('success', '更新成功！');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);

        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除！');
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success' => false,
            'msg' => '上传失败！',
            'file_path' => '',
        ];

        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);

            if ($result) {
                $data['success'] = true;
                $data['msg'] = '上传成功！';
                $data['file_path'] = $result['path'];
            }
        }

        return $data;
    }
}
