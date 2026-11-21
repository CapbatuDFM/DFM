<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('users.form', ['user' => new User(['role' => User::ROLE_CARETAKER, 'is_active' => true])]);
    }

    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('status', 'User added.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        // The owner must not lock themselves out.
        if ($user->is($request->user()) && ($data['role'] !== User::ROLE_OWNER || ! $data['is_active'])) {
            return back()->withInput()->withErrors([
                'role' => 'You cannot remove your own owner access or deactivate your own account.',
            ]);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'User updated.');
    }
}
