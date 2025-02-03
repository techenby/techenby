<x-layout :title="$title">
    <x-container class="max-w-prose print:w-full print:max-w-none print:px-0 space-y-12">
        <header class="max-w-3xl mx-auto text-center">
            <h1 class="text-5xl font-bold leading-none font-display print:text-3xl">Andy Newhouse's {{ $title }}</h1>
        </header>

        <section id="forward" class="bg-white rounded-lg shadow-lg dark:bg-mardi-950 py-4 px-6 print:p-0 print:shadow-none">
            <div class="text-lg">{!! Statamic::modify($forward)->markdown() !!}</div>
        </section>


        <section id="experience">
            <h2 class="text-3xl font-bold leading-none font-display mb-4">Experience</h2>
            <div class="space-y-12">
            @foreach ($jobs as $job)
                <div>
                    <h3 class="text-xl font-bold leading-tight print:text-lg">
                        {{ $job['title'] }} at {{ $job['company'] }}
                    </h3>
                    <p class="flex items-center mt-4 mb-1 text-sm">
                        {{ $job['start'] }} - {{ $job['end'] ?? 'current' }}
                    </p>
                    <p>{!! $job['description'] !!}</p>
                </div>
                @endforeach
            </div>
        </section>

        <section id="skills">
            <h2 class="mb-4 text-3xl font-bold leading-none font-display">Skills</h2>
            <ul class="ml-8 list-disc list-outside">
                @foreach ($skills as $skill)
                <li>{!! Statamic::modify($skill)->markdown() !!}</li>
                @endforeach
            </ul>
        </section>

        <section id="education">
            <h2 class="mb-4 text-3xl font-bold leading-none font-display">Education</h2>
            <div class="text-sm">{!! Statamic::modify($education)->markdown() !!}</div>
        </section>
    </x-container>
</x-layout>
