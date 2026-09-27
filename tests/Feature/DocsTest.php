<?php

use Illuminate\Support\Facades\Blade;

/*
|--------------------------------------------------------------------------
| Docs
|--------------------------------------------------------------------------
|
| The docs pages and the previews ship with the package, so they are checked
| here. A preview that stops rendering is a broken page on the website, and a
| component with no page is a gap a reader will fall into.
|
*/

function docsPath(string $path = ''): string
{
    $base = realpath(__DIR__.'/../../docs');

    return $path === '' ? $base : $base.'/'.$path;
}

/**
 * @return list<string>
 */
function previewNames(): array
{
    $names = array_map(
        fn (string $file): string => basename($file, '.blade.php'),
        glob(docsPath('previews').'/*.blade.php')
    );

    sort($names);

    return $names;
}

/**
 * @return list<string>
 */
function documentedComponents(): array
{
    $names = array_map(
        fn (string $file): string => basename($file, '.blade.md'),
        glob(docsPath('pages/1.x/components').'/*.blade.md')
    );

    sort($names);

    return $names;
}

/**
 * @return list<string>
 */
function allDocsPages(): array
{
    return array_merge(
        glob(docsPath('pages/1.x').'/*.blade.md'),
        glob(docsPath('pages/1.x/components').'/*.blade.md')
    );
}

dataset('previews', fn () => previewNames());

describe('previews', function () {
    it('ships previews with the package', function () {
        expect(previewNames())->not->toBeEmpty();
    });

    it('renders', function (string $name) {
        expect(Blade::render(file_get_contents(docsPath("previews/{$name}.blade.php"))))
            ->toBeString();
    })->with('previews');

    it('produces markup', function (string $name) {
        expect(trim(Blade::render(file_get_contents(docsPath("previews/{$name}.blade.php")))))
            ->not->toBeEmpty()
            ->toContain('<');
    })->with('previews');

    it('leaves no unresolved blade behind', function (string $name) {
        expect(Blade::render(file_get_contents(docsPath("previews/{$name}.blade.php"))))
            ->not->toContain('@endComponentClass')
            ->not->toContain('<?php');
    })->with('previews');

    it('only uses components that exist', function (string $name) {
        preg_match_all('/<\/?april:([a-z0-9-]+)/', file_get_contents(docsPath("previews/{$name}.blade.php")), $matches);

        expect(array_diff(array_unique($matches[1]), componentNames()))
            ->toBeEmpty("The {$name} preview uses a component that does not exist.");
    })->with('previews');
});

describe('pages', function () {
    it('ships pages with the package', function () {
        expect(documentedComponents())->not->toBeEmpty();
    });

    it('names a page after a real component', function (string $page) {
        expect(componentNames())->toContain($page);
    })->with(fn () => documentedComponents());

    it('starts every page with front matter', function (string $page) {
        expect(file_get_contents(docsPath("pages/1.x/components/{$page}.blade.md")))
            ->toStartWith("---\nview: components.docs-layout");
    })->with(fn () => documentedComponents());

    it('gives every page a title and a description', function (string $page) {
        expect(file_get_contents(docsPath("pages/1.x/components/{$page}.blade.md")))
            ->toContain('title:')
            ->toContain('description:');
    })->with(fn () => documentedComponents());

    it('only points at previews that exist', function (string $page) {
        preg_match_all(
            '/component="previews\.([a-z0-9-]+)"/',
            file_get_contents(docsPath("pages/1.x/components/{$page}.blade.md")),
            $matches
        );

        expect(array_diff(array_unique($matches[1]), previewNames()))
            ->toBeEmpty("The {$page} page points at a preview that does not exist.");
    })->with(fn () => documentedComponents());

    it('only offers publish tags that exist', function (string $page) {
        preg_match_all(
            "/'([a-z0-9-]+)'/",
            (string) strstr(file_get_contents(docsPath("pages/1.x/components/{$page}.blade.md")), '<x-publish-command'),
            $matches
        );

        expect(array_diff(array_unique($matches[1]), componentNames()))
            ->toBeEmpty("The {$page} page offers a publish tag for a component that does not exist.");
    })->with(fn () => documentedComponents());

    it('does not tell people to add the old tailwind merge fork', function () {
        expect(file_get_contents(docsPath('pages/1.x/installation.blade.md')))
            ->not->toContain('tailwind-merge-laravel')
            ->not->toContain('tailwind-merge-php.git');
    });

    it('keeps blank lines out of inline code blocks', function (string $page) {
        preg_match_all(
            '/<x-code-block-wrapper\b[^>]*(?<!\/)>(.*?)<\/x-code-block-wrapper>/s',
            file_get_contents($page),
            $matches
        );

        $blocks = array_filter($matches[1], fn (string $code): bool => (bool) preg_match('/\n[ \t]*\n/', trim($code, "\n")));

        expect($blocks)->toBeEmpty(
            basename($page).' has a blank line in a code block. Markdown ends the block there. Move the code to docs/snippets.'
        );
    })->with(fn () => allDocsPages());

    it('only points at snippets that exist', function (string $page) {
        preg_match_all('/file="(snippets\/[^"]+)"/', file_get_contents($page), $matches);

        $missing = array_filter($matches[1], fn (string $snippet): bool => ! is_file(docsPath($snippet)));

        expect($missing)->toBeEmpty(basename($page).' points at a snippet that does not exist.');
    })->with(fn () => allDocsPages());

    it('imports the javascript from the composer package', function () {
        expect(file_get_contents(docsPath('pages/1.x/installation.blade.md')).file_get_contents(docsPath('snippets/installation-app.js')))
            ->not->toContain("from 'april-ui/")
            ->toContain('vendor/yungifez/april-ui/resources/js/april-core.js');
    });

    it('uses markdown-safe links in the introduction callout', function () {
        expect(file_get_contents(docsPath('pages/1.x/index.blade.md')))
            ->toContain('[installation guide](/docs/1.x/installation)')
            ->toContain('[starter kits](/docs/1.x/starter-kits)')
            ->toContain('[blocks](/blocks)')
            ->not->toContain('[installation guide]({{url(');
    });
});
