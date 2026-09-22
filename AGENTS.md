# April UI agent guide

April UI is a Laravel Blade component library. Keep changes Blade-first and
preserve Laravel conventions.

## Repository scope

- Change components, Alpine behavior, package assets, tests, docs, and previews in this repository.
- Keep interactive behavior in the component JavaScript files.
- Use Alpine data factories that return `root`, slot bindings, `init`, and public methods.
- Load optional dependencies only on pages that use them. TipTap is the current example.
- Put static layout and visual styles in Tailwind classes in the component view.
- Keep CSS selectors for generated markup, pseudo-elements, or behavior that Tailwind cannot express clearly.
- Preserve attribute forwarding and `twMerge` behavior.
- Give a unique name to anything one component reaches for in another scope. Alpine resolves a bare name to the
  nearest scope that owns it, so `close()` reaches whichever of the eight owners sits closest, not the one you meant.
  `subOpen`, `closeSub` and `closeDropdownMenu` are named this way on purpose. Set the state in such a method rather
  than calling a shorter one, or the second call lands in the nearest scope all over again.
- Keep a component's own expressions on its own elements. The sidebar is the exception, because the reader places its
  trigger, rail and menu buttons anywhere on the page, so it keeps everything behind a `sidebar` receiver.
- Do not edit `vendor/` files in the docs repository.

## Contribution workflow

1. Inspect the current worktree before editing.
2. Create a short-lived branch from `main`.
3. Change the component view, behavior, tests, and package docs together.
4. Build assets after changing JavaScript or CSS.
5. Run the focused tests, then run the full checks before opening a pull request.

Use these commands:

```sh
composer install
npm ci
npm run build
npm run test:browser -- --workers=1
vendor/bin/phpstan analyse --no-progress
vendor/bin/pest --no-coverage
```

Use Conventional Commits:

- `fix:` creates a patch release.
- `feat:` creates a minor release.
- `feat!:` creates a major release.
- `docs:`, `test:`, and `chore:` do not create a release.

Use an imperative subject under 50 characters. Add tests for behavior changes.

## Release workflow

1. Push the completed change to `main`.
2. GitHub Actions runs the checks and Release Please.
3. Release Please opens or updates the release pull request.
4. Review the version, changelog, package assets, and checks.
5. Merge the release pull request.
6. Release Please creates the tag and GitHub release.
7. Dependabot updates the published docs site to the new package version.

Do not edit the package version, tag, or changelog by hand during a normal
release. Do not run the manual release workflow while a Release Please pull
request is pending for the same changes.

Release Please reads commit types. Non-conventional commits remain in the
package code but do not appear in the changelog or trigger a version bump.
Use `fix:` or `feat:` when a code change must ship in a release.

Use the manual `Release` workflow only when the automated release process is
blocked or a recovery release is required.

## Docs repositories

The published docs repository uses released Composer packages. The local docs
checkout uses a Composer path repository and symlinks to `../april-ui`.

When testing package changes in the local docs checkout:

```sh
cd ../april-ui-docs-local
git switch local-april-ui
composer update yungifez/april-ui --with-dependencies
npm run build
```

Do not push the local docs branch. Push published docs changes only from the
stable docs repository.

## Blade component content

- Use the default `{{$slot}}` when the content is the main part of a component and the component accepts one item. For example, a button's label belongs in `<april:button>Inside</april:button>`.
- Use a named slot for one auxiliary item that is not the component's main content, such as a dialog's `title` or a card's `footer`.
- When a region can contain multiple related items, create a dedicated child component for that collection and compose those children through the parent's default slot. Do not encode a list as a named slot.

## Sidebar

- `sidebar-trigger` uses its default slot for a custom trigger icon because the icon is the trigger's primary content. It falls back to the standard icon when the default slot is empty.
- `sidebar` accepts named `header`, `content`, and `footer` slots for its layout regions. Keep the sidebar rail and other single structural parts in the default slot.
- Sidebar menu collections use child components through the `content` slot. Use `sidebar-menu`, `sidebar-menu-item`, and the other sidebar parts for repeated content.
