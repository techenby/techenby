@php
    use Statamic\Facades\Entry;

    $entry = Entry::find($id);
@endphp

<x-layout :$title :social-title="$entry->get('title')" :social-image="app(App\Support\SocialCard::class)->url($entry)" :updated-at="$last_modified ?? null">
    <div class="space-y-10">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        <x-uses.point-n-click />

        <x-uses.list />
    </div>
</x-layout>
