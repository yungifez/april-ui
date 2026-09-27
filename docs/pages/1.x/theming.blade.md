---
view: components.docs-layout
title: Theming
description: Using CSS Variables for theming.
---
## How Theming works
For now, theming works only using CSS variables, as shown below
<x-code-block-wrapper language="html">
<div class="bg-background text-foreground"></div>
</x-code-block-wrapper>
---
### Convention

We use a simple `background` and `foreground` convention for colors. The `background` variable is used for the background color of the component and the `foreground` variable is used for the text color.

<x-callout>
The `background` suffix is omitted when the variable is used for the background color of the component.
</x-callout>

April UI ships with a moss and warm clay palette by default. The default light theme uses soft green surfaces, while the dark theme uses layered forest tones. You can replace any semantic token in your application's CSS after importing April UI.

Given the following CSS variables:

<x-code-block-wrapper title="app.css" language="css">
--primary: 222.2 47.4% 11.2%;
--primary-foreground: 210 40% 98%;
</x-code-block-wrapper>

The `background` color of the following component will be `hsl(var(--primary))` and the `foreground` color will be `hsl(var(--primary-foreground))`.

<x-code-block-wrapper title="" language="html">
<div class="bg-primary text-primary-foreground">Hello</div>
</x-code-block-wrapper>

<x-callout>

**CSS variables must be defined without color space function**. See the [Tailwind CSS documentation](https://tailwindcss.com/docs/customizing-colors#using-css-variables) for more information.

</x-callout>

### List of variables

Here's the list of variables available for customization:

<Steps>

<x-code-block-wrapper title="Default background color of <body/>...etc" language="css">
--background: 105 20% 93%;
--foreground: 145 18% 13%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Muted backgrounds such as in switch" language="css">
--muted: 100 15% 87%;
--muted-foreground: 145 8% 36%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Background color for the card component" language="css">
--card: 95 14% 97%;
--card-foreground: 145 18% 13%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Background color for popovers such as dropdown menu and popover" language="css">
--popover: 95 14% 98%;
--popover-foreground: 145 18% 13%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Default border color" language="css">
--border: 100 10% 79%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Border color for inputs such as input, select, textarea" language="css">
--input: 100 10% 79%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Primary colors for button" language="css">
--primary: 28 38% 74%;
--primary-foreground: 28 38% 16%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Secondary colors for button" language="css">
--secondary: 100 15% 87%;
--secondary-foreground: 145 18% 13%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Used for accents such as hover effects on dropdown menu items, select item...etc" language="css">
--accent: 30 20% 86%;
--accent-foreground: 145 18% 13%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Used for destructive actions such as destructive buttons" language="css">
--destructive: 0 100% 50%;
--destructive-foreground: 210 40% 98%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Used for focus ring-3" language="css">
--ring: 28 38% 74%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Border radius for card, input and buttons" language="css">
--radius: 0.5rem;
</x-code-block-wrapper>

<x-code-block-wrapper title="Colors for the sidebar component" language="css">
--sidebar-background: 100 16% 89%;
--sidebar-foreground: 145 18% 13%;
--sidebar-primary: 28 38% 74%;
--sidebar-primary-foreground: 35 25% 12%;
--sidebar-accent: 100 15% 84%;
--sidebar-accent-foreground: 145 18% 13%;
--sidebar-border: 100 10% 79%;
--sidebar-ring: 28 38% 74%;
</x-code-block-wrapper>

<x-code-block-wrapper title="Widths for the sidebar component" language="css">
--sidebar-width: 16rem;
--sidebar-width-icon: 3rem;
--sidebar-width-mobile: 18rem;
</x-code-block-wrapper>

</Steps>

### Adding new colors

To add new colors, define the channels and then map them in your `@theme` block. Both live in your CSS file.

<x-code-block-wrapper title="app.css" language="css" file="snippets/theming-new-colors.css" />

<x-callout>
Tailwind v4 has no `tailwind.config.js`. If you are following an older guide that tells you to add colors there, use
the `@theme` block instead.
</x-callout>


You can now use the `warning` utility class in your components.

<x-code-block-wrapper title="app.blade.php" language="tsx /bg-warning/ /text-warning-foreground/
">
<div class="bg-warning text-warning-foreground"></div>
</x-code-block-wrapper>

### Other color formats

ShadCN recommends using [HSL colors](https://www.smashingmagazine.com/2021/07/hsl-colors-css/) for theming but you can also use other color formats if you prefer.

See the [Tailwind CSS documentation](https://tailwindcss.com/docs/customizing-colors#using-css-variables) for more information on using `rgb`, `rgba` or `hsl` colors.

## Component overrides

Use semantic tokens first. If a component needs a product-specific change, publish only that component and edit the
copy in `resources/views/vendor/april/components`:

<x-code-block-wrapper language="bash">
    php artisan april:publish button
</x-code-block-wrapper>

April components also expose `data-slot` attributes on their internal elements. Use these selectors for small visual
adjustments without replacing a component:

<x-code-block-wrapper title="app.css" language="css">
[data-slot="button"] {
  letter-spacing: 0.01em;
}
</x-code-block-wrapper>

The package merges component classes with passed classes. The `tailwind_merge` configuration controls this behavior
when your application uses a custom Tailwind prefix or class groups.
