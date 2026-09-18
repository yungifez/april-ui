---
view: components.docs-layout
title: Editor
description: A rich text editor with a configurable toolbar.
assets:
  - editor
---

<x-component-preview component="previews.editor-demo"></x-component-preview>

Add `@aprilEditorScripts` after `@aprilScripts` in the layout that renders the editor. The editor bundle is optional so
pages that do not use `<april:editor>` do not load TipTap.

## Usage

The editor stores HTML. Use `name` for native form submission. Use `wire:model` or `x-model` for a controlled value. It dispatches `value-change` with the updated HTML; the existing `input` and `change` events remain available.

<x-code-block-wrapper language="blade">
@verbatim
<april:editor
    name="message"
    wire:model.live="message"
    placeholder="Write a message..."
    bold
    italic
    heading
    bullet-list
    ordered-list
    link
    undo
    redo
/>
@endverbatim
</x-code-block-wrapper>

The editor updates its model when the content changes. It accepts an HTML string through `value`.

## Toolbar props

Use a prop for each toolbar control. A present prop enables the control. Set the prop to `false` to disable it.

| Prop | Default | Description |
| --- | --- | --- |
| `bold` | `true` | Toggle bold text. |
| `italic` | `true` | Toggle italic text. |
| `strike` | `true` | Toggle struck text. |
| `heading` | `true` | Toggle a level-two heading. |
| `bullet-list` | `true` | Toggle a bullet list. |
| `ordered-list` | `true` | Toggle a numbered list. |
| `blockquote` | `true` | Toggle a blockquote. |
| `code-block` | `true` | Toggle a code block. |
| `link` | `true` | Add or remove a link. |
| `horizontal-rule` | `true` | Add a horizontal rule. |
| `undo` | `true` | Undo the last change. |
| `redo` | `true` | Redo the last undone change. |

Use `buttons` when the toolbar comes from application data. The array accepts the same names in camel case or kebab case.

<x-code-block-wrapper language="blade">
@verbatim
<april:editor :buttons="['bold', 'italic', 'bullet-list', 'undo', 'redo']" />
@endverbatim
</x-code-block-wrapper>

When you use `buttons`, individual props can still override a control:

```blade
<april:editor :buttons="['bold', 'italic']" :italic="false" />
```

## API reference

### Props

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `name` | `string` | `''` | Adds a hidden named input for form submission. |
| `value` | `string` | `''` | Sets the initial HTML value. |
| `placeholder` | `string` | `Write something...` | Sets the content region placeholder data. |
| `buttons` | `array` | `null` | Sets the toolbar controls. |
| `disabled` | `boolean` | `false` | Prevents editing and toolbar actions. |

The component exposes its HTML value through `x-modelable="value"`.

<x-publish-command view="editor" />
