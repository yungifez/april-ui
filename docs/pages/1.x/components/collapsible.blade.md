---
view: components.docs-layout
title: Collapsible
description: An interactive component which expands and collapses a panel.
---

<x-component-preview component="previews.collapsible-demo"></x-component-preview>

## Usage

Use `slot:trigger` for the control and `slot:content` for the panel. The panel stays hidden until the trigger is activated.

<x-code-block-wrapper language="blade">
@verbatim
<april:collapsible>
    <slot:trigger>
        <div class="flex items-center justify-between gap-4">
            <h4 class="text-sm font-semibold">Order #4189</h4>
            <april:button variant="ghost" size="icon" class="size-8" aria-label="Toggle details">
                <x-lucide-chevrons-up-down class="size-4" />
            </april:button>
        </div>
        <div class="mt-2 flex items-center justify-between rounded-md border px-4 py-2 text-sm">
            <span class="text-muted-foreground">Status</span>
            <span class="font-medium">Shipped</span>
        </div>
    </slot:trigger>
    <slot:content>
        <div class="rounded-md border px-4 py-2 text-sm">
            <p class="font-medium">Shipping address</p>
            <p class="text-muted-foreground">100 Market St, San Francisco</p>
        </div>
        <div class="mt-2 rounded-md border px-4 py-2 text-sm">
            <p class="font-medium">Items</p>
            <p class="text-muted-foreground">2x Studio Headphones</p>
        </div>
    </slot:content>
</april:collapsible>
@endverbatim
</x-code-block-wrapper>

## Composition

The component has two named slots:

```text
april:collapsible
├── slot:trigger
└── slot:content
```

Keep the trigger and content in the same `april:collapsible` component. The component connects them with `id`, `aria-controls`, and `aria-labelledby` attributes.

## Controlled state

Use `open` to start expanded. Use `x-model` or `wire:model` when another state owns the value.

<x-code-block-wrapper language="blade" file="snippets/collapsible-model.blade.php" />

The component exposes its state through `x-modelable="open"`, so Alpine and Livewire can update the same value.

## Disabled

Set `disabled` to prevent the trigger from changing the panel state.

<x-code-block-wrapper language="blade">
@verbatim
<april:collapsible disabled>
    <slot:trigger>
        <april:button variant="outline">Unavailable details</april:button>
    </slot:trigger>
    <slot:content>This panel cannot be opened.</slot:content>
</april:collapsible>
@endverbatim
</x-code-block-wrapper>

## Nested panels

Place another `april:collapsible` in the content slot to create a nested panel.

<x-code-block-wrapper language="blade" file="snippets/collapsible-nested.blade.php" />

## API reference

### Props

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `open` | `boolean` | `false` | Starts the panel in the open state. |
| `disabled` | `boolean` | `false` | Prevents the trigger from changing the state. |

### Slots

| Slot | Description |
| --- | --- |
| `trigger` | The button or control that toggles the panel. |
| `content` | The panel content. |

The root, trigger, and content expose `data-state="open"` or `data-state="closed"` for styling. The root exposes `data-disabled` when the component is disabled.

Press Enter or Space on the trigger to toggle the panel.

<x-publish-command view="collapsible" />
