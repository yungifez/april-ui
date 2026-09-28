<?php

describe('sidebar layout', function () {
    it('holds the sidebar state', function () {
        expect(renderComponent('sidebar-layout'))->toContain('x-data="sidebar(true)"');
    });

    it('starts collapsed on request', function () {
        expect(renderComponent('sidebar-layout', ':default-open="false"'))
            ->toContain('x-data="sidebar(false)"');
    });

    it('binds the keyboard shortcut through the root binding', function () {
        expect(renderComponent('sidebar-layout'))
            ->toContain('x-bind="sidebar.root"')
            ->toContain('x-modelable="sidebar.open"');
    });

    it('fills the viewport height', function () {
        expect(classesOf(renderComponent('sidebar-layout')))
            ->toContain('min-h-svh')
            ->toContain('flex');
    });

    it('renders its slot', function () {
        expect(renderComponent('sidebar-layout', '', 'page'))->toContain('page');
    });

    it('adds no style attribute when it uses the default widths', function () {
        expect(renderComponent('sidebar-layout'))->not->toContain('style=');
    });

    it('takes a custom sidebar width', function () {
        expect(renderComponent('sidebar-layout', 'width="20rem"'))
            ->toContain('--sidebar-width: 20rem');
    });

    it('takes a custom collapsed width', function () {
        expect(renderComponent('sidebar-layout', 'width-icon="4rem"'))
            ->toContain('--sidebar-width-icon: 4rem');
    });
});

describe('sidebar', function () {
    it('reports its side', function () {
        expect(renderComponent('sidebar'))->toContain('data-side="left"');
    });

    it('takes a right side', function () {
        expect(renderComponent('sidebar', 'side="right"'))->toContain('data-side="right"');
    });

    it('reports its variant', function (string $variant) {
        expect(renderComponent('sidebar', "variant=\"{$variant}\""))
            ->toContain("data-variant=\"{$variant}\"");
    })->with(['sidebar', 'floating', 'inset']);

    it('reads its state from the layout', function () {
        expect(renderComponent('sidebar'))->toContain(':data-state="sidebar.state"');
    });

    it('paints expanded before alpine starts', function () {
        expect(renderComponent('sidebar'))
            ->toContain('data-state="expanded"')
            ->toContain('data-collapsible=""');
    });

    it('paints collapsed before alpine starts when it starts closed', function () {
        expect(renderComponent('sidebar', 'collapsible="icon" :default-open="false"'))
            ->toContain('data-state="collapsed"')
            ->toContain('data-collapsible="icon"');
    });

    it('only marks itself collapsible while it is collapsed', function () {
        expect(renderComponent('sidebar', 'collapsible="icon"'))
            ->toContain('open ? \'\' : \'icon\'');
    });

    it('slides off canvas by default', function () {
        expect(renderComponent('sidebar'))
            ->toContain('group-data-[collapsible=offcanvas]:w-0');
    });

    it('shrinks to the icon width when collapsible by icon', function () {
        expect(renderComponent('sidebar', 'collapsible="icon"'))
            ->toContain('group-data-[collapsible=icon]:w-[var(--sidebar-width-icon)]');
    });

    it('leaves room for the padding of a floating sidebar', function () {
        expect(renderComponent('sidebar', 'variant="floating" collapsible="icon"'))
            ->toContain('group-data-[collapsible=icon]:w-[calc(var(--sidebar-width-icon)+1rem)]');
    });

    it('rounds a floating sidebar', function () {
        expect(renderComponent('sidebar', 'variant="floating"'))->toContain('rounded-lg');
    });

    it('pushes the page content aside', function () {
        expect(renderComponent('sidebar'))
            ->toContain('w-[var(--sidebar-width)]')
            ->toContain('shrink-0');
    });

    it('fills its layout container and keeps the panel bounded', function () {
        $html = renderComponent('sidebar');

        expect($html)
            ->toContain('relative')
            ->toContain('self-stretch')
            ->toContain('h-full w-[var(--sidebar-width)]')
            ->toContain('absolute inset-y-0')
            ->toContain('overflow-hidden')
            ->toContain('min-h-0 w-full flex-col');
    });

    it('hides the desktop sidebar on a small screen', function () {
        expect(renderComponent('sidebar'))->toContain('hidden text-sidebar-foreground md:block');
    });

    it('renders a mobile panel', function () {
        expect(renderComponent('sidebar'))
            ->toContain('data-mobile="true"')
            ->toContain('w-[var(--sidebar-width-mobile)]');
    });

    it('closes the mobile panel when the overlay is clicked', function () {
        expect(renderComponent('sidebar'))->toContain('x-on:click="sidebar.close()"');
    });

    it('closes the mobile panel on escape', function () {
        expect(renderComponent('sidebar'))->toContain('x-on:keydown.esc.window="sidebar.close()"');
    });

    it('renders its slot on desktop and on mobile', function () {
        expect(substr_count(renderComponent('sidebar', '', 'Navigation'), 'Navigation'))->toBe(2);
    });

    it('renders a plain panel when it cannot collapse', function () {
        $html = renderComponent('sidebar', 'collapsible="none"', 'Navigation');

        expect($html)->toContain('Navigation')
            ->not->toContain('data-mobile')
            ->and(substr_count($html, 'Navigation'))->toBe(1);
    });

    it('persists its state on request', function () {
        expect(renderComponent('sidebar', 'x-persist="app-sidebar"'))
            ->toContain('x-persist="app-sidebar-desktop"')
            ->not->toContain('x-persist="app-sidebar-mobile"');
    });

    it('does not persist by default', function () {
        expect(renderComponent('sidebar'))->not->toContain('x-persist');
    });

    it('persists a non-collapsible sidebar directly', function () {
        $html = renderComponent('sidebar', 'collapsible="none" x-persist="app-sidebar"');

        expect($html)->toContain('x-persist="app-sidebar"')
            ->not->toContain('x-persist="app-sidebar-desktop"')
            ->not->toContain('x-persist="app-sidebar-mobile"');
    });

    it('lets a user class win over the panel default', function () {
        expect(renderComponent('sidebar', 'class="bg-card"'))
            ->toContain('bg-card')
            ->not->toContain('flex h-full w-full flex-col bg-sidebar"');
    });
});

