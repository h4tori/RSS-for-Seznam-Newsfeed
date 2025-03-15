<?php
/**
 * Plugin Name: RSS pro Seznam Newsfeed
 * Description: Vytváří vlastní RSS výstup na adrese /rss_newsfeed_seznam zobrazující posledních 20 příspěvků s featured image jako <enclosure> a popisem z excerptu nebo prvních 3 vět z <p>. Obsahuje administraci s výběrem kategorií a nastavením výpisu.
 * Version: 1.1
 * Author: Tomáš Rohlena / SEOTEST.CZ / Webmint s.r.o.
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Registrace rewrite pravidla
add_action('init', 'szn_rewrite_rule');
function szn_rewrite_rule() {
    $custom_path = trim(get_option('szn_rss_custom_path', 'rss_newsfeed_seznam'), '/');
    add_rewrite_rule("^{$custom_path}/?$", 'index.php?rss_newsfeed_seznam=1', 'top');
}

// Přidání query var
add_filter('query_vars', function ($vars) {
    $vars[] = 'rss_newsfeed_seznam';
    return $vars;
});

// Stránka s nastavením
add_action('admin_menu', function () {
    add_options_page('RSS Newsfeed Seznam', 'RSS Newsfeed Seznam', 'manage_options', 'rss_newsfeed_seznam', 'szn_settings_page');
});

function szn_settings_page() {
    if (isset($_POST['szn_save_settings'])) {
        update_option('szn_rss_use_excerpt', isset($_POST['szn_rss_use_excerpt']) ? 1 : 0);
        update_option('szn_rss_include_categories', array_map('intval', (array)($_POST['szn_rss_include_categories'] ?? [])));
        update_option('szn_rss_exclude_categories', array_map('intval', (array)($_POST['szn_rss_exclude_categories'] ?? [])));
        update_option('szn_rss_exclude_posts', sanitize_text_field($_POST['szn_rss_exclude_posts']));
        update_option('szn_rss_sentence_count', max(1, intval($_POST['szn_rss_sentence_count'])));
        update_option('szn_rss_custom_path', sanitize_title($_POST['szn_rss_custom_path']));
				flush_rewrite_rules();
        echo '<div class="updated"><p>Nastavení uloženo.</p></div>';
    }

    $use_excerpt = get_option('szn_rss_use_excerpt', 1);
    $include_cats = get_option('szn_rss_include_categories', []);
    $exclude_cats = get_option('szn_rss_exclude_categories', []);
    $exclude_posts = get_option('szn_rss_exclude_posts', '');
    $sentence_count = get_option('szn_rss_sentence_count', 3);
    $custom_path = get_option('szn_rss_custom_path', 'rss_newsfeed_seznam');
    $categories = get_categories(['hide_empty' => false]);
    ?>
    <div class="wrap">
        <h1>RSS Seznam Newsfeed - autor: Tomáš Rohlena / Seotest.cz / Webmint s.r.o.</h1>
        <p>Plugin je primárně určen pro vytvoření RSS feedu pro Seznam Newsfeed,tak aby splňoval parametry požadované firmou Seznam.</p>
        	<p>Výstup najdete na adrese: <a href="<?= get_site_url() . '/' . esc_attr($custom_path) . '/'; ?>" target="_blank"><?= get_site_url() . '/' . esc_attr($custom_path) . '/'; ?></a></p>
      
        	 <h1>Nastavení</h1>
        <form method="post">
            <table class="form-table">
            
                <tr><th>Adresa výstupu (např. rss_newsfeed_seznam)</th><td><input type="text" name="szn_rss_custom_path" value="<?= esc_attr($custom_path) ?>"></td></tr>

                <tr><th colspan="2"><label><input type="checkbox" name="szn_rss_use_excerpt" value="1" <?php checked($use_excerpt); ?>> Preferovat Excerpt (úryvek) článku (jinak se bude brát část obsahu)</label></th></tr>
                <tr><th>Počet vět z obsahu do popisu článku (standardně 3)</th><td><input type="number" name="szn_rss_sentence_count" value="<?= esc_attr($sentence_count) ?>" min="1" style="width:100px"></td></tr>
                <tr><th>Zahrnout kategorie (pokud není vybráno tak vše)</th><td>
                  
                    <select name="szn_rss_include_categories[]" multiple size="8" style="width:300px;">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->term_id ?>" <?php selected(in_array($cat->term_id, $include_cats)); ?>><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                      <button type="button" onclick="document.querySelectorAll('[name=\'szn_rss_include_categories[]\'] option').forEach(opt => opt.selected=false);">Odznačit vše</button>
                </td></tr>
                <tr><th>Vynechat kategorie</th><td>
                    
                    <select name="szn_rss_exclude_categories[]" multiple size="8" style="width:300px;">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->term_id ?>" <?php selected(in_array($cat->term_id, $exclude_cats)); ?>><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                    <button type="button" onclick="document.querySelectorAll('[name=\'szn_rss_exclude_categories[]\'] option').forEach(opt => opt.selected=false);">Odznačit vše</button>
                </td></tr>
                <tr><th>ID příspěvků k vyloučení (čárkami oddělené)</th><td><input type="text" name="szn_rss_exclude_posts" value="<?= esc_attr($exclude_posts) ?>" style="width:100%"></td></tr>
            </table>
            <?php submit_button('Uložit nastavení', 'primary', 'szn_save_settings'); ?>
        </form>
    </div>
<?php }

// RSS výstup
add_action('template_redirect', function () {
    if (get_query_var('rss_newsfeed_seznam') == 1) {
        header('Content-Type: application/rss+xml; charset=UTF-8');

        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<rss version=\"2.0\">\n";
        echo "<channel>\n";
        echo "<title>" . get_bloginfo('name') . "</title>\n";
        echo "<link>" . get_site_url() . "</link>\n";
        echo "<description>" . get_bloginfo('description') . "</description>\n";
        echo "<language>cs</language>\n";
        echo "<lastBuildDate>" . date(DATE_RSS) . "</lastBuildDate>\n";
        echo "<copyright>" . get_bloginfo('name') . "</copyright>\n";

        $include = get_option('szn_rss_include_categories', []);
        $exclude = get_option('szn_rss_exclude_categories', []);
        $exclude_posts = array_filter(array_map('intval', explode(',', get_option('szn_rss_exclude_posts', ''))));
        $use_excerpt = get_option('szn_rss_use_excerpt', 1);
        $sentence_count = max(1, intval(get_option('szn_rss_sentence_count', 3)));

        $args = [
            'numberposts' => 20,
            'post_status' => 'publish',
            'post__not_in' => $exclude_posts
        ];
        if (!empty($include)) $args['category__in'] = $include;
        if (!empty($exclude)) $args['category__not_in'] = $exclude;

        $posts = get_posts($args);

        foreach ($posts as $post) {
            setup_postdata($post);
            $title = get_the_title($post);
            $link = get_permalink($post);
            $guid = $link;
            $pubDate = get_the_date(DATE_RSS, $post);

            $image_url = '';
            if (has_post_thumbnail($post)) {
                $img = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'full');
                if ($img) $image_url = $img[0];
            }

            if ($use_excerpt && has_excerpt($post)) {
                $excerpt = get_the_excerpt($post);
            } else {
                $content = wp_strip_all_tags(apply_filters('the_content', $post->post_content));
                preg_match('/<p>(.*?)<\/p>/is', apply_filters('the_content', $post->post_content), $match);
                $first_par = isset($match[1]) ? wp_strip_all_tags($match[1]) : '';
                $sentences = preg_split('/(?<=[.!?])\s+/', $first_par);
                $excerpt = implode(' ', array_slice($sentences, 0, $sentence_count));
            }

            echo "<item>\n";
            echo "<title>" . esc_html($title) . "</title>\n";
            echo "<link>" . esc_url($link) . "</link>\n";
            echo "<description><![CDATA[" . esc_html($excerpt) . "]]></description>\n";
            echo "<guid>" . esc_url($guid) . "</guid>\n";
            if ($image_url) echo "<enclosure url=\"$image_url\" type=\"image/jpeg\" />\n";
            echo "<pubDate>" . esc_html(date(DATE_RSS, strtotime($post->post_date))) . "</pubDate>\n";
            echo "</item>\n";
        }
        wp_reset_postdata();
        echo "</channel>\n</rss>";
        exit;
    }
});

register_activation_hook(__FILE__, 'szn_flush_rewrite_rules');
function szn_flush_rewrite_rules() {
    szn_rewrite_rule(); flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});
