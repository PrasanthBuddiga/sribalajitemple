# Sri Balaji Temple Custom API Plugin

This WordPress plugin provides custom REST API endpoints for the Sri Balaji Temple website to fetch dynamic content (Events, Sponsors, Gallery) from WordPress.

## Installation

1. Upload the `sribalaji-api.php` file to your WordPress site's `/wp-content/plugins/` directory
2. Navigate to Plugins in your WordPress admin dashboard
3. Activate the "Sri Balaji Temple Custom API" plugin
4. The custom endpoints will be available immediately

## API Endpoints

### 1. Events Endpoint
**URL:** `/wp-json/sribalaji/v1/events`

**Method:** `GET`

**Response:**
```json
[
  {
    "id": 123,
    "title": "Sri Venkateswara Swamy Abhishekam",
    "description": "<p>Join us for the sacred Abhishekam ceremony...</p>",
    "event_date": "2024-11-14",
    "time": "10:00 AM",
    "location": "Sri Balaji Temple, Bellevue",
    "image_url": "https://example.com/wp-content/uploads/2024/11/abhishekam.jpg",
    "registration_link": "https://jotform.com/..."
  },
  {
    "id": 124,
    "title": "Dolotsavam Festival",
    "description": "<p>Celebrate the divine Dolotsavam festival...</p>",
    "event_date": "2024-11-21",
    "time": "6:00 PM",
    "location": "Sri Balaji Temple, Bellevue",
    "image_url": "https://example.com/wp-content/uploads/2024/11/dolotsavam.jpg",
    "registration_link": "https://jotform.com/..."
  }
]
```

### 2. Sponsors Endpoint
**URL:** `/wp-json/sribalaji/v1/sponsors`

**Method:** `GET`

**Response:**
```json
[
  {
    "id": 201,
    "sponsor_name": "ABC Corporation",
    "tier": "Platinum",
    "logo_url": "https://example.com/wp-content/uploads/2024/11/abc-logo.png",
    "website_url": "https://abc-corp.com"
  },
  {
    "id": 202,
    "sponsor_name": "XYZ Foundation",
    "tier": "Gold",
    "logo_url": "https://example.com/wp-content/uploads/2024/11/xyz-logo.png",
    "website_url": "https://xyz-foundation.org"
  },
  {
    "id": 203,
    "sponsor_name": "Local Business",
    "tier": "Silver",
    "logo_url": "https://example.com/wp-content/uploads/2024/11/local-logo.png",
    "website_url": "https://local-business.com"
  }
]
```

### 3. Gallery Endpoint
**URL:** `/wp-json/sribalaji/v1/gallery`

**Method:** `GET`

**Optional Query Parameter:** `?category=Deities` (filter by category)

**Response:**
```json
[
  {
    "id": 301,
    "title": "Deities Gallery",
    "category": "Deities",
    "image_urls": [
      "https://example.com/wp-content/uploads/2024/11/deity1.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity2.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity3.jpg"
    ],
    "upload_date": "2024-11-01 10:00:00"
  },
  {
    "id": 302,
    "title": "Brahmotsavam 2024",
    "category": "Festivals",
    "image_urls": [
      "https://example.com/wp-content/uploads/2024/11/festival1.jpg",
      "https://example.com/wp-content/uploads/2024/11/festival2.jpg",
      "https://example.com/wp-content/uploads/2024/11/festival3.jpg"
    ],
    "upload_date": "2024-11-05 14:30:00"
  }
]
```

### 4. Videos Endpoint
**URL:** `/wp-json/sribalaji/v1/videos`

**Method:** `GET`

**Response:**
```json
[
  {
    "id": 401,
    "title": "Sri Balaji Temple - Grand Opening",
    "description": "Watch the grand opening ceremony of Sri Balaji Temple in Bellevue, WA.",
    "youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
    "thumbnail_url": "https://example.com/wp-content/uploads/2024/11/video-thumb1.jpg",
    "upload_date": "2024-11-01 10:00:00"
  },
  {
    "id": 402,
    "title": "Diwali Celebration 2024",
    "description": "Highlights from our Diwali festival celebrations with cultural programs.",
    "youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
    "thumbnail_url": "https://example.com/wp-content/uploads/2024/11/video-thumb2.jpg",
    "upload_date": "2024-11-05 14:30:00"
  }
]
```

## Custom Post Types

The plugin registers three custom post types in WordPress:

### 1. Temple Events (`temple_event`)
- **Fields:**
  - Title (post title)
  - Description (post content)
  - Event Date (custom field: `event_date`)
  - Time (custom field: `event_time`)
  - Location (custom field: `event_location`)
  - Featured Image
  - Registration Link (custom field: `registration_link`)

### 2. Temple Sponsors (`temple_sponsor`)
- **Fields:**
  - Sponsor Name (custom field: `sponsor_name`)
  - Tier (custom field: `sponsor_tier` - e.g., Platinum, Gold, Silver)
  - Logo (featured image)
  - Website URL (custom field: `website_url`)

### 3. Temple Gallery (`temple_gallery`)
- **Fields:**
  - Image (featured image)
  - Caption (post excerpt or title)
  - Category (custom field: `gallery_category` - e.g., Deities, Festivals, Events)
  - Upload Date (automatic)

## CORS Configuration

All endpoints support CORS with the following headers:
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type, Authorization`

This allows your Astro frontend to fetch data from the WordPress API without CORS errors.

## Usage in Astro

### Fetch Events
```astro
---
const response = await fetch('https://your-wordpress-site.com/wp-json/sribalaji/v1/events');
const events = await response.json();
---
```

### Fetch Sponsors
```astro
---
const response = await fetch('https://your-wordpress-site.com/wp-json/sribalaji/v1/sponsors');
const sponsors = await response.json();
---
```

### Fetch Gallery
```astro
---
const response = await fetch('https://your-wordpress-site.com/wp-json/sribalaji/v1/gallery');
const gallery = await response.json();

// Filter by category
const response = await fetch('https://your-wordpress-site.com/wp-json/sribalaji/v1/gallery?category=Deities');
const deitiesGallery = await response.json();
---
```

## Content Management

After activating the plugin, you'll see three new sections in your WordPress admin:

1. **Temple Events** - Add and manage temple events
2. **Temple Sponsors** - Add and manage sponsors
3. **Temple Gallery** - Add and manage gallery images

Use the WordPress admin interface to create content, and it will automatically be available via the API endpoints.

## Security Notes

- All endpoints are publicly accessible (no authentication required for GET requests)
- Custom fields are sanitized using WordPress sanitization functions
- URLs are escaped using `esc_url_raw` to prevent XSS attacks
- The plugin follows WordPress REST API best practices

## Testing

Test the endpoints by visiting these URLs in your browser:

- `https://your-wordpress-site.com/wp-json/sribalaji/v1/events`
- `https://your-wordpress-site.com/wp-json/sribalaji/v1/sponsors`
- `https://your-wordpress-site.com/wp-json/sribalaji/v1/gallery`

You should see JSON responses as shown in the examples above.
