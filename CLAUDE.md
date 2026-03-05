# Project: Refresh Token

## Tech Stack

- PHP
- JavaScript
- MySQL

## Architecture

- **Document root isolation:** The public web root is separated from the application code.
- **Front Controller pattern:** All requests are routed through a single entry point.
- **URL rewriting:** Handled via `.htaccess` (Apache).

## Configuration

- Environment variables and secret keys are stored in a `.env` file in the project root (outside the document root).
- The `.env` file must **never** be committed to version control — add it to `.gitignore`.
- A `.env.example` file with empty or dummy values is committed as a template for other developers.
- PHP loads variables manually via `parse_ini_file()` or equivalent (no Composer/libraries).

## Workflow Rules

- Always commit when a task is completed.
- Always commit when the user has uploaded a file.
- Always commit when changes have been made to `.env.example`.
- **Never** commit `.env` directly — verify that `.gitignore` protects it.

## Constraints

- **No SDK or Composer:** All dependencies are managed manually; no package manager is used.

## Features

### 1. User Login and Token Management

A page where a user can log in. After successful login, the user has access to a page where they can view and copy their **refresh token** (intended for use in an external bot).

**Security warning displayed on the refresh token page:**

> WARNING: Treat your refresh token like a password. Never share it with anyone you do not trust. If this token is compromised, an attacker can gain access to your account. If you suspect misuse, immediately use the "Log me out everywhere" feature.

### 2. API Endpoint for Token Renewal

An API endpoint the bot uses to renew access tokens.

- **Endpoint:** `/api/token/refresh`
- **Method:** `POST`
- **Request Body:** `{"refresh_token": "<user_refresh_token>"}`
- **Success Response (200 OK):** `{"access_token": "<new_access_token>", "refresh_token": "<new_refresh_token>"}`
- **Error Response (401 Unauthorized):** If the provided refresh token is invalid or expired.

Upon each successful renewal, the old refresh token is invalidated and the new one stored for the user.

### 3. Global Logout

A button labelled **"Log me out everywhere"** on the user's profile page. When clicked, all active sessions and all issued refresh tokens for that user are immediately invalidated.
