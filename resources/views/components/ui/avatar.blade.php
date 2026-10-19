@php
    use Statamic\Facades\GlobalSet;

    $src = GlobalSet::findByHandle('andy')?->inCurrentSite()?->augmentedValue('avatar')->value()?->url();
@endphp

<flux:avatar circle :$src {{ $attributes->merge(['alt' => 'Andy Swick']) }} />
