---
view: components.docs-layout
title: Introduction
description: A Laravel-first component library for building polished interfaces with Blade.
---

<div class="not-prose overflow-hidden rounded-xl border bg-card shadow-sm">
    <div class="border-b bg-muted/30 px-6 py-5 sm:px-8">
        <div class="flex flex-wrap items-center gap-2 text-xs font-medium uppercase tracking-[0.16em] text-muted-foreground">
            <span class="rounded-full border bg-background px-2.5 py-1">April UI 1.x</span>
            <span>Blade · Alpine · Livewire</span>
        </div>
        <h2 class="mt-5 max-w-2xl border-0 pb-0 text-3xl font-bold tracking-tight sm:text-4xl">Build polished interfaces without leaving Laravel.</h2>
        <p class="mt-4 max-w-2xl text-base leading-7 text-muted-foreground">April UI brings the component quality of shadcn/ui to Blade, with Alpine for browser state, Livewire when the server should stay involved, and Tailwind for the final say.</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{url('docs/1.x/installation')}}" class="inline-flex h-10 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground no-underline transition-colors hover:bg-primary/90">Get started <x-lucide-arrow-right class="ml-2 size-4" /></a>
            <a href="{{url('blocks')}}" class="inline-flex h-10 items-center justify-center rounded-md border bg-background px-4 text-sm font-medium no-underline transition-colors hover:bg-accent">Browse blocks</a>
        </div>
    </div>
    <div class="grid gap-0 divide-y sm:grid-cols-3 sm:divide-x sm:divide-y-0">
        <a href="{{url('docs/1.x/starter-kits')}}" class="group p-5 no-underline transition-colors hover:bg-muted/30">
            <span class="font-mono text-xs text-primary">01</span>
            <strong class="mt-2 block text-sm text-foreground">Start with a kit</strong>
            <span class="mt-1 block text-sm leading-6 text-muted-foreground">Choose a Blade or Livewire application shell.</span>
        </a>
        <a href="{{url('docs/1.x/installation')}}" class="group p-5 no-underline transition-colors hover:bg-muted/30">
            <span class="font-mono text-xs text-primary">02</span>
            <strong class="mt-2 block text-sm text-foreground">Install the package</strong>
            <span class="mt-1 block text-sm leading-6 text-muted-foreground">Add April UI to an existing Laravel application.</span>
        </a>
        <a href="{{url('docs/1.x/components/button')}}" class="group p-5 no-underline transition-colors hover:bg-muted/30">
            <span class="font-mono text-xs text-primary">03</span>
            <strong class="mt-2 block text-sm text-foreground">Browse components</strong>
            <span class="mt-1 block text-sm leading-6 text-muted-foreground">See the API, preview the behavior, and copy the markup.</span>
        </a>
    </div>
</div>

## A Laravel workflow, kept intact

April UI is designed to feel like part of your application. Write normal Blade components, keep interaction in Alpine or Livewire, and publish only the views you need to own.

<x-code-block-wrapper title="resources/views/dashboard.blade.php" language="blade">
    @verbatim
    <april:card>
        <slot:title>Revenue</slot:title>
        <slot:content>
            <p class="text-2xl font-bold">$45,231.89</p>
            <p class="text-sm text-muted-foreground">+20.1% from last month</p>
        </slot:content>
    </april:card>
    @endverbatim
</x-code-block-wrapper>

<div class="not-prose grid gap-3 sm:grid-cols-3">
    <div class="rounded-lg border bg-background p-4"><p class="font-mono text-xs text-primary">Blade first</p><p class="mt-2 text-sm font-medium">Readable application markup</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Your templates stay close to the HTML and Laravel patterns you already use.</p></div>
    <div class="rounded-lg border bg-background p-4"><p class="font-mono text-xs text-primary">State is yours</p><p class="mt-2 text-sm font-medium">Alpine, Livewire, or both</p><p class="mt-1 text-sm leading-6 text-muted-foreground">April UI gives you the surface without deciding how your application should think.</p></div>
    <div class="rounded-lg border bg-background p-4"><p class="font-mono text-xs text-primary">Own the exception</p><p class="mt-2 text-sm font-medium">Package views with safe overrides</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Use the defaults, then publish a component when your product needs its own behavior or markup.</p></div>
</div>

## Explore the library

The component pages show the rendered result, the Blade source, available options, and the commands for publishing views when you need to customize them.

<div class="not-prose grid gap-3 sm:grid-cols-2">
    <a href="{{url('docs/1.x/components/button')}}" class="rounded-lg border bg-background p-4 no-underline transition-colors hover:bg-muted/30"><p class="font-medium text-foreground">Primitives and forms</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Buttons, inputs, selects, checkboxes, switches, textareas, and validation-friendly form pieces.</p></a>
    <a href="{{url('docs/1.x/components/dialog')}}" class="rounded-lg border bg-background p-4 no-underline transition-colors hover:bg-muted/30"><p class="font-medium text-foreground">Interactive surfaces</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Dialogs, sheets, popovers, dropdowns, command menus, tooltips, and anchored overlays.</p></a>
    <a href="{{url('docs/1.x/components/data-table')}}" class="rounded-lg border bg-background p-4 no-underline transition-colors hover:bg-muted/30"><p class="font-medium text-foreground">Data and content</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Charts, data tables, calendars, date pickers, editors, attachments, and conversational content.</p></a>
    <a href="{{url('docs/1.x/components/sidebar')}}" class="rounded-lg border bg-background p-4 no-underline transition-colors hover:bg-muted/30"><p class="font-medium text-foreground">Application structure</p><p class="mt-1 text-sm leading-6 text-muted-foreground">Sidebars, cards, navigation, tabs, steps, banners, and the pieces that shape a product surface.</p></a>
</div>

<x-callout>
    **Choose your starting point**

    Start with one of the [starter kits](/docs/1.x/starter-kits) for a working Laravel shell with April UI already in place, or follow the [installation guide](/docs/1.x/installation) to add it to an existing application. You can also browse the [blocks](/blocks) for larger compositions.
</x-callout>
