@props(['name'])

@error($name)
<p class="mt-3 font-geist-mono text-xs text-red-700 dark:text-red-400">{{ $message }}</p>
@enderror
