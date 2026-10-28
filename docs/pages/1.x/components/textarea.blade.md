---
view: components.docs-layout
title: Textarea
description: Displays a form textarea or a component that looks like a textarea.
---

<x-component-preview component="previews.textarea-demo"></x-component-preview>

## Set an initial value

Place the initial value inside the component. A textarea reads its value from its content.

<x-code-block-wrapper language="blade">
@verbatim
<april:textarea name="description">{{ old('description', $description) }}</april:textarea>
@endverbatim
</x-code-block-wrapper>

Keep the value next to the opening and closing tags to avoid adding whitespace.
Use escaped Blade output for user content. An empty component still works with `wire:model`.

<x-publish-command view="textarea" />
