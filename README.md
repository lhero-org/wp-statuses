# WP Statuses

An improved custom post statuses API for WordPress, including support for custom statuses in the block editor.

This is a maintained fork of [imath/wp-statuses](https://github.com/imath/wp-statuses), which its author archived in November 2024 with an invitation to take it over or fork it. Many thanks to imath and everyone who contributed to it. It is maintained by [LocalHero](https://lhero.org), which uses it across its plugins.

## Installation

As a Composer dependency of a plugin:

```bash
composer require lhero-org/wp-statuses
```

Then require the loader from your plugin's main file, when the file is parsed (not from a hook):

```php
require_once __DIR__ . '/vendor/lhero-org/wp-statuses/loader.php';
```

It can also be installed as a plugin on its own, by placing this directory in `wp-content/plugins/`.

## Bundling it in several plugins

Any number of plugins can bundle their own copy. Each copy's `loader.php` registers its version, and only the newest copy is loaded, on `plugins_loaded` at priority 1, before the library boots.

- Require `loader.php`, not `wp-statuses.php`.
- Do not load it through Composer's `autoload.files`. Composer includes a package's files only once per request, so the other bundled copies would never register their versions.
- Copies older than 2.2.0 have no loader. One included directly before `plugins_loaded` wins, and the loader does nothing.

## Changes from the original

The original's last release was 2.1.9.

### 2.2.0

- Fix: a locale switch during `init` (GatherPress does one while building its venue rewrite slug) no longer freezes the post types of the built-in statuses. Post types registered after the switch kept only their custom statuses in the block editor, losing Draft, Pending and Publish.
- Fix: unregistering `publish` for a post type with `wp_statuses_unregister_status_for_post_type()` no longer also unregisters `draft` and `pending`.
- New: `loader.php`, so several plugins can bundle the library and the newest copy is loaded.
- `wp_statuses()` is guarded with `function_exists()`, and the library boots straight away when it is loaded during `plugins_loaded`.
- Composer package renamed to `lhero-org/wp-statuses`, type `library`.

## Licence

GNU General Public License v2, as the original.
