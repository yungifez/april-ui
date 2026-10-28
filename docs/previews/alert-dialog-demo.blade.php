<april:alert-dialog>
    <slot:trigger>
        <april:button variant="outline">Delete account</april:button>
    </slot:trigger>
    <slot:content>
        <april:alert-dialog-header>
            <slot:title>Are you absolutely sure?</slot:title>
            <slot:description>This action cannot be undone. This will permanently delete your account and remove your data from our servers.</slot:description>
        </april:alert-dialog-header>
        <april:alert-dialog-footer>
            <april:alert-dialog-cancel>Cancel</april:alert-dialog-cancel>
            <april:alert-dialog-action type="button">Continue</april:alert-dialog-action>
        </april:alert-dialog-footer>
    </slot:content>
</april:alert-dialog>
