# Changelog

### 2026-05-06

- Fixed newsletter subscription Turnstile check by overriding the correct OXID `send()` method instead of `addMe()`
- Bumped version to 1.0.6

### 2026-03-05

- Fixed Turnstile validation in all controllers: captcha check now runs before the actual action (contact, password reset, newsletter)
- Bumped version to 1.0.5

### 2025-11-22

- Added README with module description, installation guide and configuration notes

### 2025-08-19

- Fixed PHP 7 compatibility for TurnstileService

### 2025-07-31

- Fixed user registration with Turnstile validation

### 2025-06-19

- Removed redundant language configuration from module metadata

### 2025-06-14

- Fixed user registration in metadata

### 2025-06-08

- Initial implementation of Cloudflare Turnstile module for OXID eShop
- Captcha protection for contact form, forgot password, newsletter signup and registration
- Admin configuration for site key, secret key, theme and size
- Individually toggleable Turnstile checks per form
