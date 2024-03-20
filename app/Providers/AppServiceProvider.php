<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use Statamic\Facades\Markdown;
use Statamic\Statamic;
use Torchlight\Commonmark\V2\TorchlightExtension;
use Ueberdosis\CommonMark\HintExtension;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Statamic::script('app', 'cp');
        // Statamic::style('app', 'cp');

        Markdown::addExtension(function () {
            return [
                new TorchlightExtension,
                new HintExtension,
            ];
        });
    }
}
