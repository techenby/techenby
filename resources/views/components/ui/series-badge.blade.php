{{-- Matches flux:badge size="sm", tinted with the brand purple so a series reads apart from a type. --}}
<span {{ $attributes->class('inline-flex items-center rounded-md bg-enby-purple/10 px-2 py-1 text-xs font-medium whitespace-nowrap text-enby-purple-text [print-color-adjust:exact] dark:bg-enby-purple/25') }}>{{ $slot }}</span>
