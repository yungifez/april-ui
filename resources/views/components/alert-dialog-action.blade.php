@props(['type' => 'button'])

<button type="{{$type}}" data-slot="alert-dialog-action" x-bind="action"
    {{$attributes->twMerge(['inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50'])}}>
    {{$slot}}
</button>
