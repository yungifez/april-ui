<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Yungifez\AprilUI\Registry;

describe('the component registry', function () {
    it('describes every package view and only known dependencies', function () {
        $registry = app(Registry::class);
        $files = [];

        foreach ($registry->manifest() as $name => $entry) {
            expect($entry['name'])->toBe($name)
                ->and($entry['files'])->not->toBeEmpty();

            foreach ($entry['files'] as $file) {
                expect($registry->sourcePath().'/'.$file)->toBeFile();
                expect($files)->not->toContain($file);
                $files[] = $file;
            }

            foreach ($entry['dependencies'] ?? [] as $dependency) {
                expect($registry->familyExists($dependency))->toBeTrue();
            }
        }

        expect($registry->names())->toHaveCount(count(componentNames()))
            ->and($files)->toHaveCount(count(componentNames()))
            ->and($registry->names())->toContain('button', 'calendar', 'sidebar');
    });

    it('resolves component dependencies', function () {
        $registry = app(Registry::class);

        $resolved = [];
        $registry->resolve('select', $resolved);

        expect($resolved)->toContain('select', 'angle-down');
    });

    it('lists component details', function () {
        Artisan::call('april:list', ['component' => 'button']);

        expect(Artisan::output())->toContain('button', 'button.blade.php');
    });

    it('rejects an unknown component', function () {
        expect(Artisan::call('april:list', ['component' => 'does-not-exist']))->toBe(1)
            ->and(Artisan::output())->toContain('Unknown component');
    });

    it('returns all registered component names when no component is requested', function () {
        Artisan::call('april:list');

        $output = Artisan::output();

        expect($output)->toContain('button', 'calendar', 'sidebar')
            ->and(substr_count($output, PHP_EOL))->toBeGreaterThan(50);
    });
});

describe('component publishing', function () {
    beforeEach(function () {
        File::deleteDirectory(resource_path('views/vendor/april'));
        File::deleteDirectory(base_path('resources/views/vendor/april'));
    });

    afterEach(function () {
        File::deleteDirectory(resource_path('views/vendor/april'));
        File::deleteDirectory(base_path('resources/views/vendor/april'));
    });

    it('publishes a component and its dependencies through Laravel vendor paths', function () {
        $exitCode = Artisan::call('april:publish', [
            'components' => ['select'],
            '--force' => true,
        ]);

        expect($exitCode)->toBe(0)
            ->and(resource_path('views/vendor/april/components/select.blade.php'))->toBeFile()
            ->and(resource_path('views/vendor/april/components/angle-down.blade.php'))->toBeFile()
            ->and(file_get_contents(resource_path('views/vendor/april/components/select.blade.php')))
            ->toBe(file_get_contents(componentPath('select')));
    });

    it('publishes input-group by its component name', function () {
        $exitCode = Artisan::call('april:publish', [
            'components' => ['input-group'],
            '--force' => true,
        ]);

        expect($exitCode)->toBe(0)
            ->and(resource_path('views/vendor/april/components/input-group.blade.php'))->toBeFile()
            ->and(file_get_contents(resource_path('views/vendor/april/components/input-group.blade.php')))
            ->toBe(file_get_contents(componentPath('input-group')));
    });

    it('lets Laravel resolve a published override', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::append($path, "\n<span data-published=\"true\"></span>\n");
        clearCompiledViews();
        View::getFinder()->flush();
        View::getFinder()->prependNamespace('april', resource_path('views/vendor/april'));

        expect(render('<april:button>Save</april:button>'))
            ->toContain('data-published="true"')
            ->toContain('Save');
    });

    it('keeps a published override unless force is requested', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::put($path, '<button data-test="local-button">Local</button>');

        expect(Artisan::call('april:publish', ['components' => ['button']]))->toBe(0)
            ->and(file_get_contents($path))->toContain('local-button');

        expect(Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]))->toBe(0)
            ->and(file_get_contents($path))->toBe(file_get_contents(componentPath('button')));
    });

    it('requires an explicit component in non-interactive mode', function () {
        expect(Artisan::call('april:publish', ['--no-interaction' => true]))->toBe(1)
            ->and(Artisan::output())->toContain('Specify a component or use --all.')
            ->and(resource_path('views/vendor/april'))->not->toBeDirectory();
    });

    it('publishes every registered component with all', function () {
        expect(Artisan::call('april:publish', ['--all' => true, '--force' => true]))->toBe(0);

        $published = File::allFiles(resource_path('views/vendor/april/components'));

        expect($published)->toHaveCount(count(componentNames()))
            ->and(resource_path('views/vendor/april/components/button.blade.php'))->toBeFile()
            ->and(resource_path('views/vendor/april/components/sidebar.blade.php'))->toBeFile();
    });

    it('rejects an unknown component without creating a partial publication', function () {
        expect(Artisan::call('april:publish', [
            'components' => ['button', 'does-not-exist'],
            '--force' => true,
        ]))->toBe(1)
            ->and(resource_path('views/vendor/april'))->not->toBeDirectory();
    });

    it('shows published changes without writing during a dry run', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::append($path, "\n{{-- local change --}}\n");

        $exitCode = Artisan::call('april:update', [
            'components' => ['button'],
            '--dry-run' => true,
            '--diff' => true,
        ]);

        expect($exitCode)->toBe(0)
            ->and(Artisan::output())->toContain('button.blade.php')
            ->and(file_get_contents($path))->toContain('local change');
    });

    it('creates a backup when updating a changed published file', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::append($path, "\n{{-- local change --}}\n");

        expect(Artisan::call('april:update', [
            'components' => ['button'],
            '--force' => true,
        ]))->toBe(0)
            ->and($path.'.bak')->toBeFile()
            ->and(file_get_contents($path.'.bak'))->toContain('local change')
            ->and(file_get_contents($path))->toBe(file_get_contents(componentPath('button')));
    });

    it('does not write during a dry run', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::append($path, "\n{{-- local change --}}\n");
        $before = file_get_contents($path);

        Artisan::call('april:update', [
            'components' => ['button'],
            '--dry-run' => true,
        ]);

        expect(file_get_contents($path))->toBe($before)
            ->and($path.'.bak')->not->toBeFile();
    });

    it('ignores whitespace-only changes when requested', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::put($path, "  \n".preg_replace('/\s+/', ' ', (string) file_get_contents($path))."\n");

        expect(Artisan::call('april:update', [
            'components' => ['button'],
            '--ignore-whitespace' => true,
        ]))->toBe(0)
            ->and(Artisan::output())->toContain('up to date')
            ->and($path.'.bak')->not->toBeFile();
    });

    it('can update a changed published file without a backup', function () {
        Artisan::call('april:publish', [
            'components' => ['button'],
            '--force' => true,
        ]);

        $path = resource_path('views/vendor/april/components/button.blade.php');
        File::append($path, "\n{{-- local change --}}\n");

        expect(Artisan::call('april:update', [
            'components' => ['button'],
            '--force' => true,
            '--no-backup' => true,
        ]))->toBe(0)
            ->and($path.'.bak')->not->toBeFile()
            ->and(file_get_contents($path))->toBe(file_get_contents(componentPath('button')));
    });

    it('reports an unknown component before comparing published files', function () {
        expect(Artisan::call('april:update', ['components' => ['does-not-exist']]))->toBe(1)
            ->and(Artisan::output())->toContain('Unknown component');
    });
});
