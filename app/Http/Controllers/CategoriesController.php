<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category)
    {
        //$topics = Topic::where('category_id', $category->id)->paginate();
        $topics = $category->topics()->withOrder($request->order)->paginate();

        return view('topics.index', compact('topics', 'category'));
    }
}
