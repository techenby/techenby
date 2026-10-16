<x-layout :$title>
    <div class="space-y-10">
        <x-ui.page-header :heading="$heading ?? $title" :$subheading />

        <x-uses.point-n-click />

        <x-uses.list />
    </div>
</x-layout>