describe('sidebar trigger', function () {
    it('toggles the sidebar', function () {
        expect(renderComponent('sidebar-trigger'))->toContain('x-on:click="sidebar.toggle()"');
    });

    it('is labelled for screen readers', function () {
        expect(renderComponent('sidebar-trigger'))
            ->toContain('<span class="sr-only">Toggle Sidebar</span>')
            ->toContain('title="Toggle Sidebar"');
    });

    it('draws a default icon', function () {
        expect(renderComponent('sidebar-trigger'))->toContain('<svg');
    });

    it('takes a custom icon', function () {
        $html = render('<april:sidebar-trigger>Menu</april:sidebar-trigger>');

        expect($html)->toContain('Menu')->not->toContain('<svg');
    });

    it('is a small square button', function () {
        expect(classesOf(renderComponent('sidebar-trigger')))
            ->toContain('size-7')
            ->not->toContain('h-10')
            ->not->toContain('w-10');
    });

    it('does not submit a surrounding form', function () {
        expect(renderComponent('sidebar-trigger'))->toContain('type="button"');
    });
});

describe('sidebar rail', function () {
    it('toggles the sidebar', function () {
        expect(renderComponent('sidebar-rail'))->toContain('x-on:click="sidebar.toggle()"');
    });

    it('sits on the outer edge of the side it is on', function () {
        expect(classesOf(renderComponent('sidebar-rail')))
            ->toContain('group-data-[side=left]:-right-4')
            ->toContain('group-data-[side=right]:left-0');
    });

    it('stays out of the tab order', function () {
        expect(renderComponent('sidebar-rail'))->toContain('tabindex="-1"');
    });
});

