---
view: components.docs-layout
title: Data attributes
description: Style any part of a component from your own CSS.
---

Every component marks its parts with `data-slot`, and every component that has state reports it with `data-state`.
Both use the same names as ShadCn, so a stylesheet written for ShadCn works here.

This means you can restyle a component without publishing it and without touching Alpine.

## Slots

`data-slot` names a part. A component with one element has one slot. A component built from several elements names
each one:

<x-code-block-wrapper title="Rendered card" language="html">
<div data-slot="card">
    <div data-slot="card-header">
        <h2 data-slot="card-title">Billing</h2>
        <p data-slot="card-description">Your plan renews monthly.</p>
    </div>
    <div data-slot="card-content">...</div>
    <div data-slot="card-footer">...</div>
</div>
</x-code-block-wrapper>

Target a part from your CSS:

<x-code-block-wrapper title="app.css" language="css">
[data-slot="card-title"] {
    letter-spacing: -0.02em;
}
</x-code-block-wrapper>

Or from a Tailwind class on a parent:

<x-code-block-wrapper language="blade">
@verbatim
<april:card class="[&_[data-slot=card-title]]:text-primary">...</april:card>
@endverbatim
</x-code-block-wrapper>

<x-callout>
When a component wraps another one, the outer name wins. `sidebar-input` renders an input with
`data-slot="sidebar-input"`, not `data-slot="input"`.
</x-callout>

---

## State

`data-state` says what the component is doing. The value depends on the component:

<x-code-block-wrapper language="html" file="snippets/data-attributes-state.html" />

A disabled control also gets `data-disabled`.

The starting state is in the HTML the server sends, so the page looks right before Alpine boots. Alpine keeps the
attribute current after that.

### Using it

<x-code-block-wrapper title="app.css" language="css">
[data-slot="dialog-content"][data-state="open"] {
    animation: fade-in 150ms ease-out;
}
</x-code-block-wrapper>

Tailwind can do the same with the `data-` variant:

<x-code-block-wrapper language="blade">
@verbatim
<april:switch class="data-[state=checked]:ring-2" />
@endverbatim
</x-code-block-wrapper>

---

## Which components report state

| Component | Attribute | Values |
| --- | --- | --- |
| accordion-item | `data-state` | open, closed |
| alert | `data-state` | open, closed |
| banner | `data-state` | open, closed |
| date-picker | `data-state` | open, closed |
| dialog | `data-state` | open, closed |
| dropdown-menu | `data-state` | open, closed |
| dropdown-menu-sub | `data-state` | open, closed |
| popover | `data-state` | open, closed |
| select | `data-state`, `data-disabled` | open, closed |
| sheet | `data-state` | open, closed |
| sidebar | `data-state`, `data-side`, `data-variant`, `data-collapsible` | expanded, collapsed |
| switch | `data-state`, `data-disabled` | checked, unchecked |
| tabs-trigger | `data-state` | active, inactive |
| tabs-content | `data-state` | active, inactive |
| tooltip | `data-state` | open, closed |

<x-callout>

Animation classes such as `animate-in` and `slide-in-from-right` are not part of Tailwind. If you want them, install
[tw-animate-css](https://github.com/Wombosvideo/tw-animate-css). Without it those classes produce no CSS. The
components animate with Alpine transitions either way.

</x-callout>
