<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use App\Models\Link;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, User $user, Link $link)
    {
        //$topics = Topic::where('category_id', $category->id)->paginate();
        $topics = $category->topics()->withOrder($request->order)->paginate();

        $active_users = $user->getActiveUsers();

        $links = $link->getAllCached();

        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