describe('sidebar inset', function () {
    it('renders the page content in a main element', function () {
        expect(renderComponent('sidebar-inset', '', 'Page'))
            ->toContain('<main')
            ->toContain('Page');
    });

    it('reacts to an inset sidebar next to it', function () {
        expect(classesOf(renderComponent('sidebar-inset')))
            ->toContain('md:peer-data-[variant=inset]:rounded-xl');
    });

    it('fills the remaining width', function () {
        expect(classesOf(renderComponent('sidebar-inset')))
            ->toContain('flex-1')
            ->toContain('min-w-0');
    });
});

describe('sidebar sections', function () {
    it('marks the section for styling and testing', function (string $name, string $marker) {
        expect(renderComponent($name))->toContain("data-sidebar=\"{$marker}\"");
    })->with([
        ['sidebar-group', 'group'],
        ['sidebar-group-label', 'group-label'],
        ['sidebar-group-content', 'group-content'],
        ['sidebar-menu', 'menu'],
        ['sidebar-menu-item', 'menu-item'],
        ['sidebar-separator', 'separator'],
    ]);

    it('renders its slot', function (string $name) {
        expect(renderComponent($name, '', 'content'))->toContain('content');
    })->with([
        'sidebar-group',
        'sidebar-group-label',
        'sidebar-group-content',
        'sidebar-menu',
        'sidebar-menu-item',
    ]);

    it('renders header, content, and footer named slots on desktop and mobile', function () {
        $html = render(<<<'BLADE'
        <april:sidebar>
            <slot:header>Header</slot:header>
            <slot:content>Content</slot:content>
            <slot:footer>Footer</slot:footer>
        </april:sidebar>
        BLADE);

        expect(substr_count($html, 'Header'))->toBe(2)
            ->and(substr_count($html, 'Content'))->toBe(2)
            ->and(substr_count($html, 'Footer'))->toBe(2)
            ->and($html)->toContain('overflow-auto')
            ->toContain('data-slot="sidebar-header"')
            ->toContain('data-slot="sidebar-content"')
            ->toContain('data-slot="sidebar-footer"');
    });

    it('leaves the padding of the content to its groups and keeps it scrolling when collapsed', function () {
        $html = render(<<<'BLADE'
        <april:sidebar>
            <slot:content>Content</slot:content>
        </april:sidebar>
        BLADE);

        preg_match('/data-slot="sidebar-content"[^>]*class="([^"]*)"/', $html, $matches);
        $classes = explode(' ', $matches[1] ?? '');

        expect($classes)
            ->toContain('overflow-auto')
            ->toContain('group-data-[collapsible=icon]:[scrollbar-width:none]')
            ->not->toContain('p-2')
            ->not->toContain('group-data-[collapsible=icon]:overflow-hidden')
            ->and(classesOf(renderComponent('sidebar-group')))->toContain('p-2');
    });

    it('hides the group label when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-group-label')))
            ->toContain('group-data-[collapsible=icon]:opacity-0');
    });

    it('renders the menu as a list', function () {
        expect(renderComponent('sidebar-menu'))->toContain('<ul');
    });

    it('renders a menu item as a list item', function () {
        expect(renderComponent('sidebar-menu-item'))->toContain('<li');
    });

    it('hides the separator from the accessibility tree', function () {
        expect(renderComponent('sidebar-separator'))->toContain('role="none"');
    });

});

