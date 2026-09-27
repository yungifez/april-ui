---
view: components.docs-layout
title: Installation
description: Start a new Laravel app with a starter kit, or add April UI to an app you already have.
---

The fastest way to start is a starter kit. You get a new Laravel app with April UI, Tailwind, Alpine, authentication,
and an application shell already in place. If you already have an app, you can add April UI by hand in a few steps.

## Start with a starter kit

Pick the kit that matches how you build. Use the Blade kit for controllers, forms, and server-rendered views:

<x-code-block-wrapper title="Blade" language="bash">
laravel new my-app --using=yungifez/april-ui-blade-starter-kit
</x-code-block-wrapper>

Use the Livewire kit for Livewire page components:

<x-code-block-wrapper title="Livewire" language="bash">
laravel new my-app --using=yungifez/april-ui-starter-kit
</x-code-block-wrapper>

Then start the app:

<x-code-block-wrapper language="bash">
cd my-app
composer dev
</x-code-block-wrapper>

That's it. See [Starter kits](/docs/1.x/starter-kits) for what each kit includes.

## Add to an existing app

Your app needs:

- Laravel 12 or 13
- PHP 8.3 or later
- Tailwind CSS 4
- Alpine.js. Livewire already includes it.

### 1. Install the package

<x-code-block-wrapper language="bash">
composer require yungifez/april-ui
</x-code-block-wrapper>

### 2. Import the styles

Import April UI after Tailwind in your CSS file. April UI tells Tailwind where its views are, so you do not need to add
a `@source` line.

<x-code-block-wrapper title="resources/css/app.css" language="css">
@import "tailwindcss";
@import "../../vendor/yungifez/april-ui/resources/css/april.css";
</x-code-block-wrapper>

### 3. Load the scripts

Add `@aprilScripts` to the `<head>` of your layout. It registers April UI with Alpine before Alpine starts.

<x-code-block-wrapper title="resources/views/components/layouts/app.blade.php" language="blade">
@verbatim
@aprilScripts
@endverbatim
</x-code-block-wrapper>

The rich text editor ships separately, so pages without it stay light. Add `@aprilEditorScripts` only to layouts
that render `<april:editor>`:

<x-code-block-wrapper title="resources/views/components/layouts/app.blade.php" language="blade">
@verbatim
@aprilScripts
@aprilEditorScripts
@endverbatim
</x-code-block-wrapper>

To put April UI in your own bundle instead, see **Bundle the scripts yourself** below.

### 4. Try a component

<x-code-block-wrapper title="resources/views/welcome.blade.php" language="blade">
@verbatim
<april:button>Hello, April</april:button>
@endverbatim
</x-code-block-wrapper>

Run `npm run dev` and open the page. You should see a styled button.

## Bundle the scripts yourself

Use this when you want April UI in your own Vite bundle instead of `@aprilScripts`. Import the core entry point from
the Composer package and register it when Alpine initializes:

<x-code-block-wrapper title="resources/js/app.js" language="js" file="snippets/installation-app.js" />

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
