---
sidebar_position: 5
---

# API Documentation

Integrate MRShort into your applications using our REST API.

## Authentication

All API requests require an API token. Get yours from **Settings → API Tokens**.

```bash
Authorization: Bearer YOUR_API_TOKEN
```

## Base URL

```
https://mrshort.io/api/v1
```

## Endpoints

### Shorten a Link

```http
POST /shorten
```

**Request Body:**
```json
{
  "url": "https://example.com/your-long-url",
  "alias": "custom-alias"  // optional
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "short_url": "https://mrshort.io/abc123",
    "original_url": "https://example.com/your-long-url",
    "alias": "abc123"
  }
}
```

### Get Link Stats

```http
GET /links/{alias}/stats
```

**Response:**
```json
{
  "success": true,
  "data": {
    "clicks": 1234,
    "earnings": 5.67,
    "countries": {...}
  }
}
```

### List Your Links

```http
GET /links
```

**Query Parameters:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 20)

## Rate Limits

- **100 requests/minute** for free accounts
- **1000 requests/minute** for VIP accounts

## Error Handling

All errors return a JSON response:

```json
{
  "success": false,
  "message": "Error description"
}
```

## Need Help?

Contact us at [support@mrshort.io](mailto:support@mrshort.io) for API assistance.