describe('sidebar menu button', function () {
    it('renders a button by default', function () {
        expect(renderComponent('sidebar-menu-button', '', 'Inbox'))
            ->toContain('<button')
            ->toContain('type="button"')
            ->toContain('Inbox');
    });

    it('is not active by default', function () {
        expect(renderComponent('sidebar-menu-button'))->toContain('data-active="false"');
    });

    it('marks itself active', function () {
        expect(renderComponent('sidebar-menu-button', ':active="true"'))
            ->toContain('data-active="true"');
    });

    it('styles the active state', function () {
        expect(classesOf(renderComponent('sidebar-menu-button')))
            ->toContain('data-[active=true]:bg-sidebar-accent');
    });

    it('applies the size', function (string $size, string $expected) {
        expect(classesOf(renderComponent('sidebar-menu-button', "size=\"{$size}\"")))
            ->toContain($expected);
    })->with([
        ['sm', 'h-7'],
        ['lg', 'h-12'],
    ]);

    it('reports its size for styling', function () {
        expect(renderComponent('sidebar-menu-button', 'size="lg"'))->toContain('data-size="lg"');
    });

    it('uses the default size when none is given', function () {
        expect(classesOf(renderComponent('sidebar-menu-button')))->toContain('h-8');
    });

    it('opens the sidebar through the sidebar receiver', function () {
        expect(renderComponent('sidebar-menu-button'))->toContain('x-on:click="sidebar.show()"');
    });

    it('can leave sidebar expansion to a parent interactive component', function () {
        expect(renderComponent('sidebar-menu-button', ':expand-sidebar="false"'))
            ->not->toContain('x-on:click');
    });

    it('carries no tooltip by default', function () {
        expect(renderComponent('sidebar-menu-button'))->not->toContain('data-slot="tooltip"');
    });

    it('labels itself with a tooltip while the sidebar shows icons', function () {
        $html = renderComponent('sidebar-menu-button', 'tooltip="Boarding"');

        expect($html)
            ->toContain('data-slot="tooltip"')
            ->toContain('Boarding')
            ->toContain("x-effect=\"tooltipDisabled = sidebar.state !== 'collapsed' || sidebar.isMobile\"");
    });

    it('applies the outline variant', function () {
        expect(renderComponent('sidebar-menu-button', 'variant="outline"'))->toContain('bg-background');
    });

    it('shrinks to a square when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-menu-button')))
            ->toContain('group-data-[collapsible=icon]:size-8!')
            ->toContain('group-data-[collapsible=icon]:justify-center')
            ->toContain('group-data-[collapsible=icon]:[&>span]:hidden');
    });

    it('lets a user class win over the default', function () {
        expect(classesOf(renderComponent('sidebar-menu-button', 'class="h-16"')))
            ->toContain('h-16')
            ->not->toContain('h-8');
    });
});

describe('sidebar menu button link', function () {
    it('renders an anchor', function () {
        expect(renderComponent('sidebar-menu-button-link', 'href="/inbox"', 'Inbox'))
            ->toContain('<a')
            ->toContain('href="/inbox"')
            ->toContain('Inbox')
            ->not->toContain('<button');
    });

    it('carries the same slot marker as the button form', function () {
        expect(renderComponent('sidebar-menu-button-link', 'href="/x"'))
            ->toContain('data-sidebar="menu-button"')
            ->toContain('data-slot="sidebar-menu-button"');
    });

    it('marks itself active', function () {
        expect(renderComponent('sidebar-menu-button-link', 'href="/x" :active="true"'))
            ->toContain('data-active="true"');
    });

    it('applies the size', function () {
        expect(classesOf(renderComponent('sidebar-menu-button-link', 'href="/x" size="lg"')))
            ->toContain('h-12');
    });

    it('does not inherit the button link underline reset as a variant', function () {
        expect(classesOf(renderComponent('sidebar-menu-button-link', 'href="/x"')))
            ->not->toContain('bg-primary');
    });

    it('hides its label when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-menu-button-link', 'href="/x"')))
            ->toContain('group-data-[collapsible=icon]:justify-center')
            ->toContain('group-data-[collapsible=icon]:[&>span]:hidden');
    });
});

