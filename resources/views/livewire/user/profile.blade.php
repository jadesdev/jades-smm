@section('title', $metaTitle)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pb-10">
    <div>
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Account Information</h2>
            </x-slot>
            <form action="" class="px-2" method="post">
                @csrf
                <x-forms.input name="name" label="Name" placeholder="Enter your name" :required="true" />
                <x-forms.input name="username" label="Username" placeholder="Enter your username" :required="true" />
                <x-forms.input name="" label="Email" value="{{ Auth::user()->email }}" placeholder="Enter your email" readonly />
                <x-forms.input name="phone" label="Phone" placeholder="Enter your phone" :required="true" />

                <x-button type="submit" variant="primary" class="w-full justify-center">
                    Update Profile
                </x-button>
            </form>
        </x-card>
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Security</h2>
            </x-slot>
            <form action="" class="px-2" method="post">
                @csrf
                <x-forms.input name="current_password" type="password" label="Current Password"
                    placeholder="Enter your password" :required="true" />
                <x-forms.input name="password" type="password" label="Password" placeholder="Enter your password"
                    :required="true" />
                <x-forms.input name="password_confirmation" type="password" label="Confirm Password"
                    placeholder="Enter your password" :required="true" />

                <x-button type="submit" variant="primary" class="w-full justify-center">
                    Update Password
                </x-button>
            </form>
        </x-card>
    </div>
    <div>
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">API Key</h2>
            </x-slot>
            <x-forms.input name="" value="sasasas" label="" placeholder="Enter your API Key" readonly />
            <x-button type="button" variant="primary" class="w-full ">
                Generate API Key
            </x-button>
        </x-card>
        <x-card>
            <x-slot name="header">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Delete Account</h2>
            </x-slot>
            <p class="text-lg text-red-600 mb-2">This action cannot be undone.Are you sure you want to delete your account?</p>   
            <form action="" class="px-2" method="post">
                @csrf
                <x-forms.input name="password" type="password" label="Enter your password" placeholder="Enter your password"
                    :required="true" />

                <x-button type="submit" variant="primary" class="w-full justify-center">
                    Delete Account
                </x-button>
            </form>
        </x-card>
    </div>
</div>

@include('layouts.meta')
