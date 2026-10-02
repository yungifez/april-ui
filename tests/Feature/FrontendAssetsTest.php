<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

describe('asset routes', function () {
    it('registers a named route', function (string $name) {
        expect(Route::has("april-ui.{$name}"))->toBeTrue();
    })->with(['april.js', 'april.min.js', 'editor.js', 'editor.min.js', 'april.css', 'april.min.css']);

    it('serves the file', function (string $name) {
        $this->get(route("april-ui.{$name}"))->assertOk();
    })->with(['april.js', 'april.min.js', 'editor.js', 'editor.min.js', 'april.css', 'april.min.css']);

    it('serves javascript with a javascript content type', function (string $name) {
        $this->get(route("april-ui.{$name}"))
            ->assertHeader('Content-Type', 'text/javascript; charset=utf-8');
    })->with(['april.js', 'april.min.js', 'editor.js', 'editor.min.js']);

    it('serves css with a css content type', function (string $name) {
        $this->get(route("april-ui.{$name}"))
            ->assertHeader('Content-Type', 'text/css; charset=utf-8');
    })->with(['april.css', 'april.min.css']);

    it('serves the same bytes as the file on disk', function (string $name) {
        $response = $this->get(route("april-ui.{$name}"));

        expect($response->streamedContent())
            ->toBe(file_get_contents(__DIR__."/../../dist/{$name}"));
    })->with(['april.js', 'april.css']);

    it('ships the rule that hides cloaked elements before alpine starts', function () {
        $response = $this->get(route('april-ui.april.css'));

        expect($response->streamedContent())->toContain('[x-cloak]');
    });

    it('serves the alpine behaviours in the javascript bundle', function () {
        $response = $this->get(route('april-ui.april.js'));

        expect($response->streamedContent())->toContain('Alpine.data(name, resolve(name)');
    });

    it('uses the april-ui url prefix', function (string $name) {
        expect(route("april-ui.{$name}", absolute: false))->toBe("/april-ui/{$name}");
    })->with(['april.js', 'april.min.js', 'editor.js', 'editor.min.js', 'april.css', 'april.min.css']);
});

describe('the aprilStyles directive', function () {
    it('links a stylesheet', function () {
        expect(render('@aprilStyles'))
            ->toContain('<link rel="stylesheet"')
            ->toContain('/april-ui/april.min.css');
    });

    it('adds the manifest hash as a cache buster', function () {
        clearCompiledViews();
        $manifest = json_decode(file_get_contents(__DIR__.'/../../dist/manifest.json'), true);

        expect(render(' @aprilStyles'))->toContain('?ver='.$manifest['/april.css']);
    });

    it('links the readable stylesheet when debug is on', function () {
        config()->set('app.debug', true);

        expect(render('  @aprilStyles'))
            ->toContain('/april-ui/april.css')
            ->not->toContain('april.min.css');
    });
});

describe('the aprilScripts directive', function () {
    it('adds a script tag', function () {
        expect(render('@aprilScripts'))
            ->toContain('<script src=')
            ->toContain('/april-ui/april.min.js');
    });

    it('adds the manifest hash as a cache buster', function () {
        clearCompiledViews();
        $manifest = json_decode(file_get_contents(__DIR__.'/../../dist/manifest.json'), true);

        expect(render(' @aprilScripts'))->toContain('?ver='.$manifest['/april.js']);
    });

    it('links the readable bundle when debug is on', function () {
        config()->set('app.debug', true);

        expect(render('  @aprilScripts'))
            ->toContain('/april-ui/april.js')
            ->not->toContain('april.min.js');
    });

    it('can be used together with the styles directive', function () {
        $html = render('@aprilStyles @aprilScripts');

        expect($html)->toContain('<link')->toContain('<script');
    });
});

describe('the aprilEditorScripts directive', function () {
    it('links the optional editor bundle', function () {
        expect(render('@aprilEditorScripts'))
            ->toContain('<script src=')
            ->toContain('/april-ui/editor.min.js');
    });

    it('adds the editor manifest hash as a cache buster', function () {
        clearCompiledViews();
        $manifest = json_decode(file_get_contents(__DIR__.'/../../dist/manifest.json'), true);

        expect(render(' @aprilEditorScripts'))->toContain('?ver='.$manifest['/editor.js']);
    });
});

describe('a compiled asset directive', function () {
    it('follows the host that serves the page', function (string $directive) {
        clearCompiledViews();
        URL::forceRootUrl('http://first.test');
        render($directive);

        URL::forceRootUrl('http://second.test:8081');

        expect(render($directive))
            ->toContain('http://second.test:8081/april-ui/')
            ->not->toContain('first.test');
    })->with(['@aprilStyles', '@aprilScripts', '@aprilEditorScripts']);

    it('follows the debug setting in force now', function () {
        clearCompiledViews();
        config()->set('app.debug', false);
        render('@aprilEditorScripts');

        config()->set('app.debug', true);

        expect(render('@aprilEditorScripts'))
            ->toContain('/april-ui/editor.js')
            ->not->toContain('editor.min.js');
    });
});
