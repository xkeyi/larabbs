<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Category;
use App\Models\Topic;
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

    public function index(Request $request)
    {
        $topics = Topic::withOrder($request->order)->paginate();

        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
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

        return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
    }

    public function edit(Topic $topic)
    {
        $categories = Category::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'title' => 'required|min2',
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

        return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
    }
}
