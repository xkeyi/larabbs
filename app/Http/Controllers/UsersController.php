<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'name' => $request->name,
            'introduction' => $request->introduction,
        ]);

        return redirect()->route('users.show', $user)->with('success', '个人资料更新成功！');
    }
}
