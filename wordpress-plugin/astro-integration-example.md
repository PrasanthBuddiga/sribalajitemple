# Astro Integration Example

This document shows how to integrate the WordPress API endpoints into your Astro components.

## Environment Configuration

First, add your WordPress site URL to your `.env` file:

```env
WORDPRESS_API_URL=https://your-wordpress-site.com
```

## Fetching Data in Astro

### Events Page Example

```astro
---
import Layout from '../layouts/Layout.astro';
import Header from '../components/Header.astro';
import Footer from '../components/Footer.astro';

// Fetch events from WordPress API
const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/wp-json/sribalaji/v1/events`);
const events = await response.json();
---

<Layout title="Events | Sri Balaji Temple">
  <Header />
  
  <div class="pt-24 pb-20 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl font-bold text-center mb-12">Temple Events</h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {events.map((event) => (
          <div class="rounded-2xl bg-white/80 border border-maroon/20 p-6 shadow-lg">
            {event.image_url && (
              <img 
                src={event.image_url} 
                alt={event.title} 
                class="w-full h-48 object-cover rounded-lg mb-4"
              />
            )}
            <h3 class="text-xl font-bold text-ink mb-2">{event.title}</h3>
            <p class="text-ink-light text-sm mb-4 flex-grow">{event.description}</p>
            <div class="space-y-2 text-sm text-ink-light">
              <p>📅 {event.event_date}</p>
              <p>⏰ {event.time}</p>
              <p>📍 {event.location}</p>
            </div>
            {event.registration_link && (
              <a 
                href={event.registration_link} 
                target="_blank" 
                rel="noopener noreferrer"
                class="inline-block mt-4 px-4 py-2 rounded-lg bg-saffron hover:bg-maroon text-white font-medium transition-colors"
              >
                Register Now
              </a>
            )}
          </div>
        ))}
      </div>
    </div>
  </div>

  <Footer />
</Layout>
```

### Sponsors Page Example

```astro
---
import Layout from '../layouts/Layout.astro';
import Header from '../components/Header.astro';
import Footer from '../components/Footer.astro';

// Fetch sponsors from WordPress API
const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/wp-json/sribalaji/v1/sponsors`);
const sponsors = await response.json();

// Group sponsors by tier
const sponsorsByTier = sponsors.reduce((acc, sponsor) => {
  const tier = sponsor.tier || 'Other';
  if (!acc[tier]) acc[tier] = [];
  acc[tier].push(sponsor);
  return acc;
}, {});

const tierOrder = ['Platinum', 'Gold', 'Silver', 'Other'];
---

<Layout title="Sponsors | Sri Balaji Temple">
  <Header />
  
  <div class="pt-24 pb-20 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl font-bold text-center mb-12">Our Sponsors</h1>
      
      {tierOrder.map((tier) => (
        sponsorsByTier[tier] && sponsorsByTier[tier].length > 0 && (
          <section class="mb-12">
            <h2 class="text-2xl font-bold text-maroon mb-6">{tier} Sponsors</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {sponsorsByTier[tier].map((sponsor) => (
                <div class="rounded-2xl bg-white/80 border border-maroon/20 p-6 shadow-lg text-center">
                  {sponsor.logo_url && (
                    <img 
                      src={sponsor.logo_url} 
                      alt={sponsor.sponsor_name} 
                      class="w-full h-32 object-contain mb-4"
                    />
                  )}
                  <h3 class="text-lg font-bold text-ink mb-2">{sponsor.sponsor_name}</h3>
                  {sponsor.website_url && (
                    <a 
                      href={sponsor.website_url} 
                      target="_blank" 
                      rel="noopener noreferrer"
                      class="text-saffron hover:text-maroon transition-colors"
                    >
                      Visit Website
                    </a>
                  )}
                </div>
              ))}
            </div>
          </section>
        )
      ))}
    </div>
  </div>

  <Footer />
</Layout>
```

### Gallery Page Example

```astro
---
import Layout from '../layouts/Layout.astro';
import Header from '../components/Header.astro';
import Footer from '../components/Footer.astro';

// Fetch gallery from WordPress API
const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/wp-json/sribalaji/v1/gallery`);
const gallery = await response.json();

// Group by category
const galleryByCategory = gallery.reduce((acc, item) => {
  const category = item.category || 'Other';
  if (!acc[category]) acc[category] = [];
  acc[category].push(item);
  return acc;
}, {});
---

