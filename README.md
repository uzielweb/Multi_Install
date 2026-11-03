# Multi Install for Joomla 6

Joomla 6 component that allows you to install multiple extension packages (components, modules, plugins, templates) simultaneously from a single ZIP archive or directly from a remote package URL.

## Authorship & Maintenance

- **Maintainer & Developer:** Uziel Almeida Oliveira ([Ponto Mega](https://pontomega.com.br))
- **Original Author:** Chris Taylor (legacy v1.0)
- **Repository:** [https://github.com/uzielweb/Multi_Install](https://github.com/uzielweb/Multi_Install)

## Key Features in Version 2.0.0

- Fully compatible with **Joomla 6** and **PHP 8.3 / 8.4**.
- Built-in support for the native **Joomla Update Server** (`update.xml`) integrated with the Joomla Administrator Control Panel.
- Modern native architecture:
  - Dependency Injection with **Service Provider** (`services/provider.php`).
  - PSR-4 namespaces (`Uzielweb\Component\Multiinstall`).
  - Resilient extraction using `Joomla\Archive` (supports both native `ZipArchive` and pure-PHP fallback).
  - Modern Web Asset Manager (WAM) via `joomla.asset.json`.
  - Clean MVC structure with standard CSRF protection tokens and ACL permissions (`access.xml` and `config.xml`).
- Full bilingual internationalization (i18n): **English (en-GB)** and **Portuguese (pt-BR)**.
- Modern interface tailored for Joomla Administrator templates (Atum / Bootstrap 5).

## Installation

1. Download the release package `com_multiinstall-v2.0.0.zip` from the [Releases](https://github.com/uzielweb/Multi_Install/releases) page.
2. Log in to your Joomla Administrator Panel: **System > Install > Extensions**.
3. Upload the ZIP package.
4. Open the extension from the menu: **Components > Multi Install**.

## How to Use

1. Compress one or more Joomla extension ZIP packages into a single parent ZIP file.
2. In the Multi Install interface, upload that parent ZIP archive (or paste a remote URL).
3. Click **Install**. All nested extensions will be unpacked, verified, and installed in sequential batch automatically.

## License

GNU General Public License version 2 or later; see LICENSE.txt.