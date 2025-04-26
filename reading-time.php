<?php
/**
 * Plugin Name: WP Temps Lecture
 * Plugin URI: https://github.com/crea-troyes/wp-temps-lecture
 * Description: Estime et affiche le temps de lecture des articles WordPress.
 * Version: 1.0.0
 * Author: Alban GUILLIER
 * Author URI: https://blog.crea-troyes.fr
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-temps-lecture
 */


// On compte les mots de l’article en estimant que la vitesse moyenne de lecture est de 200 mots par minute.
function rtc_calculate_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    $words_per_minute = get_option('rtc_words_per_minute', 200);
    $reading_time = ceil($word_count / $words_per_minute);

    $intro_text = get_option('rtc_intro_text', 'Temps de lecture : ');
    $position = get_option('rtc_position', 'before');

    $reading_time_display = "<div class='rtc-reading-time'><strong>{$intro_text} {$reading_time} minutes</strong></div>";


    if ($position === 'after') {
        return $content . $reading_time_display;
    } else {
        return $reading_time_display . $content;
    }
}

function rtc_reading_time_shortcode($atts) {
    global $post;

    if (!$post) {
        return '';
    }

    $atts = shortcode_atts([
        'intro' => get_option('rtc_intro_text', __('Temps de lecture :', 'reading-time-counter')),
    ], $atts, 'reading_time');

    $words = str_word_count(strip_tags($post->post_content));
    $words_per_minute = (int) get_option('rtc_words_per_minute', 200);
    $minutes = ceil($words / $words_per_minute);

    $html = '<div class="rtc-reading-time">';
    $html .= esc_html($atts['intro']) . ' ' . $minutes . ' ' . __('minutes', 'reading-time-counter');
    $html .= '</div>';

    return $html;
}

add_shortcode('reading_time', 'rtc_reading_time_shortcode');


function rtc_add_admin_menu() {
    add_options_page(
        'Reading Time Counter - Paramètres',  // Titre de la page
        'Reading Time',                       // Libellé dans le menu
        'manage_options',                     // Capacité requise
        'rtc_settings',                       // Slug unique
        'rtc_display_settings_page'           // Fonction de rappel
    );
}
add_action('admin_menu', 'rtc_add_admin_menu');

function rtc_display_settings_page() {
    ?>
    <div class="wrap">
        <h1>Paramètres du plugin Reading Time Counter</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('rtc_settings_group');
            do_settings_sections('rtc_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function rtc_settings_init() {
    register_setting('rtc_settings_group', 'rtc_words_per_minute');
    register_setting('rtc_settings_group', 'rtc_position');
    register_setting('rtc_settings_group', 'rtc_intro_text');

    add_settings_section(
        'rtc_settings_section',
        'Réglages principaux',
        null,
        'rtc_settings'
    );

    // Champ : vitesse de lecture
    add_settings_field(
        'rtc_words_per_minute',
        'Vitesse de lecture (mots/minute)',
        function () {
            $value = get_option('rtc_words_per_minute', 200);
            echo "<input type='number' name='rtc_words_per_minute' value='" . esc_attr($value) . "' min='50' />";
        },
        'rtc_settings',
        'rtc_settings_section'
    );

    // Champ : position
    add_settings_field(
        'rtc_position',
        'Position du compteur',
        function () {
            $value = get_option('rtc_position', 'before');
            echo "<select name='rtc_position'>
                    <option value='before'" . selected($value, 'before', false) . ">Avant le contenu</option>
                    <option value='after'" . selected($value, 'after', false) . ">Après le contenu</option>
                  </select>";
        },
        'rtc_settings',
        'rtc_settings_section'
    );

    // Champ : texte personnalisé
    add_settings_field(
        'rtc_intro_text',
        'Texte affiché avant le temps de lecture',
        function () {
            $value = get_option('rtc_intro_text', '⏱️ Temps de lecture estimé :');
            echo "<input type='text' name='rtc_intro_text' value='" . esc_attr($value) . "' size='50' />";
        },
        'rtc_settings',
        'rtc_settings_section'
    );
}
add_action('admin_init', 'rtc_settings_init');

function rtc_enqueue_styles() {
    wp_enqueue_style('rtc-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'rtc_enqueue_styles');

function rtc_load_textdomain() {
    load_plugin_textdomain('reading-time-counter', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'rtc_load_textdomain');
