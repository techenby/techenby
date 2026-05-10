@props(['name'])

@error($name)
<p class="mt-3 font-['Geist_Mono'] text-xs text-red-700 dark:text-red-400">{{ $message }}</p>
@enderror
