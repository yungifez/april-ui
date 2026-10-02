<?php

namespace Yungifez\AprilUI\Handlers;

use Illuminate\Support\Facades\Blade;

class FrontendAssetsHandler
{
    /**
     * @var array<string, string>|null
     */
    protected static ?array $manifestHashes = null;

    public function boot()
    {
        Blade::directive('aprilStyles', fn () => '<?php echo \\'.static::class.'::styles(); ?>');
        Blade::directive('aprilScripts', fn () => '<?php echo \\'.static::class.'::scripts(); ?>');
        Blade::directive('aprilEditorScripts', fn () => '<?php echo \\'.static::class.'::editorScripts(); ?>');
    }

    /**
     * Build the tag on each request, so the URL follows the host that serves the page
     * and the debug setting in force now, not the ones present when the view compiled.
     */
    public static function styles(): string
    {
        return '<link rel="stylesheet" href="'.static::url('april', 'css').'">';
    }

    public static function scripts(): string
    {
        return '<script src="'.static::url('april', 'js').'"></script>';
    }

    public static function editorScripts(): string
    {
        return '<script src="'.static::url('editor', 'js').'"></script>';
    }

    protected static function url(string $bundle, string $extension): string
    {
        $name = config('app.debug') ? "{$bundle}.{$extension}" : "{$bundle}.min.{$extension}";

        return route("april-ui.{$name}").'?ver='.(static::manifestHashes()["/{$bundle}.{$extension}"] ?? '');
    }

    /**
     * @return array<string, string>
     */
    protected static function manifestHashes(): array
    {
        return static::$manifestHashes ??= json_decode(file_get_contents(__DIR__.'/../../dist/manifest.json'), true);
    }
}