describe('sidebar menu action', function () {
    it('sits in the corner of its menu item', function () {
        expect(classesOf(renderComponent('sidebar-menu-action')))
            ->toContain('absolute')
            ->toContain('top-1.5')
            ->toContain('right-1');
    });

    it('follows the size of the button beside it', function () {
        expect(classesOf(renderComponent('sidebar-menu-action')))
            ->toContain('peer-data-[size=lg]/menu-button:top-2.5');
    });

    it('is always visible by default', function () {
        expect(classesOf(renderComponent('sidebar-menu-action')))->not->toContain('md:opacity-0');
    });

    it('can appear only on hover', function () {
        expect(classesOf(renderComponent('sidebar-menu-action', ':show-on-hover="true"')))
            ->toContain('md:opacity-0')
            ->toContain('group-hover/menu-item:opacity-100');
    });

    it('hides when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-menu-action')))
            ->toContain('group-data-[collapsible=icon]:hidden');
    });

    it('makes room for itself in the button beside it', function () {
        expect(classesOf(renderComponent('sidebar-menu-button')))
            ->toContain('group-has-data-[sidebar=menu-action]/menu-item:pr-8');
    });
});

describe('sidebar menu badge', function () {
    it('sits on the right of its menu item', function () {
        expect(classesOf(renderComponent('sidebar-menu-badge')))
            ->toContain('absolute')
            ->toContain('right-1');
    });

    it('does not take pointer events', function () {
        expect(classesOf(renderComponent('sidebar-menu-badge')))->toContain('pointer-events-none');
    });

    it('lines its digits up', function () {
        expect(classesOf(renderComponent('sidebar-menu-badge')))->toContain('tabular-nums');
    });

    it('renders its slot', function () {
        expect(renderComponent('sidebar-menu-badge', '', '24'))->toContain('24');
    });
});

describe('sidebar menu skeleton', function () {
    it('builds its rows from the skeleton component', function () {
        expect(renderComponent('sidebar-menu-skeleton'))
            ->toContain('animate-pulse')
            ->toContain('data-sidebar="menu-skeleton-text"');
    });

    it('leaves out the icon by default', function () {
        expect(renderComponent('sidebar-menu-skeleton'))->not->toContain('menu-skeleton-icon');
    });

    it('adds an icon on request', function () {
        expect(renderComponent('sidebar-menu-skeleton', ':show-icon="true"'))
            ->toContain('data-sidebar="menu-skeleton-icon"');
    });

    it('gives the row a width so a list does not look like a grid', function () {
        expect(renderComponent('sidebar-menu-skeleton'))->toMatch('/max-width: [5-9][0-9]%/');
    });

    it('matches the height of a menu button', function () {
        expect(classesOf(renderComponent('sidebar-menu-skeleton')))->toContain('h-8');
    });
});

describe('sidebar menu sub', function () {
    it('renders the submenu as a list with a rule down the side', function () {
        expect(renderComponent('sidebar-menu-sub'))
            ->toContain('<ul')
            ->and(classesOf(renderComponent('sidebar-menu-sub')))
            ->toContain('border-l')
            ->toContain('border-sidebar-border');
    });

    it('hides the submenu when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-menu-sub')))
            ->toContain('group-data-[collapsible=icon]:hidden');
    });

    it('renders a submenu item as a list item', function () {
        expect(renderComponent('sidebar-menu-sub-item', '', 'Child'))
            ->toContain('<li')
            ->toContain('Child');
    });

    it('renders a submenu button as an anchor', function () {
        expect(renderComponent('sidebar-menu-sub-button', 'href="/child"', 'Child'))
            ->toContain('<a')
            ->toContain('href="/child"');
    });

    it('uses the medium submenu size by default', function () {
        $html = renderComponent('sidebar-menu-sub-button');

        expect($html)->toContain('data-size="md"')
            ->and(classesOf($html))->toContain('text-sm');
    });

    it('takes the small submenu size', function () {
        $html = renderComponent('sidebar-menu-sub-button', 'size="sm"');

        expect($html)->toContain('data-size="sm"')
            ->and(classesOf($html))->toContain('text-xs')->not->toContain('text-sm');
    });

    it('marks a submenu button active', function () {
        expect(renderComponent('sidebar-menu-sub-button', ':active="true"'))
            ->toContain('data-active="true"');
    });
});

