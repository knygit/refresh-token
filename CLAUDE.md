# Project: Refresh Token

## Tech Stack

- PHP (no Composer/SDK)
- JavaScript
- Microsoft OAuth2 / MS Graph API

## Architecture

- **Document root isolation:** `public/` is the web root, app code lives outside it.
- **Front Controller pattern:** All requests route through `public/index.php`.
- **URL rewriting:** `.htaccess` (Apache).
- **No database:** Tokens are stored in PHP sessions and used directly against Microsoft's token endpoint.

## Flow

1. User logs in via Microsoft OAuth2.
2. Microsoft returns access_token + refresh_token → stored in PHP session.
3. User copies their refresh_token from the dashboard.
4. Bot calls `POST /api/token/refresh` with the refresh_token → our API calls Microsoft's token endpoint → returns new access_token + refresh_token.

## Configuration

- `.env` file in project root (outside document root) — **never** committed.
- `.env.example` committed as template.
- PHP loads via `parse_ini_file()`.

## Workflow Rules

- Always commit when a task is completed.
- Always commit when the user has uploaded a file.
- Always commit when changes have been made to `.env.example`.
- **Never** commit `.env` — verify `.gitignore` protects it.

## Constraints

- **No SDK or Composer** — all HTTP calls via cURL.
- **No database** — session-based only.
