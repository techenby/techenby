@props(['text' => ''])

@php
    use Illuminate\Support\Str;

    $text = trim((string) $text);
@endphp

{{-- Answers still starting with "Prompt:" are placeholders, so they read as notes until they're replaced. --}}
@if ($text !== '')
    <div {{ $attributes->class(['article-prose no-lede', 'italic [&_p]:text-zinc-500 dark:[&_p]:text-zinc-400' => Str::startsWith($text, 'Prompt:')]) }}>
        {!! Str::markdown($text) !!}
    </div>
@endif
