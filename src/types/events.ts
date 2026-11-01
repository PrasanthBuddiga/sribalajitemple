export interface TempleEvent {
  id: number;
  title: string;
  description: string;
  event_date: string;
  time: string;
  location: string;
  image_url: string;
  registration_link: string;
}

export interface TempleSponsor {
  id: number;
  sponsor_name: string;
  tier: string;
  logo_url: string;
  website_url: string;
}

export interface GalleryAlbum {
  id: number;
  title: string;
  category: string;
  image_urls: string[];
  upload_date: string;
}

export interface TempleVideo {
  id: number;
  title: string;
  description: string;
  youtube_url: string;
  upload_date: string;
}
