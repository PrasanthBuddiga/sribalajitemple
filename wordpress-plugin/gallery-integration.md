# Gallery WordPress API Integration

## Changes Made

### 1. Updated `src/types/events.ts`

**Changed:**
- Renamed `GalleryItem` to `GalleryAlbum` to reflect the new structure
- Updated interface to match WordPress API with multi-image support:
  - `title` - Gallery album title
  - `category` - Gallery category (e.g., "Deities", "Festivals", "Events")
  - `image_urls` - Array of image URLs for this album
  - `upload_date` - Upload date

### 2. Updated `src/pages/gallery.astro`

**Removed:**
- Hardcoded sample gallery data with placeholder images (Picsum)
- Album grouping logic (no longer needed - WordPress returns albums directly)
- Mock data like 'Abhishekam'

**Added:**
- Dynamic fetch from WordPress API: `https://sribalajitemple.org/wp-json/sribalaji/v1/gallery`
- Error handling with user-friendly error messages
- Direct mapping of WordPress albums to GalleryAlbum component format
- TypeScript typing using `GalleryAlbum` from `types/events.ts`

**Data Transformation:**
The WordPress API now returns complete albums with multiple images. The code transforms them to match the GalleryAlbum component:
```javascript
const albumsForComponent = galleryAlbums.map(album => ({
  title: album.title,
  photos: album.image_urls.map(url => ({
    url: url,
    alt: album.title
  }))
}));
```

### 3. Updated `src/components/GalleryLightbox.astro`

**Mobile Optimizations:**
- Full-screen on mobile (no padding)
- Rounded corners on desktop only
- Smaller touch targets on mobile (40px vs 48px on desktop)
- Responsive text sizes
- Better spacing for mobile

### 4. Updated `src/components/GalleryAlbum.astro`

**Mobile Optimizations:**
- Reduced padding on mobile
- Smaller title text on mobile
- Tighter grid gaps on mobile
- Maintains 2-column grid on mobile, 3-column on desktop

### 5. Updated WordPress Plugin (`sribalaji-api.php`)

**Changed Gallery Endpoint:**
- Now fetches all attached media images for each gallery post
- Uses `get_attached_media('image', $item->ID)` to get all images
- Falls back to featured image if no attached images
- Returns array of image URLs in `image_urls` field
- Structure matches new GalleryAlbum interface

## API Response Structure

The WordPress API endpoint now returns gallery albums in this format:

```json
[
  {
    "id": 301,
    "title": "Deities Gallery",
    "category": "Deities",
    "image_urls": [
      "https://example.com/wp-content/uploads/2024/11/deity1.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity2.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity3.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity4.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity5.jpg",
      "https://example.com/wp-content/uploads/2024/11/deity6.jpg"
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

## WordPress Content Management

To add gallery albums in WordPress:

1. Go to WordPress Admin → Temple Gallery
2. Add New Gallery Album
3. Fill in:
   - **Title** - Album name (e.g., "Deities Gallery", "Brahmotsavam 2024")
   - **Custom Field: gallery_category** - Category (e.g., "Deities", "Festivals", "Events")
   - **Upload Images** - Use the WordPress media library to upload multiple images
     - Click "Add Media" button
     - Select multiple images from your computer
     - Upload and attach them to this gallery post
4. Publish

**Note:** Each gallery post can have multiple images attached. The API will return all attached images in the `image_urls` array.

## Error Handling

If the API fetch fails, users see:
- Red error banner with error message
- Friendly explanation
- Instruction to try again later or contact temple administrator

## Current Limitations

1. **No album sorting** - Albums are displayed in the order returned by WordPress. Could add sorting by date or custom order.

2. **No pagination** - All gallery albums and videos are fetched at once. For large galleries, consider adding pagination.

## Future Enhancements

1. **Album sorting** - Add custom field for album order or sort by date
2. **Lazy loading** - Implement infinite scroll or pagination for large galleries
3. **Image optimization** - Add responsive image sizes in WordPress
4. **Album thumbnails** - Use first image as album cover
5. **Filter by category** - Add category filter buttons for better UX
6. **Download option** - Allow users to download images (if permitted)
7. **Video thumbnails** - Display custom thumbnails instead of YouTube default
8. **Video categories** - Add category support for videos (similar to gallery)

## Testing

Test the gallery at `http://localhost:4321/gallery`:

1. Verify albums are displayed with their titles
2. Verify each album shows all its images in the grid
3. Test lightbox navigation (next/prev buttons)
4. Test keyboard navigation (arrow keys, escape)
5. Test mobile responsiveness (full-screen lightbox)
6. Test error handling (disable API to see error state)

## Comparison with Events

**Similarities:**
- Both fetch from WordPress API at build time
- Both use TypeScript types from `types/events.ts`
- Both have error handling
- Both display placeholder messages when empty

**Differences:**
- Events are individual items, Gallery returns complete albums with multiple images
- Events have date/time/location, Gallery albums have title/category/image_urls
- Events link to registration forms, Gallery items open in lightbox
- Gallery has video section (currently hardcoded)
- Gallery uses WordPress media library for multi-image uploads
