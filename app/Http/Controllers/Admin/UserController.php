<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\SettingsEnforcer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Eager load role to avoid N+1
        $query = User::with('role');

        // Filter by role if provided
        $selectedRole = $request->get('role');
        if ($selectedRole) {
            $query->whereHas('role', function ($q) use ($selectedRole) {
                $q->where('name', $selectedRole);
            });
        }

        $users = $query->orderBy('last_name')->orderBy('first_name')->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles', 'selectedRole'));
    }

    /**
     * Show form to create a new user.
     */
    public function create()
    {
        // Only show admin-level roles for this create form
        $roles = Role::whereIn('name', ['System Admin', 'Principal'])->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required', SettingsEnforcer::getPasswordRules(), 'confirmed'],
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name'] . ' ' .
                      ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') .
                      $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'password_updated_at' => now(),
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // If editing an admin-level user, restrict role options to admin/principal only
        $isAdminLevel = in_array($user->role?->name, ['System Admin', 'Principal']);
        $roles = $isAdminLevel
            ? Role::whereIn('name', ['System Admin', 'Principal'])->orderBy('name')->get()
            : Role::all();

        return view('admin.users.edit', compact('user', 'roles', 'isAdminLevel'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'password' => ['nullable', SettingsEnforcer::getPasswordRules(), 'confirmed'],
        ]);

        $data = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name'] . ' ' .
                      ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') .
                      $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role_id' => $validated['role_id'],
            'status' => $validated['status'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
            $data['password_updated_at'] = now();
        }

        $user->update($data);

        // Sync teacher record if this user is linked to a teacher
        if ($user->teacher) {
            $user->teacher->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Reset a user's password (admin-initiated).
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', SettingsEnforcer::getPasswordRules(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_updated_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Password for {$user->first_name} {$user->last_name} has been reset successfully.");
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            // Remove user references from section finalizations to avoid FK constraint errors
            \App\Models\SectionFinalization::where('unlocked_by', $user->id)
                ->update(['unlocked_by' => null]);
            \App\Models\SectionFinalization::where('finalized_by', $user->id)
                ->update(['finalized_by' => null]);

            $user->delete();
            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}