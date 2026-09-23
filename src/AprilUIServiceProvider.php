<?php

namespace Yungifez\AprilUI;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TalesFromADev\TailwindMerge\TailwindMerge;
use TalesFromADev\TailwindMerge\TailwindMergeInterface;
use Yungifez\AprilUI\Console\Commands\DoctorCommand;
use Yungifez\AprilUI\Console\Commands\ListCommand;
use Yungifez\AprilUI\Console\Commands\McpCommand;
use Yungifez\AprilUI\Console\Commands\McpInstallCommand;
use Yungifez\AprilUI\Console\Commands\PublishCommand;
use Yungifez\AprilUI\Console\Commands\UpdateCommand;
use Yungifez\AprilUI\Handlers\FrontendAssetsHandler;
use Yungifez\AprilUI\Handlers\TailwindMergeHandler;
use Yungifez\AprilUI\Support\TailwindMerger;

class AprilUIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('april-ui')
            ->hasConfigFile()
            ->hasRoute('assets')
            ->hasViews('april');
    }

    public function packageRegistered()
    {
        $this->app->singleton(TailwindMergeInterface::class, function ($app): TailwindMergeInterface {
            $config = $app['config']->get('april-ui.tailwind_merge', []);

            return new TailwindMerge(array_filter(
                $config,
                fn ($value): bool => $value !== null && $value !== []
            ));
        });

        $this->app->singleton(TailwindMerger::class);
    }

    public function packageBooted()
    {
        app(FrontendAssetsHandler::class)->boot();
        app(TailwindMergeHandler::class)->boot();

        $this->publishComponentViews();

        // allow support for <april:component syntax
        Blade::precompiler(function ($str) {
            return app(AprilBladeCompiler::class)->compile($str);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                ListCommand::class,
                PublishCommand::class,
                UpdateCommand::class,
                DoctorCommand::class,
                McpCommand::class,
                McpInstallCommand::class,
            ]);
        }
    }

    /**
     * Register a publish tag for every component view.
     *
     * This lets a user publish a single component instead of all of them.
     */
    protected function publishComponentViews(): void
    {
        $basePath = __DIR__.'/../resources/views/components';

        foreach (File::allFiles($basePath) as $file) {

            if (! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $path = $file->getPathname();
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            $this->publishes([
                $path => resource_path("views/vendor/april/components/{$relative}"),
            ], 'april-view-'.str_replace(['/', '.blade.php'], ['-', ''], $relative));
        }
    }
}
