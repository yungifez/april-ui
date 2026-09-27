---
view: components.docs-layout
title: Sidebar
description: A composable, collapsible sidebar for application layouts.
---

<x-component-preview component="previews.sidebar-demo"></x-component-preview>

The sidebar is not one component. It is a set of parts you put together, so you can build the layout your app needs
instead of the one I guessed you needed.

## Anatomy

Wrap everything in `sidebar-layout`. It holds the open state, so the sidebar, the trigger and the page content all
agree on whether the sidebar is open.

<x-code-block-wrapper title="layout.blade.php" language="blade" file="snippets/sidebar-layout.blade.php" />

<x-callout>
Put `sidebar-inset` next to the sidebar, not inside it. It holds your page content and shifts as the sidebar opens
and closes.
</x-callout>

---

## Menus

A menu is a list. Use `sidebar-menu-button-link` for a link and `sidebar-menu-button` for a button. Mark the current
page with `:active="true"`.

Add a badge for a count, an action for a row menu, and a submenu for children. The skeleton is there for while your
data loads.

<x-component-preview component="previews.sidebar-menu-demo"></x-component-preview>

While the sidebar shows icons only, a menu button has no room for its label. Pass `tooltip` to give it one. The
tooltip stays silent while the sidebar is open, because the button already says the same words.

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar-menu-button tooltip="Boarding">
    <x-lucide-bed-double />
    <span>Boarding</span>
</april:sidebar-menu-button>
@endverbatim
</x-code-block-wrapper>

A menu button opens the sidebar when you click it. This is safe inside another component, because the button reaches
the sidebar by name: a submenu trigger opens the sidebar and its own children on the same click. Pass
`:expand-sidebar="false"` when a button must leave the sidebar alone:

<x-code-block-wrapper language="blade">
@verbatim
<april:collapsible>
    <slot:trigger>
        <april:sidebar-menu-button tooltip="Boarding">
            <x-lucide-bed-double />
            <span>Boarding</span>
        </april:sidebar-menu-button>
    </slot:trigger>
    <slot:content>...</slot:content>
</april:collapsible>
@endverbatim
</x-code-block-wrapper>

<x-callout>
`sidebar-menu-action` is hidden until you hover the row when you pass `:show-on-hover="true"`.
</x-callout>

---

## Collapsible

The `collapsible` attribute decides what happens when the sidebar closes. It takes three values.

### Offcanvas

The default. The sidebar slides off the screen.

<x-component-preview component="previews.sidebar-collapsible-icon-demo"></x-component-preview>

---

### Icon

The sidebar shrinks to icon width and keeps the icons visible. Labels, badges, actions and submenus hide themselves.

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar collapsible="icon">...</april:sidebar>
@endverbatim
</x-code-block-wrapper>

---

### None

The sidebar never collapses. Use it when you want a plain, always open panel.

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar collapsible="none">...</april:sidebar>
@endverbatim
</x-code-block-wrapper>

---

## Side

Use the `side` attribute to put the sidebar on the right. The border, the rail and the mobile panel all follow.

<x-component-preview component="previews.sidebar-side-demo"></x-component-preview>

---

## Variants

The `variant` attribute takes `sidebar`, `floating` or `inset`. `floating` rounds the panel and gives it a border.
`inset` pulls the page content in and rounds it instead.

<x-component-preview component="previews.sidebar-variant-demo"></x-component-preview>

---

## Search

`sidebar-input` is the input component with the sidebar sizing already applied.

<x-component-preview component="previews.sidebar-search-demo"></x-component-preview>

---

## Opening and closing

Three things toggle the sidebar:

- `sidebar-trigger`, a button you can put anywhere inside the layout
- `sidebar-rail`, the thin strip along the sidebar edge
- <kbd>Ctrl</kbd> + <kbd>B</kbd>, or <kbd>Cmd</kbd> + <kbd>B</kbd> on a Mac

A menu button opens the sidebar as well, unless you pass `:expand-sidebar="false"`.

On a screen narrower than `768px` the sidebar becomes a panel that slides in over the page. The same three things
still open it.

Start the sidebar closed with `:default-open="false"`.

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar-layout :default-open="false">...</april:sidebar-layout>
@endverbatim
</x-code-block-wrapper>

---

## Reading the sidebar state

The layout keeps the whole sidebar under one `sidebar` name. Reach for it with that receiver, the way you would call a
hook:

<x-code-block-wrapper language="blade">
@verbatim
<button type="button" x-on:click="sidebar.toggle()">Toggle</button>
<span x-show="sidebar.state === 'collapsed'">Icons only</span>
@endverbatim
</x-code-block-wrapper>

| Name | What it holds |
| --- | --- |
| `sidebar.open` | The desktop state. `sidebar-layout` binds it with `x-modelable`. |
| `sidebar.openMobile` | The mobile panel. It is never stored. |
| `sidebar.isMobile` | True below `768px`. |
| `sidebar.state` | `expanded` or `collapsed`. |
| `sidebar.isOpen()` | The state of whichever panel the screen width uses. |
| `sidebar.toggle()` | Flip the state. |
| `sidebar.show()` | Open it. |
| `sidebar.close()` | Close it. |

The name is the point. Alpine reads a bare name against the scopes of the element that holds the expression, so `open`
inside a collapsible means the collapsible, not the sidebar. The sidebar wraps your whole page, so a bare name would
sooner or later meet a component that owns the same one.

---

## Persisting state

To keep the sidebar open or collapsed across page loads, pass `x-persist` with a unique key. The component applies the
`x-persist` directive to both the desktop and mobile sidebars for you.

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar x-persist="app-sidebar">...</april:sidebar>
@endverbatim
</x-code-block-wrapper>

---

## Width

The sidebar reads three CSS variables. Set them on `sidebar-layout` to change the width for one sidebar:

<x-code-block-wrapper language="blade">
@verbatim
<april:sidebar-layout width="20rem" width-icon="4rem">...</april:sidebar-layout>
@endverbatim
</x-code-block-wrapper>

Or set them in your CSS to change every sidebar:

<x-code-block-wrapper title="app.css" language="css">
:root {
    --sidebar-width: 16rem;
    --sidebar-width-icon: 3rem;
    --sidebar-width-mobile: 18rem;
}
</x-code-block-wrapper>

---

## Styling from your own CSS

Every part carries a `data-slot` attribute, and the sidebar carries its state. You can style any part without
touching the markup:

<x-code-block-wrapper title="app.css" language="css" file="snippets/sidebar-styling.css" />

See [Data attributes](/docs/1.x/data-attributes) for the full list.

<x-publish-command :views="[
    'sidebar-layout',
    'sidebar',
    'sidebar-trigger',
    'sidebar-rail',
    'sidebar-inset',
    'sidebar-input',
    'sidebar-separator',
    'sidebar-group',
    'sidebar-group-label',
    'sidebar-group-action',
    'sidebar-group-content',
    'sidebar-menu',
    'sidebar-menu-item',
    'sidebar-menu-button',
    'sidebar-menu-button-link',
    'sidebar-menu-action',
    'sidebar-menu-badge',
    'sidebar-menu-skeleton',
    'sidebar-menu-sub',
    'sidebar-menu-sub-item',
    'sidebar-menu-sub-button',
]" />
