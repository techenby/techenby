<?php

namespace App\Providers;

use App\Support\Lately;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Mechanisms\FrontendAssets\FrontendAssets;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;
use Statamic\StaticSite\SSG;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // The static site generator renders every page in one process, and Livewire
            // only prints its scripts once per process, so reset that for every page.
            View::creator('components.layout', function (): void {
                $assets = app(FrontendAssets::class);
                $assets->hasRenderedScripts = false;
                $assets->hasRenderedStyles = false;
            });
        }

        SSG::addUrls(fn (): array => collect(array_keys(app(Lately::class)->filters()))->map(fn (string $type) => "/log/{$type}")->all());

        SSG::after(function (): void {
            $destination = config('statamic.ssg.destination');
            $minified = ! config('app.debug');

            // Flux and Livewire serve their JavaScript from routes the generator can't save, so copy the files over.
            $scripts = [
                base_path('vendor/livewire/flux-pro/dist/'.($minified ? 'flux.min.js' : 'flux.js')) => $minified ? '/flux/flux.min.js' : '/flux/flux.js',
                base_path('vendor/livewire/livewire/dist/'.($minified ? 'livewire.min.js' : 'livewire.js')) => EndpointResolver::scriptPath($minified),
            ];

            foreach ($scripts as $source => $path) {
                File::ensureDirectoryExists(dirname($destination.$path));
                File::copy($source, $destination.$path);
            }
        });
    }
}
