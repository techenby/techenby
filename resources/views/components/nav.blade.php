<header class="relative z-10">
    <div
        class="max-w-6xl mx-auto flex flex-col sm:flex-row space-y-4 sm:space-y-0 items-center justify-between py-8 px-6">
        <a href="/">
            <x-ant.svg src="assets/logo" class="h-8 inline group" alt="TechEnby Logo" />
        </a>
        <nav>
            <ul class="flex items-center space-x-6 font-bold text-lg">
                @foreach(Statamic::tag('nav:main') as $link)
                <li>
                    <a href="{{ $link['url'] }}" class="hover:text-java-600 dark:hover:text-java-400">
                        {{ $link['title'] }}
                    </a>
                </li>
                @endforeach
                <li>
                    <a href="https://pinkary.com/@techenby">
                        <x-ant.svg src="assets/icons/pinkary" class="w-7 h-7 hover:text-java-600 dark:hover:text-java-400 scale-100 hover:scale-2 hover:-rotate-2" aria-label="RSS Feed" role="img" />
                    </a>
                </li>
                <li>
                    <a href="/feed.xml">
                        <x-ant.svg src="assets/icons/rss" class="w-7 h-7 hover:text-java-600 dark:hover:text-java-400 scale-100 hover:scale-2 hover:-rotate-2" aria-label="RSS Feed" role="img" />
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>
