# PressIQ Widgets (formerly AC Starter Toolkit)

**A professional, production-ready WordPress plugin delivering essential, high-performance UI widgets and layout modules.**

As part of the GlowOS & Nexus AI ecosystem's broader product consolidation, **PressIQ Widgets** is being upgraded from an internal starter toolkit into a fully featured, public-facing WordPress plugin. Our goal is to publish this directly to the WordPress Plugin Repository to provide a robust, reliable suite of tools for modern site builders.

## 🚀 Vision: A Public-Ready Plugin

**Transition from Internal to Public:**
- **Code Refactoring:** Ensuring full compliance with WordPress coding standards, sanitization, and security best practices.
- **Module Architecture:** Migrating from a monolithic toolkit to an opt-in, modular architecture (only load what you need).
- **Pro Upgrades:** Establishing a freemium model to support ongoing development as part of our monetization strategy.

## 🛠 Features (Current & Planned)

- **Modular Design:** Disable widgets you don't use to keep the payload feather-light.
- **Modern Assets:** Enqueued CSS and JS only load when a widget is present on the page.
- **GlowOS Integration (Coming Soon):** Hooks for the Pi Coding Agent to automatically deploy and configure widgets via API.

## ⚙️ Installation (Developer Version)

1. Download or clone this repository:
   ```bash
   git clone https://github.com/camster91/PressIQ-Widgets.git
   ```
2. Place the `PressIQ-Widgets` folder into your `/wp-content/plugins/` directory.
3. Activate the **PressIQ Widgets** plugin through the 'Plugins' menu in WordPress.

## 📈 Roadmap for WordPress.org Submission

- [ ] Rename all internal functions and classes to use the `pressiq_` prefix.
- [ ] Implement robust escaping (`esc_html__`, `esc_attr__`) on all output.
- [ ] Create an admin settings page for toggling modules on/off.
- [ ] Prepare `readme.txt` using the official WordPress plugin template.
- [ ] Generate standard banners and icons (`banner-772x250.png`, `icon-128x128.png`).

---
*Developed by Cameron Ashley / Nexus AI.*
