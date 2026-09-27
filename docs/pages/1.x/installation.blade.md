---
view: components.docs-layout
title: Installation
description: How to install the project.
---

## Requirements

To install this project, you need:

- A Laravel 12 app
- PHP 8.3 and above
- Tailwind CSS (v4)
- Alpine JS
- A cool project idea

## Installation

To set up the project:

<ul class="flex min-w-0 max-w-full flex-col gap-5">
    <li class="min-w-0 max-w-full wrap-break-word">
        Install the package using Composer
        <x-code-block-wrapper language="bash">
            composer require yungifez/april-ui
        </x-code-block-wrapper>
    </li>
    <li class="min-w-0 max-w-full wrap-break-word">
    In your CSS file, import the project's CSS file
    <x-code-block-wrapper title="app.css" language="css">
        @import "../../vendor/yungifez/april-ui/resources/css/april.css";
    </x-code-block-wrapper>
    </li>
    <li class="min-w-0 max-w-full wrap-break-word">
    In your JS file, import the project's Javascript file
    <x-code-block-wrapper title="app.js" language="js">
        import '/vendor/yungifez/april-ui/dist/april.js'
    </x-code-block-wrapper>
    Or you could use the blade directive
    <x-code-block-wrapper title="layout.blade.php" language="js">
        @verbatim
            @aprilScripts
        @endverbatim
    </x-code-block-wrapper>
    </li>
    <li class="min-w-0 max-w-full wrap-break-word">
    Test the setup was successful
    <x-code-block-wrapper title="index.blade.php" language="blade">
        @verbatim
        <april:button>Hello There Youtube!</april:button>
        @endverbatim
    </x-code-block-wrapper>
    </li>
</ul>

<x-callout>

**Upgrading from an earlier version?** April UI used to need two `repositories` entries in your `composer.json` for a
fork of `tailwind-merge`. You can delete them. The package now uses
[tales-from-a-dev/tailwind-merge-php](https://github.com/tales-from-a-dev/tailwind-merge-php), which comes straight
from Packagist.

</x-callout>

## Configuration

The package works without any config. If you use a custom Tailwind setup, publish the config file:

<x-code-block-wrapper language="bash">
    php artisan vendor:publish --tag=april-ui-config
</x-code-block-wrapper>

The `tailwind_merge` section controls how a component merges its own classes with the classes you pass to it:

<x-code-block-wrapper title="config/april-ui.php" language="php" file="snippets/installation-tailwind-merge.php" />

## Package views and publishing

April UI uses the package views by default. This keeps upgrades simple and follows Laravel's package conventions.

List the available components and their dependencies:

<x-code-block-wrapper language="bash">
    php artisan april:list
</x-code-block-wrapper>

If you need to change a component, publish it to Laravel's normal vendor override path:

<x-code-block-wrapper language="bash">
    php artisan april:publish button
</x-code-block-wrapper>

The published file is copied to `resources/views/vendor/april/components/button.blade.php`. Laravel loads this copy
before the package view. Publish every component with `php artisan april:publish --all`.

Review published components against the package version with:

<x-code-block-wrapper language="bash">
    php artisan april:update --diff
</x-code-block-wrapper>

Use `--dry-run` to inspect changes without writing files. Use `php artisan april:doctor` to find common Blade issues,
such as a typeless button inside a form.

## Optional JavaScript entry points

The `@aprilScripts` directive is the easiest setup. If you manage your JavaScript bundle yourself, import the core
entry point from the Composer package. Use this entry point when Livewire or your app already starts Alpine:

<x-code-block-wrapper title="resources/js/app.js" language="js" file="snippets/installation-app.js" />

The core bundle does not include the optional rich text editor. Add `@aprilEditorScripts` only to layouts that
render `<april:editor>`:

<x-code-block-wrapper title="layout.blade.php" language="blade">
@verbatim
@aprilScripts
@aprilEditorScripts
@endverbatim
</x-code-block-wrapper>

This keeps TipTap and its editor extensions out of pages that do not use the editor.

## MCP server

April UI includes a local MCP server for component discovery and publishing. Add it to the project's MCP client
configuration with:

<x-code-block-wrapper language="bash">
    php artisan april:mcp:install
</x-code-block-wrapper>

The installer adds an `april-ui` server to `.mcp.json` and preserves an existing `laravel-boost` server. For Codex,
use `--codex` to update the project-scoped `.codex/config.toml` instead:

<x-code-block-wrapper language="bash">
    php artisan april:mcp:install --codex
</x-code-block-wrapper>

You can also pass `--config=path/to/mcp.json` when your client stores its configuration elsewhere. A `.toml` path is
handled as a Codex configuration. Restart Codex after changing its configuration. The generated server entry starts
April UI over standard input and output:

<x-code-block-wrapper language="json">
{
  "command": "php",
  "args": ["artisan", "april:mcp"]
}
</x-code-block-wrapper>

The server can list, search, and publish components. Publishing still writes to Laravel's normal
`resources/views/vendor/april/components` path.
