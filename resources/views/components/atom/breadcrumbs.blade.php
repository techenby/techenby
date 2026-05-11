<ol role="list" class="flex items-center">
    <s:nav:breadcrumbs>
    <li @class(['current' => $is_current])>
        <div class="flex items-center">
            @if (! $loop->first)
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-4 shrink-0 text-neutral-300 dark:text-neutral-600">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
            </svg>
            @endif
            @if ($loop->last)
            <span>{{ $title }}</span>
            @else
            <a href="{{ $url }}">{{ $title }}</a>
            @endif
        </div>
    </li>
    </s:nav:breadcrumbs>
</ol>
