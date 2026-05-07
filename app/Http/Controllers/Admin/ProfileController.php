<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Determine the current route prefix (admin or principal).
     */
    private function getRoutePrefix(): string
    {
        $routeName = request()->route()?->getName() ?? '';
        return str_starts_with($routeName, 'principal.') ? 'principal' : 'admin';
    }

    public function index()
    {
        $user = auth()->user();
        $routePrefix = $this->getRoutePrefix();
        return view('admin.profile.index', compact('user', 'routePrefix'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.profile')->with('success', 'Profile updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $dataUri = 'data:' . $mime . ';base64,' . $base64;

            $user->photo = $dataUri;
            $user->save();
        }

        return redirect()->route($this->getRoutePrefix() . '.profile')->with('success', 'Profile photo updated!');
    }

    public function removePhoto()
    {
        $user = auth()->user();

        $user->photo = null;
        $user->save();

        return redirect()->route($this->getRoutePrefix() . '.profile')->with('success', 'Profile photo removed!');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', \App\Services\SettingsEnforcer::getPasswordRules(), 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_updated_at' => now(),
        ]);

        return redirect()->route($this->getRoutePrefix() . '.profile')->with('success', 'Password updated successfully!');
    }

    public function destroyAccount(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.']);
        }

        // Delete photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
