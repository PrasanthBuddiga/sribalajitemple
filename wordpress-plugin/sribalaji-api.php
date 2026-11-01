<?php
/**
 * Plugin Name: Sri Balaji Temple Custom API
 * Description: Custom REST API endpoints for Events, Sponsors, and Gallery
 * Version: 1.0.0
 * Author: Sri Balaji Temple
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Types
 */
function sribalaji_register_custom_post_types() {
    // Events Post Type
    register_post_type('temple_event', [
        'label' => 'Temple Events',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'events'],
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
    ]);

    // Sponsors Post Type
    register_post_type('temple_sponsor', [
        'label' => 'Temple Sponsors',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'thumbnail'],
        'rewrite' => ['slug' => 'sponsors'],
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
    ]);

    // Gallery Post Type
    register_post_type('temple_gallery', [
        'label' => 'Temple Gallery',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'gallery'],
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
    ]);

    // Videos Post Type
    register_post_type('temple_video', [
        'label' => 'Temple Videos',
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'videos'],
        'has_archive' => true,
        'menu_icon' => 'dashicons-video',
    ]);
}
add_action('init', 'sribalaji_register_custom_post_types');

/**
 * Register Custom Fields for Events
 */
function sribalaji_register_event_meta() {
    register_post_meta('temple_event', 'event_date', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('temple_event', 'event_time', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('temple_event', 'event_location', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('temple_event', 'registration_link', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ]);
}
add_action('init', 'sribalaji_register_event_meta');

/**
 * Register Custom Fields for Sponsors
 */
function sribalaji_register_sponsor_meta() {
    register_post_meta('temple_sponsor', 'sponsor_name', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('temple_sponsor', 'sponsor_tier', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    register_post_meta('temple_sponsor', 'website_url', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ]);
}
add_action('init', 'sribalaji_register_sponsor_meta');

/**
 * Register Custom Fields for Gallery
 */
function sribalaji_register_gallery_meta() {
    register_post_meta('temple_gallery', 'gallery_category', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);
}
add_action('init', 'sribalaji_register_gallery_meta');

/**
 * Register Custom Fields for Videos
 */
function sribalaji_register_video_meta() {
    register_post_meta('temple_video', 'youtube_url', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
    ]);
}
add_action('init', 'sribalaji_register_video_meta');

/**
 * Register REST API Routes
 */
function sribalaji_register_api_routes() {
    register_rest_route('sribalaji/v1', '/events', [
        'methods' => 'GET',
        'callback' => 'sribalaji_get_events',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('sribalaji/v1', '/sponsors', [
        'methods' => 'GET',
        'callback' => 'sribalaji_get_sponsors',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('sribalaji/v1', '/gallery', [
        'methods' => 'GET',
        'callback' => 'sribalaji_get_gallery',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('sribalaji/v1', '/videos', [
        'methods' => 'GET',
        'callback' => 'sribalaji_get_videos',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'sribalaji_register_api_routes');

/**
 * Enable CORS for Custom Endpoints
 */
function sribalaji_enable_cors() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
add_action('rest_api_init', function () {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        return $value;
    });
}, 15);

/**
 * Events Endpoint Callback
 */
function sribalaji_get_events($request) {
    $args = [
        'post_type' => 'temple_event',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $events = get_posts($args);
    $data = [];

    foreach ($events as $event) {
        $featured_image = get_the_post_thumbnail_url($event->ID, 'full');
        
        $data[] = [
            'id' => $event->ID,
            'title' => $event->post_title,
            'description' => $event->post_content,
            'event_date' => get_post_meta($event->ID, 'event_date', true),
            'time' => get_post_meta($event->ID, 'event_time', true),
            'location' => get_post_meta($event->ID, 'event_location', true),
            'image_url' => $featured_image ? $featured_image : '',
            'registration_link' => get_post_meta($event->ID, 'registration_link', true),
        ];
    }

    return new WP_REST_Response($data, 200);
}

/**
 * Sponsors Endpoint Callback
 */
function sribalaji_get_sponsors($request) {
    $args = [
        'post_type' => 'temple_sponsor',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'meta_value',
        'meta_key' => 'sponsor_tier',
        'order' => 'ASC',
    ];

    $sponsors = get_posts($args);
    $data = [];

    foreach ($sponsors as $sponsor) {
        $featured_image = get_the_post_thumbnail_url($sponsor->ID, 'full');
        
        $data[] = [
            'id' => $sponsor->ID,
            'sponsor_name' => get_post_meta($sponsor->ID, 'sponsor_name', true) ?: $sponsor->post_title,
            'tier' => get_post_meta($sponsor->ID, 'sponsor_tier', true),
            'logo_url' => $featured_image ? $featured_image : '',
            'website_url' => get_post_meta($sponsor->ID, 'website_url', true),
        ];
    }

    return new WP_REST_Response($data, 200);
}

/**
 * Gallery Endpoint Callback
 */
function sribalaji_get_gallery($request) {
    $category = $request->get_param('category');
    
    $args = [
        'post_type' => 'temple_gallery',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    if ($category) {
        $args['meta_key'] = 'gallery_category';
        $args['meta_value'] = $category;
    }

    $gallery_items = get_posts($args);
    $data = [];

    foreach ($gallery_items as $item) {
        // Get all attached images for this gallery post
        $images = get_attached_media('image', $item->ID);
        $image_urls = [];
        
        foreach ($images as $image) {
            $image_url = wp_get_attachment_image_url($image->ID, 'full');
            if ($image_url) {
                $image_urls[] = $image_url;
            }
        }
        
        // If no attached images, fall back to featured image
        if (empty($image_urls)) {
            $featured_image = get_the_post_thumbnail_url($item->ID, 'full');
            if ($featured_image) {
                $image_urls[] = $featured_image;
            }
        }
        
        $data[] = [
            'id' => $item->ID,
            'title' => $item->post_title,
            'category' => get_post_meta($item->ID, 'gallery_category', true),
            'image_urls' => $image_urls,
            'upload_date' => $item->post_date,
        ];
    }

    return new WP_REST_Response($data, 200);
}

/**
 * Videos Endpoint Callback
 */
function sribalaji_get_videos($request) {
    $args = [
        'post_type' => 'temple_video',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ];

    $videos = get_posts($args);
    $data = [];

    foreach ($videos as $video) {
        $featured_image = get_the_post_thumbnail_url($video->ID, 'full');
        
        $data[] = [
            'id' => $video->ID,
            'title' => $video->post_title,
            'description' => $video->post_content ?: $video->post_excerpt,
            'youtube_url' => get_post_meta($video->ID, 'youtube_url', true),
            'thumbnail_url' => $featured_image ? $featured_image : '',
            'upload_date' => $video->post_date,
        ];
    }

    return new WP_REST_Response($data, 200);
}

/**
 * Add Custom Fields to REST API for Custom Post Types
 */
function sribalaji_add_custom_fields_to_rest() {
    // Add custom fields to temple_event
    register_rest_field('temple_event', 'event_date', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'event_date', true);
        },
    ]);

    register_rest_field('temple_event', 'event_time', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'event_time', true);
        },
    ]);

    register_rest_field('temple_event', 'event_location', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'event_location', true);
        },
    ]);

    register_rest_field('temple_event', 'registration_link', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'registration_link', true);
        },
    ]);

    // Add custom fields to temple_sponsor
    register_rest_field('temple_sponsor', 'sponsor_name', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'sponsor_name', true);
        },
    ]);

    register_rest_field('temple_sponsor', 'sponsor_tier', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'sponsor_tier', true);
        },
    ]);

    register_rest_field('temple_sponsor', 'website_url', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'website_url', true);
        },
    ]);

    // Add custom fields to temple_gallery
    register_rest_field('temple_gallery', 'gallery_category', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'gallery_category', true);
        },
    ]);

    // Add custom fields to temple_video
    register_rest_field('temple_video', 'youtube_url', [
        'get_callback' => function ($post) {
            return get_post_meta($post['id'], 'youtube_url', true);
        },
    ]);
}
add_action('rest_api_init', 'sribalaji_add_custom_fields_to_rest');
