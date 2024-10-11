<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $isBlocked = $request->input('block') ?? false;
        $users = User::where('id', '!=', Auth::user()->id)
            ->when(!$isBlocked, function ($query) {
                $query->where('block', false);
            })
            ->paginate(15);
        return view('users.index', ['users' => $users]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'New user created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $user = User::findOrFail($id);

        $user->block = false;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User unblocked successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->block = true;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User blocked successfully');
    }
}
