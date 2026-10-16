@php
    use App\Support\Lately;
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
@endphp

<x-ui.photo-entry :$entry :badge="app(Lately::class)->kind($entry) ?? 'Make'" />
