@props(['set'])

<div class="hint {{ $set->style }}">
    <h2 class="hint-title">
        {{ $set->title}}
    </h2>
    <div class="hint-content">
        {!! $set->content !!}
    </div>
</div>
