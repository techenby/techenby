@php
    use App\Support\BlogCodeHighlighter;
    use Illuminate\Support\Str;

    $language = filled($language ?? null) ? (string) $language : null;
    $note = filled($note ?? null) ? (string) $note : null;
    $class = $language ? ' class="language-'.e(Str::slug($language)).'"' : '';
    $html = app(BlogCodeHighlighter::class)->highlight('<pre><code'.$class.'>'.e((string) ($code ?? '')).'</code></pre>');
@endphp

@if (filled($code ?? null))
    <figure data-code-block>
        @if ($language || $note)
            <figcaption>
                <span>{{ $language }}</span>
                @if ($note)
                    <span>{{ $note }}</span>
                @endif
            </figcaption>
        @endif

        {!! $html !!}
    </figure>
@endif