describe('sidebar group action', function () {
    it('sits in the corner of its group', function () {
        expect(classesOf(renderComponent('sidebar-group-action')))
            ->toContain('absolute')
            ->toContain('top-3.5')
            ->toContain('right-3');
    });

    it('hides when the sidebar collapses to icons', function () {
        expect(classesOf(renderComponent('sidebar-group-action')))
            ->toContain('group-data-[collapsible=icon]:hidden');
    });

    it('widens its hit area on a touch screen', function () {
        expect(classesOf(renderComponent('sidebar-group-action')))->toContain('after:-inset-2');
    });
});

describe('sidebar input', function () {
    it('builds on the input component', function () {
        expect(renderComponent('sidebar-input'))->toContain('<input');
    });

    it('is shorter than a normal input', function () {
        expect(classesOf(renderComponent('sidebar-input')))
            ->toContain('h-8')
            ->not->toContain('h-10');
    });

    it('drops the shadow of a normal input', function () {
        expect(classesOf(renderComponent('sidebar-input')))->toContain('shadow-none');
    });

    it('forwards a name attribute', function () {
        expect(renderComponent('sidebar-input', 'name="search"'))->toContain('name="search"');
    });
});

describe('sidebar separator', function () {
    it('builds on the separator component', function () {
        expect(renderComponent('sidebar-separator'))->toContain('role="none"');
    });

    it('uses the sidebar border colour', function () {
        expect(classesOf(renderComponent('sidebar-separator')))->toContain('bg-sidebar-border');
    });

    it('drops the vertical margin that a plain separator has', function () {
        expect(classesOf(renderComponent('sidebar-separator')))
            ->toContain('my-0')
            ->not->toContain('my-4');
    });
});

describe('shadcn parity', function () {
    // shadcn puts a data-slot on every part. Keep them, so the same CSS and the
    // same tests work against either library.
    it('marks every part with a data-slot', function (string $name, string $slot) {
        expect(renderComponent($name))->toContain("data-slot=\"{$slot}\"");
    })->with([
        ['sidebar-layout', 'sidebar-wrapper'],
        ['sidebar', 'sidebar'],
        ['sidebar-trigger', 'sidebar-trigger'],
        ['sidebar-rail', 'sidebar-rail'],
        ['sidebar-inset', 'sidebar-inset'],
        ['sidebar-input', 'sidebar-input'],
        ['sidebar-separator', 'sidebar-separator'],
        ['sidebar-group', 'sidebar-group'],
        ['sidebar-group-label', 'sidebar-group-label'],
        ['sidebar-group-action', 'sidebar-group-action'],
        ['sidebar-group-content', 'sidebar-group-content'],
        ['sidebar-menu', 'sidebar-menu'],
        ['sidebar-menu-item', 'sidebar-menu-item'],
        ['sidebar-menu-button', 'sidebar-menu-button'],
        ['sidebar-menu-action', 'sidebar-menu-action'],
        ['sidebar-menu-badge', 'sidebar-menu-badge'],
        ['sidebar-menu-skeleton', 'sidebar-menu-skeleton'],
        ['sidebar-menu-sub', 'sidebar-menu-sub'],
        ['sidebar-menu-sub-item', 'sidebar-menu-sub-item'],
        ['sidebar-menu-sub-button', 'sidebar-menu-sub-button'],
    ]);

    it('covers every part of the shadcn sidebar', function (string $name) {
        expect(componentNames())->toContain($name);
    })->with([
        'sidebar',
        'sidebar-group',
        'sidebar-group-action',
        'sidebar-group-content',
        'sidebar-group-label',
        'sidebar-input',
        'sidebar-inset',
        'sidebar-menu',
        'sidebar-menu-action',
        'sidebar-menu-badge',
        'sidebar-menu-button',
        'sidebar-menu-item',
        'sidebar-menu-skeleton',
        'sidebar-menu-sub',
        'sidebar-menu-sub-button',
        'sidebar-menu-sub-item',
        'sidebar-layout',
        'sidebar-rail',
        'sidebar-separator',
        'sidebar-trigger',
    ]);

    it('turns the gap around for a sidebar on the right', function () {
        expect(renderComponent('sidebar'))->toContain('group-data-[side=right]:rotate-180');
    });

    it('draws the border on the inner edge', function () {
        expect(renderComponent('sidebar'))
            ->toContain('group-data-[side=left]:border-r')
            ->toContain('group-data-[side=right]:border-l');
    });

    it('styles a floating sidebar through a data attribute', function () {
        expect(renderComponent('sidebar'))
            ->toContain('group-data-[variant=floating]:rounded-lg')
            ->toContain('group-data-[variant=floating]:border-sidebar-border');
    });

    it('wraps the sidebar colour token in hsl, as this library stores channels', function () {
        expect(renderComponent('sidebar-menu-button', 'variant="outline"'))
            ->toContain('shadow-[0_0_0_1px_hsl(var(--sidebar-border))]');
    });
});

