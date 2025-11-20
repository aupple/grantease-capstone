@props(['status'])

@if ($status)
    <div
        {{ $attributes->merge(['class' => 'font-medium text-sm text-green-500 bg-green-500/10 px-4 py-3 rounded-lg border border-green-500/30 backdrop-blur-sm text-center mb-4']) }}>
        {{ $status }}
    </div>
@endif
