@props(['color' => 'gray', 'hex' => '#333333', 'small' => false])

<div class="bg-white p-1">
    <x-color :hex=$hex />
    <p class="text-black font-brother tracking-wider text-lg p-2">{{ ucfirst($color) }}</p>
</div>