it('renders a whole sidebar layout', function () {
    $html = render(<<<'BLADE'
    <april:sidebar-layout>
        <april:sidebar collapsible="icon">
            <slot:header>
                <april:sidebar-input name="search" placeholder="Search" />
            </slot:header>
            <slot:content>
                <april:sidebar-group>
                    <april:sidebar-group-label>Platform</april:sidebar-group-label>
                    <april:sidebar-group-action>+</april:sidebar-group-action>
                    <april:sidebar-group-content>
                        <april:sidebar-menu>
                            <april:sidebar-menu-item>
                                <april:sidebar-menu-button-link href="/inbox" :active="true">
                                    Inbox
                                </april:sidebar-menu-button-link>
                                <april:sidebar-menu-badge>24</april:sidebar-menu-badge>
                                <april:sidebar-menu-action :show-on-hover="true">...</april:sidebar-menu-action>
                                <april:sidebar-menu-sub>
                                    <april:sidebar-menu-sub-item>
                                        <april:sidebar-menu-sub-button href="/inbox/all">All</april:sidebar-menu-sub-button>
                                    </april:sidebar-menu-sub-item>
                                </april:sidebar-menu-sub>
                            </april:sidebar-menu-item>
                            <april:sidebar-menu-item>
                                <april:sidebar-menu-skeleton :show-icon="true" />
                            </april:sidebar-menu-item>
                        </april:sidebar-menu>
                    </april:sidebar-group-content>
                </april:sidebar-group>
                <april:sidebar-separator />
            </slot:content>
            <slot:footer>Profile</slot:footer>
            <april:sidebar-rail />
        </april:sidebar>
        <april:sidebar-inset>
            <april:sidebar-trigger />
            <p>Page</p>
        </april:sidebar-inset>
    </april:sidebar-layout>
    BLADE);

    expect($html)
        ->toContain('x-data="sidebar(true)"')
        ->toContain('data-slot="sidebar-wrapper"')
        ->toContain('data-slot="sidebar-header"')
        ->toContain('data-slot="sidebar-menu-button"')
        ->toContain('data-slot="sidebar-menu-sub-button"')
        ->toContain('data-slot="sidebar-menu-skeleton"')
        ->toContain('href="/inbox"')
        ->toContain('href="/inbox/all"')
        ->toContain('<main')
        ->toContain('Page')
        ->not->toContain('@endComponentClass')
        ->not->toContain('<?php');
});
