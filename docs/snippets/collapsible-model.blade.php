<div x-data="{ detailsOpen: false }">
    <april:collapsible x-model="detailsOpen">
        <slot:trigger>
            <april:button variant="outline">Toggle details</april:button>
        </slot:trigger>
        <slot:content>These details follow the Alpine state.</slot:content>
    </april:collapsible>
</div>

<april:collapsible wire:model="detailsOpen">
    <slot:trigger><april:button>Toggle details</april:button></slot:trigger>
    <slot:content>These details follow the Livewire property.</slot:content>
</april:collapsible>