<Layout title="Gallery | Sri Balaji Temple">
  <Header />
  
  <div class="pt-24 pb-20 px-4">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl font-bold text-center mb-12">Temple Gallery</h1>
      
      {Object.entries(galleryByCategory).map(([category, items]) => (
        <section class="mb-12">
          <h2 class="text-2xl font-bold text-maroon mb-6">{category}</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {items.map((item) => (
              <div class="rounded-2xl bg-white/80 border border-maroon/20 overflow-hidden shadow-lg">
                <img 
                  src={item.image_url} 
                  alt={item.caption} 
                  class="w-full h-64 object-cover"
                />
                <div class="p-4">
                  <p class="text-ink-light text-sm">{item.caption}</p>
                  <p class="text-ink-light/60 text-xs mt-2">
                    {new Date(item.upload_date).toLocaleDateString()}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  </div>

  <Footer />
</Layout>
```

## Reusable API Helper

Create a utility file to handle API calls:

```typescript
// src/lib/api.ts
export interface Event {
  id: number;
  title: string;
  description: string;
  event_date: string;
  time: string;
  location: string;
  image_url: string;
  registration_link: string;
}

export interface Sponsor {
  id: number;
  sponsor_name: string;
  tier: string;
  logo_url: string;
  website_url: string;
}

export interface GalleryItem {
  id: number;
  image_url: string;
  caption: string;
  category: string;
  upload_date: string;
}

const API_URL = import.meta.env.WORDPRESS_API_URL;

export async function getEvents(): Promise<Event[]> {
  const response = await fetch(`${API_URL}/wp-json/sribalaji/v1/events`);
  return response.json();
}

export async function getSponsors(): Promise<Sponsor[]> {
  const response = await fetch(`${API_URL}/wp-json/sribalaji/v1/sponsors`);
  return response.json();
}

export async function getGallery(category?: string): Promise<GalleryItem[]> {
  const url = category 
    ? `${API_URL}/wp-json/sribalaji/v1/gallery?category=${encodeURIComponent(category)}`
    : `${API_URL}/wp-json/sribalaji/v1/gallery`;
  const response = await fetch(url);
  return response.json();
}
```

## Using the Helper in Components

```astro
---
import Layout from '../layouts/Layout.astro';
import { getEvents } from '../lib/api';

const events = await getEvents();
---

<Layout title="Events | Sri Balaji Temple">
  <!-- Your component code -->
</Layout>
```

## Error Handling

Add error handling for API failures:

```astro
---
let events = [];
let error = null;

try {
  const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/wp-json/sribalaji/v1/events`);
  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }
  events = await response.json();
} catch (e) {
  error = e.message;
  console.error('Failed to fetch events:', e);
}
---

{error ? (
  <div class="text-center text-red-600">
    <p>Unable to load events. Please try again later.</p>
  </div>
) : (
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {events.map((event) => (
      <!-- Event card -->
    ))}
  </div>
)}
```

## Build-Time vs. Runtime Fetching

**For Static Build (recommended):**
- Use `fetch` in Astro frontmatter (as shown above)
- Data is fetched at build time
- Better performance and SEO

**For Dynamic Runtime:**
- Use client-side JavaScript
- Good for frequently changing content
- Example:

```astro
---
import Layout from '../layouts/Layout.astro';
---

<Layout title="Events | Sri Balaji Temple">
  <div id="events-container"></div>
  
  <script>
    async function loadEvents() {
      const response = await fetch(`${import.meta.env.WORDPRESS_API_URL}/wp-json/sribalaji/v1/events`);
      const events = await response.json();
      
      const container = document.getElementById('events-container');
      container.innerHTML = events.map(event => `
        <div class="event-card">
          <h3>${event.title}</h3>
          <p>${event.event_date}</p>
        </div>
      `).join('');
    }
    
    loadEvents();
  </script>
</Layout>
```

## Caching Strategy

For better performance, consider adding caching:

```typescript
// src/lib/api.ts
const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes

const cache = new Map();

export async function getEvents(): Promise<Event[]> {
  const cacheKey = 'events';
  const cached = cache.get(cacheKey);
  
  if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
    return cached.data;
  }
  
  const response = await fetch(`${API_URL}/wp-json/sribalaji/v1/events`);
  const data = await response.json();
  
  cache.set(cacheKey, { data, timestamp: Date.now() });
  return data;
}
```

Note: Caching is handled automatically by Astro's static build process, so runtime caching is only needed for client-side fetching.
