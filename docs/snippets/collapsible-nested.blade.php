<april:collapsible>
    <slot:trigger><april:button variant="ghost">Project details</april:button></slot:trigger>
    <slot:content>
        <p>Project description.</p>

        <april:collapsible>
            <slot:trigger><april:button variant="ghost">Advanced details</april:button></slot:trigger>
            <slot:content>Advanced project settings.</slot:content>
        </april:collapsible>
    </slot:content>
</april:collapsible>
