@php
    $tag = Statamic::tag('svg');

    foreach($attributes as $method => $prop) {
        $method = Str::camel($method);

        $tag->$method($prop);
    }
@endphp

{!! $tag !!}
