<april:sidebar-layout>
    <april:sidebar>
        <slot:header>...</slot:header>
        <slot:content>
            <april:sidebar-group>
                <april:sidebar-group-label>Platform</april:sidebar-group-label>
                <april:sidebar-group-content>
                    <april:sidebar-menu>
                        <april:sidebar-menu-item>
                            <april:sidebar-menu-button-link href="/inbox">Inbox</april:sidebar-menu-button-link>
                        </april:sidebar-menu-item>
                    </april:sidebar-menu>
                </april:sidebar-group-content>
            </april:sidebar-group>
        </slot:content>
        <slot:footer>...</slot:footer>
        <april:sidebar-rail />
    </april:sidebar>

    <april:sidebar-inset>
        <april:sidebar-trigger />
        {{ $slot }}
    </april:sidebar-inset>
</april:sidebar-layout>
