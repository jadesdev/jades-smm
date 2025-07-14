<div {{ $attributes->merge(['class' => $getClasses()]) }}>
    {{ $slot }}
</div>

@push('styles')
    <style>
        .button-group .btn {
            {{ $getButtonClasses() }}
        }
    </style>
@endpush
