# Changelog

All notable changes to `april-ui` will be documented in this file.

## April UI v1.0.0 - 2026-09-01

The first stable release of April UI. Includes Laravel Blade components with Alpine and Livewire support, package-first view loading, Artisan discovery and diagnostics, local MCP tooling, accessible browser-tested behavior, and complete public component documentation.

## Unreleased

- Keep avatars and vertical carousels within their preview bounds.
- Correct banner positioning and transition classes.
- Normalize server ISO timestamps to the visitor's local calendar day.
- Dispatch initial calendar values to same-element Alpine listeners.
- Repair the multiple and range calendar examples.
- Keep dropdown calendars with week numbers within narrow containers.
- Keep single-month calendar widths stable and reset incomplete ranges when selecting an earlier date.
- Anchor combobox and date-picker panels to their triggers, including a CSS fallback when Alpine Anchor is unavailable.
- Load TipTap through an optional editor asset and give editor controls inline word-processor icons.
- Support opt-in x-teleport for combobox and date-picker panels.
- Keep data-table pagination readable on narrow screens.

## [1.3.1](https://github.com/yungifez/april-ui/compare/v1.3.0...v1.3.1) (2026-09-22)


### Bug Fixes

* drop steps Lucide dependency ([#56](https://github.com/yungifez/april-ui/issues/56)) ([bd96038](https://github.com/yungifez/april-ui/commit/bd960381efaf161e2b55ff0e4e4aee537d34f4bb))

## [1.3.0](https://github.com/yungifez/april-ui/compare/v1.2.6...v1.3.0) (2026-09-07)


### Features

* reach the sidebar state through a receiver ([#52](https://github.com/yungifez/april-ui/issues/52)) ([d116eec](https://github.com/yungifez/april-ui/commit/d116eec2875e756ef08cfce9869048737b3095d9))

## [1.2.6](https://github.com/yungifez/april-ui/compare/v1.2.5...v1.2.6) (2026-09-06)


### Bug Fixes

* announce select and combobox as selects ([a7b852a](https://github.com/yungifez/april-ui/commit/a7b852add66c9e1e0fc1b7b9d8faa8e638e15880))
* stop component titles skipping heading levels ([197da94](https://github.com/yungifez/april-ui/commit/197da9474a806e1c7c66cc4972ca60237f91d6e0))

## [1.2.5](https://github.com/yungifez/april-ui/compare/v1.2.4...v1.2.5) (2026-09-04)


### Bug Fixes

* support nested sidebar triggers ([fe27a28](https://github.com/yungifez/april-ui/commit/fe27a28d8144f65f0d99c6d4d99a2a01f09a8265))

## [1.2.4](https://github.com/yungifez/april-ui/compare/v1.2.3...v1.2.4) (2026-09-04)


### Bug Fixes

* sync Livewire select state ([d000655](https://github.com/yungifez/april-ui/commit/d00065500121052e73a3db72f9a7fe556e4c3d3d))

## [1.2.3](https://github.com/yungifez/april-ui/compare/v1.2.2...v1.2.3) (2026-09-02)


### Bug Fixes

* guard missing input errors ([#47](https://github.com/yungifez/april-ui/issues/47)) ([7df771d](https://github.com/yungifez/april-ui/commit/7df771d5ce102349f5961e6312d730509c2ab58a))

## [1.2.2](https://github.com/yungifez/april-ui/compare/v1.2.1...v1.2.2) (2026-09-02)


### Bug Fixes

* ignore native Livewire models ([07da652](https://github.com/yungifez/april-ui/commit/07da652ae076bdf52ba1184e42660b8c862490bd))

## [1.2.1](https://github.com/yungifez/april-ui/compare/v1.2.0...v1.2.1) (2026-09-02)


### Bug Fixes

* support Codex MCP configuration ([0f89e4f](https://github.com/yungifez/april-ui/commit/0f89e4f46558990ab97659a25d30b8b21c7f378f))
* support Codex MCP configuration ([3c291f5](https://github.com/yungifez/april-ui/commit/3c291f5cbda368d8a6e3cd71f264b198f264164b))

## [1.2.0](https://github.com/yungifez/april-ui/compare/v1.1.0...v1.2.0) (2026-09-01)


### Features

* add MCP config installer ([ea053c8](https://github.com/yungifez/april-ui/commit/ea053c83deb2d95a1cf3dcbe24f1aeb1d29a5656))

## [1.1.0](https://github.com/yungifez/april-ui/compare/v1.0.23...v1.1.0) (2026-09-01)


### Features

* make starter theme default ([7c219b9](https://github.com/yungifez/april-ui/commit/7c219b9b1a113aa8538662957f5bdeada3f0380a))

## [1.0.23](https://github.com/yungifez/april-ui/compare/v1.0.22...v1.0.23) (2026-09-01)


### Bug Fixes

* repair introduction links ([1d059c2](https://github.com/yungifez/april-ui/commit/1d059c24dfd0b75f8d0a2a05ae1b90e90d6d5ba2))
* repair introduction links ([b996dc3](https://github.com/yungifez/april-ui/commit/b996dc377b458904628d0c019ac7ccb055101927))

## [1.0.22](https://github.com/yungifez/april-ui/compare/v1.0.21...v1.0.22) (2026-09-01)


### Bug Fixes

* highlight combobox options on hover ([be4b8c7](https://github.com/yungifez/april-ui/commit/be4b8c71b37ab2c44e10c097cbf9214f8cfce17e))
* highlight combobox options on hover ([852a8be](https://github.com/yungifez/april-ui/commit/852a8bedd9e732990a60325116140a65862b9e97))

## [1.0.21](https://github.com/yungifez/april-ui/compare/v1.0.20...v1.0.21) (2026-09-01)


### Bug Fixes

* harden GitHub Actions supply-chain security ([a515da7](https://github.com/yungifez/april-ui/commit/a515da7670a46426e2b25d274a0a5fea5e3bc031))

## [1.0.20](https://github.com/yungifez/april-ui/compare/v1.0.19...v1.0.20) (2026-09-01)


### Bug Fixes

* move calendar layout into Tailwind ([6f1eda1](https://github.com/yungifez/april-ui/commit/6f1eda1b25cdd0280f3b5874f4da8a7c1707bb02))

## [1.0.19](https://github.com/yungifez/april-ui/compare/v1.0.18...v1.0.19) (2026-09-01)


### Bug Fixes

* align release tags with package tags ([05092f1](https://github.com/yungifez/april-ui/commit/05092f15ac55ce4052ef4a5e96a5bc6b54baa38d))

## v1.0.0 - 2026-08-31

The first stable release of April UI.

- Laravel Blade components with Alpine and Livewire support.
- Package-first view loading with explicit vendor publishing and update checks.
- Artisan component discovery, diagnostics, and local MCP tooling.
- Responsive, accessible component behavior covered by PHP and browser tests.
- Documentation and examples for the complete public component set.

## v0.0.1 prima emissio - 2024-07-24

I thought making a UI library was easy, I was wrong
