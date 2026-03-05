# API Guide - Token Refresh

## Endpoint

```
POST /api/token/refresh
```

## Request

Send a JSON body with the user's refresh token:

```json
{
  "refresh_token": "YOUR_REFRESH_TOKEN"
}
```

**Headers:**

```
Content-Type: application/json
```

## Response

### Success (200 OK)

```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJub...",
  "refresh_token": "0.AXYA..."
}
```

- `access_token` — Use this as a Bearer token for MS Graph API calls. Valid for ~1 hour.
- `refresh_token` — The new refresh token. **You must store this and use it for the next refresh.** The old one is no longer valid.

### Error (401 Unauthorized)

```json
{
  "error": "Invalid or expired refresh token."
}
```

This means the refresh token is expired, revoked, or invalid. The user must log in again to get a new one.

## Usage with MS Graph

Use the `access_token` as a Bearer token in the `Authorization` header:

```
GET https://graph.microsoft.com/v1.0/me
Authorization: Bearer ACCESS_TOKEN_HERE
```

### Examples

**Read emails:**
```
GET https://graph.microsoft.com/v1.0/me/messages
Authorization: Bearer ACCESS_TOKEN_HERE
```

**Send email:**
```
POST https://graph.microsoft.com/v1.0/me/sendMail
Authorization: Bearer ACCESS_TOKEN_HERE
Content-Type: application/json

{
  "message": {
    "subject": "Hello",
    "body": { "contentType": "Text", "content": "Hi there" },
    "toRecipients": [{ "emailAddress": { "address": "someone@example.com" } }]
  }
}
```

**Read calendar events:**
```
GET https://graph.microsoft.com/v1.0/me/events
Authorization: Bearer ACCESS_TOKEN_HERE
```

**Read OneDrive files:**
```
GET https://graph.microsoft.com/v1.0/me/drive/root/children
Authorization: Bearer ACCESS_TOKEN_HERE
```

## Important Notes

- Each refresh returns a **new** refresh token. Always store the latest one.
- Access tokens expire after ~1 hour. Refresh before they expire.
- If a refresh fails, the user must log in again on the website.
