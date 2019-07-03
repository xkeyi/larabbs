<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        return view('topics.create');
    }
}
