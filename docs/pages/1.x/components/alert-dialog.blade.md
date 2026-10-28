---
view: components.docs-layout
title: Alert Dialog
description: A modal dialog that interrupts the user with important content and expects a response.
---

<x-component-preview component="previews.alert-dialog-demo"></x-component-preview>

Use an alert dialog for destructive or consequential actions. The dialog stays open until the user chooses an action.

<x-code-block-wrapper language="blade">
@verbatim
<april:alert-dialog>
    <slot:trigger><april:button>Delete account</april:button></slot:trigger>
    <slot:content>
        <april:alert-dialog-header>
            <slot:title>Delete account?</slot:title>
            <slot:description>This action cannot be undone.</slot:description>
        </april:alert-dialog-header>
        <april:alert-dialog-footer>
            <april:alert-dialog-cancel>Cancel</april:alert-dialog-cancel>
            <april:alert-dialog-action>Delete</april:alert-dialog-action>
        </april:alert-dialog-footer>
    </slot:content>
</april:alert-dialog>
@endverbatim
</x-code-block-wrapper>

The `trigger`, `content`, `title`, and `description` regions are named slots. The footer uses its default slot because it can contain multiple actions.

## Submit a form

The action uses `type="button"` by default. Set `type="submit"` to submit a form after confirmation.
Use `form` to name the form when the dialog content is outside it, including teleported content.

<x-code-block-wrapper language="blade">
@verbatim
<form id="delete-program" method="POST" action="/programs/1">
    @csrf
    @method('DELETE')
</form>
<april:alert-dialog x-teleport="body">
    <slot:trigger><april:button>Delete program</april:button></slot:trigger>
    <slot:content>
        <april:alert-dialog-header>
            <slot:title>Delete this program?</slot:title>
        </april:alert-dialog-header>
        <april:alert-dialog-footer>
            <april:alert-dialog-cancel>Cancel</april:alert-dialog-cancel>
            <april:alert-dialog-action type="submit" form="delete-program">Delete</april:alert-dialog-action>
        </april:alert-dialog-footer>
    </slot:content>
</april:alert-dialog>
@endverbatim
</x-code-block-wrapper>

<x-publish-command :views="['alert-dialog', 'alert-dialog-header', 'alert-dialog-footer', 'alert-dialog-action', 'alert-dialog-cancel']" />
