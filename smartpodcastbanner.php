<?php
/*
Plugin Name: Smart Banners for Podcasts
Plugin URI: https://crixu.blog/plugins/smartpodcastbanners
Description: Adds a Smart Banner for an Apple Podcast to the website header
Version: 1.0
Author: Lucas Radke
Author URI: https://crixu.blog
*/

// Add the settings page to the WordPress admin menu
function add_settings_page() {
    add_options_page( 'Smart Banners for Podcasts Settings', 'Smart Banners for Podcasts', 'manage_options', 'smart-banners-for-podcasts', 'render_settings_page' );
}
add_action( 'admin_menu', 'add_settings_page' );

// Render the settings page
function render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Smart Banners for Podcasts Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'smart-banners-for-podcasts' ); ?>
            <?php do_settings_sections( 'smart-banners-for-podcasts' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Add the podcast ID setting to the settings page
function add_settings() {
    add_settings_section( 'smart-banners-for-podcasts', 'Podcast ID', 'render_settings', 'smart-banners-for-podcasts' );
    add_settings_field( 'podcast_id', 'Podcast ID', 'render_podcast_id_field', 'smart-banners-for-podcasts', 'smart-banners-for-podcasts' );
    register_setting( 'smart-banners-for-podcasts', 'podcast_id' );
}
add_action( 'admin_init', 'add_settings' );

// Render the podcast ID field
function render_podcast_id_field() {
    $podcast_id = get_option( 'podcast_id' );
    ?>
    <input type="text" id="podcast_id" name="podcast_id" value="<?php echo esc_attr( $podcast_id ); ?>" />
    <?php
}

// Render the settings section
function render_settings() {
    echo 'Enter the podcast ID for your Apple Podcast.';
}


// Add the Smart Banner to the website header
function add_smart_banner() {
    // Retrieve the podcast ID from the settings
    $podcast_id = get_option( 'podcast_id' );

    // Construct the URL for the Smart Banner
    $smart_banner_url = "https://podcasts.apple.com/us/podcast/id$podcast_id";

    // Add the Smart Banner to the header
    echo '<script>
        var smartBanner = document.createElement("meta");
        smartBanner.name = "apple-itunes-app";
        smartBanner.content = "app-id=' . esc_attr($podcast_id) . ', app-argument=' . esc_attr( esc_url($smart_banner_url)) . '";
        document.getElementsByTagName("head")[0].appendChild(smartBanner);
    </script>';
}

// Hook the Smart Banner into the WordPress header
add_action( 'wp_head', 'add_smart_banner' );