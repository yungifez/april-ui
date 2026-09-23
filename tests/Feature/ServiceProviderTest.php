<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yungifez\AprilUI\AprilUIServiceProvider;

function publishPaths(string $tag): array
{
    return ServiceProvider::pathsToPublish(AprilUIServiceProvider::class, $tag);
}

describe('registration', function () {
    it('registers the service provider', function () {
        expect(app()->getLoadedProviders())->toHaveKey(AprilUIServiceProvider::class);
    });

    it('loads the package config', function () {
        expect(config('april-ui'))->toBeArray();
    });

    it('registers the april view namespace', function () {
        expect(view()->getFinder()->getHints())->toHaveKey('april');
    });

    it('registers the MCP installation command', function () {
        expect(Artisan::all())->toHaveKey('april:mcp:install');
    });

    it('registers the asset routes', function () {
        expect(Route::has('april-ui.april.js'))->toBeTrue()
            ->and(Route::has('april-ui.editor.js'))->toBeTrue();
    });

    it('registers the blade precompiler', function () {
        expect(Blade::render('<april:label>Hi</april:label>'))->toContain('<label');
    });
});

describe('publishing', function () {
    it('offers a publish tag for the config file', function () {
        expect(publishPaths('april-ui-config'))->not->toBeEmpty();
    });

    it('offers a publish tag for all views', function () {
        expect(publishPaths('april-views'))->not->toBeEmpty();
    });

    it('offers a publish tag for each component', function (string $name) {
        expect(publishPaths("april-view-{$name}"))->toHaveCount(1);
    })->with('components');

    it('points a component tag at the vendor view path', function (string $name) {
        $target = str_replace('\\', '/', array_values(publishPaths("april-view-{$name}"))[0]);

        expect($target)->toEndWith("resources/views/vendor/april/components/{$name}.blade.php");
    })->with('components');

    it('points a component tag at the file it names', function (string $name) {
        expect(realpath(array_key_first(publishPaths("april-view-{$name}"))))
            ->toBe(realpath(componentPath($name)));
    })->with('components');

    it('publishes one component without publishing the others', function () {
        $target = resource_path('views/vendor/april/components');
        File::deleteDirectory($target);

        Artisan::call('vendor:publish', ['--tag' => 'april-view-button', '--force' => true]);

        expect("{$target}/button.blade.php")->toBeFile()
            ->and("{$target}/badge.blade.php")->not->toBeFile();
    });

    it('publishes a component that a user can then edit', function () {
        $target = resource_path('views/vendor/april/components');
        File::deleteDirectory($target);

        Artisan::call('vendor:publish', ['--tag' => 'april-view-badge', '--force' => true]);

        expect(file_get_contents("{$target}/badge.blade.php"))
            ->toBe(file_get_contents(componentPath('badge')));
    });

    it('publishes the config file', function () {
        $target = config_path('april-ui.php');
        File::delete($target);

        Artisan::call('vendor:publish', ['--tag' => 'april-ui-config', '--force' => true]);

        expect($target)->toBeFile()
            ->and(require $target)->toHaveKey('tailwind_merge');
    });

    afterEach(function () {
        File::deleteDirectory(resource_path('views/vendor/april'));
        File::delete(config_path('april-ui.php'));
    });
});
