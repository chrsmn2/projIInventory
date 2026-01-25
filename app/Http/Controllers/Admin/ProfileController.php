<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\PasswordChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile page
     */
    public function edit()
    {
        return view('admin.profile.index', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the admin's profile information
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new photo
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $photoPath;
        }

        $user->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the admin's password
     */
    public function updatePassword(PasswordChangeRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete the admin's account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.current_password' => 'Password tidak sesuai.',
        ]);

        $user = auth()->user();

        // Delete profile photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        auth()->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun berhasil dihapus.');
    }
}
