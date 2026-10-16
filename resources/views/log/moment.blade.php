@php
    use Statamic\Facades\Entry;
@endphp

<x-ui.photo-entry :entry="Entry::find($id)" badge="Moment" />
