# Astro Events Page Integration

## Changes Made

### 1. Updated `src/pages/events.astro`
- Removed dependency on local `sample-wp-posts.json`
- Added dynamic fetch from WordPress API: `https://sribalajitemple.org/wp-json/sribalaji/v1/events`
- Added error handling with user-friendly error messages
- API fetch happens at build time (Astro's default behavior)

### 2. Updated `src/components/EventCard.astro`
- Changed interface from `WPPost` to `TempleEvent` to match WordPress API response
- Updated field mappings:
  - `title.rendered` → `title`
  - `excerpt.rendered` → `description`
  - `_embedded['wp:featuredmedia'][0].source_url` → `image_url`
- Added display of event details:
  - Event date (formatted)
  - Time
  - Location
- Added conditional button:
  - If `registration_link` exists: "Register Now" button linking to the form
  - Otherwise: "View Event Flyer" button opening the modal

### 3. Created `src/types/events.ts`
Added TypeScript type definitions for:
- `TempleEvent` - Events from WordPress API
- `TempleSponsor` - Sponsors from WordPress API
- `GalleryItem` - Gallery items from WordPress API

## API Response Structure

The WordPress API endpoint returns events in this format:

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
    "registration_link": "https://form.jotform.com/243641479186163"
  }
]
```

## Event Card Display

Each event card now shows:
1. **Featured Image** - Click to view full flyer in modal
2. **Event Title** - Linked/hoverable
3. **Description** - Truncated to 3 lines
4. **Event Details:**
   - 📅 Date (formatted: "Mon, Nov 14, 2024")
   - ⏰ Time (e.g., "10:00 AM")
   - 📍 Location
5. **Action Button:**
   - "Register Now" - Links to registration form if available
   - "View Event Flyer" - Opens modal for flyer viewing

## Error Handling

If the API fetch fails, users see:
- Red error banner with error message
- Friendly explanation
- Instruction to try again later or contact temple administrator

## Build-Time vs Runtime

The current implementation uses **build-time fetching**:
- Events are fetched when the Astro site is built
- Better performance and SEO
- Static HTML is generated

To switch to **runtime fetching** (for live updates without rebuild), modify the fetch to happen in a client-side script instead of the Astro frontmatter.

## Testing

The dev server is already running at `http://localhost:4321`. Visit:
- `http://localhost:4321/events` to see the updated events page

The page will now fetch real data from the WordPress API and display events with their dates, times, locations, and registration links.

## Next Steps

To complete the integration:

1. **Ensure WordPress plugin is installed** on `sribalajitemple.org`
2. **Add events in WordPress** using the "Temple Events" custom post type
3. **Fill in custom fields:**
   - Event Date
   - Time
   - Location
   - Registration Link
   - Featured Image
4. **Test the endpoint** at `https://sribalajitemple.org/wp-json/sribalaji/v1/events`
5. **Verify the Astro site** displays the events correctly

## Future Enhancements

- Add filtering by date/month
- Add search functionality
- Add event countdown timers
- Add calendar view integration
- Add email reminders for upcoming events
