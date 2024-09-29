@props(['set'])

<div>
    <video class="dark:hidden rounded-lg overflow-hidden border border-java-400" src="{{ $set->light_version->url }}" controls></video>
    <video class="hidden dark:block rounded-lg overflow-hidden border border-java-800" src="{{ $set->dark_version->url }}" controls></video>
</div>
