 <!-- Website Information -->
 <x-card>
    <x-slot name="header">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            Website Information
        </h3>
    </x-slot>
    <form action="{{ route('admin.settings.update') }}" method="post"
        class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
        @csrf
        <x-forms.input name="title" label="Website Name" type="text" :value="$settings->title"
            placeholder="Enter website name" icon="fas fa-globe" />
        <x-forms.input name="name" label="Short Name" type="text" :value="$settings->name"
            placeholder="Enter short name" />
        <x-forms.input name="email" label="Website Email" type="email" :value="$settings->email"
            placeholder="Enter website email" />
        <x-forms.input name="admin_email" label="Admin Email" type="email" :value="$settings->admin_email"
            placeholder="Enter admin email" />
        <x-forms.input name="phone" label="Website Phone" type="tel" :value="$settings->phone"
            placeholder="Enter phone number" />
        <x-forms.textarea name="description" label="Website About" rows="3" :value="$settings->description"
            placeholder="Enter website description" class="md:col-span-2" />
        <x-forms.textarea name="meta_description" label="Meta Description" rows="3" :value="$settings->meta_description"
            placeholder="Meta description" class="md:col-span-2" />
        <x-forms.textarea name="meta_keywords" label="Website Keywords" rows="2" :value="$settings->meta_keywords"
            placeholder="Enter website meta_keywords" class="md:col-span-2" />
        <div class="md:col-span-2">
            <x-button type="submit" variant="primary" class="w-full">
                <i class="fas fa-save mr-2"></i> Save Settings
            </x-button>
        </div>
    </form>
</x-card>

<!-- Logo/Image Settings -->
<x-card>
    <x-slot name="header">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <i class="fas fa-images mr-2 text-purple-500"></i>
            Logo & Image Settings
        </h3>
    </x-slot>
    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
            <div>
                <x-forms.file name="logo" label="Site Logo" :value="$settings->logo" accept="image/*"
                    preview-class="h-20 w-30 mt-2" />
            </div>
            <div>
                <x-forms.file name="favicon" label="Favicon" :value="$settings->favicon" accept="image/*"
                    preview-class="h-16 w-16 mt-2" />
            </div>
        </div>
        <x-button type="submit" variant="primary" class="w-full">
            <i class="fas fa-sync-alt mr-2"></i> Update Settings
        </x-button>
    </form>
</x-card>

<!-- Social Links -->
<x-card>
    <x-slot name="header">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <i class="fas fa-share-alt mr-2 text-green-500"></i>
            Social Links
        </h3>
    </x-slot>
    <form action="{{ route('admin.settings.update') }}" method="post"
        class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-y-0">
        @csrf
        <x-forms.input name="facebook" label="Facebook" type="text" :value="$settings->facebook"
            icon="fab fa-facebook" />
        <x-forms.input name="twitter" label="Twitter" type="text" :value="$settings->twitter" icon="fab fa-twitter" />
        <x-forms.input name="instagram" label="Instagram" type="text" :value="$settings->instagram"
            icon="fab fa-instagram" />
        <x-forms.input name="telegram" label="Telegram" type="text" :value="$settings->telegram"
            icon="fab fa-telegram" />
        {{-- <x-forms.input name="linkedin" label="LinkedIn" type="text" :value="$settings->linkedin"
            icon="fab fa-linkedin" /> --}}
        <x-forms.input name="whatsapp" label="WhatsApp" type="text" :value="$settings->whatsapp"
            icon="fab fa-whatsapp" />
        <div class="md:col-span-2">
            <x-button type="submit" variant="primary" class="w-full">
                <i class="fas fa-save mr-2"></i> Update Settings
            </x-button>
        </div>
    </form>
</x-card>

<!-- Currency Settings -->
<x-card>
    <x-slot name="header">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            <i class="fas fa-money-bill-wave mr-2 text-yellow-500"></i>
            Currency Settings
        </h3>
    </x-slot>
    <form action="{{ route('admin.settings.update') }}" method="post"
        class="grid grid-cols-1 md:grid-cols-3 gap-6 gap-y-0">
        @csrf
        <x-forms.input name="currency" label="Currency Symbol" type="text" :value="$settings->currency" required />
        <x-forms.input name="currency_code" label="Currency Code" type="text" :value="$settings->currency_code"
            required />
        <x-forms.input name="currency_rate" label="Currency Rate" type="text" :value="$settings->currency_rate"
            required />
        <div class="md:col-span-3">
            <x-button type="submit" variant="primary" class="w-full">
                <i class="fas fa-save mr-2"></i> Update Settings
            </x-button>
        </div>
    </form>
</x-card>