<div {{ $attributes->merge(['class' => 'lg:prose-lg prose dark:prose-invert prose-brand prose-a:underline leading-normal']) }}>
    {!! $content ?? $slot !!}
</div>
