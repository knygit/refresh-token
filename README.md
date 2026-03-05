# Refresh Token Service

A simple PHP application that lets users log in with their Microsoft account, obtain a refresh token, and hand it to a bot. The bot then uses an API endpoint to exchange the refresh token for new MS Graph access tokens.

## Flow

1. User visits the site and clicks **Login with Microsoft**.
2. Microsoft OAuth2 returns an access token and refresh token.
3. The dashboard displays the refresh token for the user to copy.
4. The user gives the refresh token to their bot.
5. The bot calls `POST /api/token/refresh` to get a fresh access token whenever needed.

## Requirements

- PHP 7.4+ with cURL extension
- Apache with `mod_rewrite` enabled
- A Microsoft App Registration (Azure AD) with the required scopes

## Setup

1. Clone the repository.
2. Copy `.env.example` to `.env` and fill in your Microsoft App Registration credentials.
3. Point your Apache document root to the `public/` directory.
4. Ensure `.htaccess` rewrites are enabled (`AllowOverride All`).

## Configuration (.env)

| Variable | Description |
|---|---|
| `MS_CLIENT_ID` | Application (client) ID from Azure |
| `MS_CLIENT_SECRET` | Client secret from Azure |
| `MS_TENANT_ID` | Tenant ID (or `common` for multi-tenant) |
| `MS_REDIRECT_URI` | Must match the redirect URI in Azure (e.g. `https://yourdomain.com/callback`) |
| `MS_SCOPES` | Space-separated list of MS Graph scopes |
| `APP_SECRET` | Random string used for internal signing |

## MS Graph Scopes

| Scope | Purpose |
|---|---|
| `offline_access` | Receive refresh token |
| `User.Read` | Read user profile |
| `Mail.Read` | Read user's emails |
| `Mail.Send` | Send emails as the user |
| `Calendars.Read` | Read user's calendar |
| `Calendars.ReadWrite` | Create/edit calendar events |
| `Files.Read` | Read user's OneDrive files |

## Project Structure

```
public/              Document root (Apache points here)
  index.php          Front controller
  .htaccess          URL rewriting
  robots.txt         Block search engines
  assets/css/        Stylesheets
app/
  config.php         Loads .env
  auth.php           Session-based auth
  microsoft.php      OAuth2 + cURL to Microsoft
  router.php         Simple router
  controllers/       Route handlers
templates/           PHP templates
```
