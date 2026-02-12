<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\PasswordChangeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile page
     */
    public function edit()
    {
        return view('admin.profile.index', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the admin's profile information
     */
    public function update(ProfileUpdateRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
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

            // Debug: Log the photo path
            Log::info('Profile photo uploaded: ' . $photoPath);
        }

        $user->update($validated);

        // Refresh the authenticated user to update session data
        Auth::setUser($user->fresh());

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update the admin's password
     */
    public function updatePassword(PasswordChangeRequest $request)
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete the admin's account (soft delete)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.current_password' => 'Password tidak sesuai.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Prevent deleting the last admin
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.profile.edit')
                ->with('error', 'Tidak boleh menghapus admin terakhir. Sistem harus memiliki minimal satu admin.');
        }

        // Delete profile photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Log the deletion action (optional)
        Log::info("Admin account deleted (soft delete): {$user->name} ({$user->email})");

        // Soft delete the user (using SoftDeletes trait)
        // Data di incoming_items dan outgoing_items tetap aman karena FK tidak cascade
        $user->delete();

        auth()->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Akun berhasil dihapus. Data transaksi tetap tersimpan untuk audit trail.');
    }
}
  