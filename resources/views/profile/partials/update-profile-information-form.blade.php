<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center gap-4">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="h-10 w-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.7 15.25c4.967 0 9.311 2.684 11.3 6.75z" />
                            <path d="M16.5 5.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('JPG, PNG or GIF (max. 2MB)') }}</p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Department -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="department" :value="__('Department')" />
                <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" :value="old('department', $user->department)" />
                <x-input-error class="mt-2" :messages="$errors->get('department')" />
            </div>

            <!-- Position -->
            <div>
                <x-input-label for="position" :value="__('Position')" />
                <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" :value="old('position', $user->position)" />
                <x-input-error class="mt-2" :messages="$errors->get('position')" />
            </div>
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Profile updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>
