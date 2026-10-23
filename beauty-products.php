<?php
/**
 * Plugin Name: Beauty Products Dynamic
 * Description: Hiển thị danh sách sản phẩm mỹ phẩm.
 * Version: 1.0.0
 */

function beauty_products_dynamic_styles() {
    wp_register_style('beauty-products-dynamic', false);
    wp_enqueue_style('beauty-products-dynamic');

    $css = '
        .beauty-products-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
            margin: 28px 0;
            align-items: stretch;
        }

        .beauty-products-grid .beauty-product-card {
            height: 100%;
            min-width: 0;
            padding: 0 0 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .beauty-products-grid .beauty-product-card a,
        .beauty-products-grid .beauty-product-card a:link,
        .beauty-products-grid .beauty-product-card a:visited,
        .beauty-products-grid .beauty-product-card a:hover,
        .beauty-products-grid .beauty-product-card a:focus,
        .beauty-products-grid .beauty-product-card a:active {
            text-decoration: none !important;
            border-bottom: none !important;
            box-shadow: none !important;
            background-image: none !important;
        }

        .beauty-products-grid .beauty-product-card > a:first-child {
            display: block;
            width: 100%;
            overflow: hidden;
            border-radius: 8px;
        }

        .beauty-products-grid .beauty-product-card img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
            margin-bottom: 0;
            transition: transform 0.3s ease;
        }

        .beauty-products-grid .beauty-product-card > a:first-child:hover img {
            transform: scale(1.03);
        }

        .beauty-products-grid .beauty-product-card h3 {
            font-size: 22px;
            line-height: 1.4;
            margin: 18px 0 10px;
        }

        .beauty-products-grid .beauty-product-card h3 a,
        .beauty-products-grid .beauty-product-card h3 a:visited,
        .beauty-products-grid .beauty-product-card h3 a:hover,
        .beauty-products-grid .beauty-product-card h3 a:focus {
            color: #303030 !important;
            text-decoration: none !important;
        }

        .beauty-products-grid .beauty-product-description {
    min-height: 54px;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
    overflow-wrap: anywhere;
    word-break: normal;
    font-size: 15px;
    line-height: 1.8;
    margin-bottom: 16px;
}

.beauty-products-grid .beauty-product-card h3 {
    width: 100%;
    overflow-wrap: anywhere;
    font-size: 22px;
    line-height: 1.5;
    margin: 18px 0 10px;
}

        .beauty-products-grid .beauty-product-card .beauty-product-button,
        .beauty-products-grid .beauty-product-card .beauty-product-button:link,
        .beauty-products-grid .beauty-product-card .beauty-product-button:visited,
        .beauty-products-grid .beauty-product-card .beauty-product-button:hover,
        .beauty-products-grid .beauty-product-card .beauty-product-button:focus {
            display: inline-block;
            width: fit-content;
            margin-top: auto;
            padding: 12px 22px;
            background: #F48FB1;
            color: #ffffff !important;
            text-decoration: none !important;
            border: none !important;
            border-radius: 5px;
            box-shadow: none !important;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .beauty-products-grid .beauty-product-card .beauty-product-button:hover {
            background: #e87896;
            color: #ffffff !important;
        }

        @media (max-width: 700px) {
            .beauty-products-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .beauty-products-grid .beauty-product-card h3 {
                font-size: 20px;
            }
        }
    ';

    wp_add_inline_style('beauty-products-dynamic', $css);
}
add_action('wp_enqueue_scripts', 'beauty_products_dynamic_styles');

add_action('wp_enqueue_scripts', function () {
    $css_path = ABSPATH . 'custom-css/beauty.css';
    $css_url  = home_url('/custom-css/beauty.css');

    if (file_exists($css_path)) {
        wp_enqueue_style(
            'glow-beauty-typography',
            $css_url,
            array(),
            filemtime($css_path)
        );
    }
});

function beauty_products_dynamic_shortcode() {
    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'post__not_in'   => array(1),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $products = new WP_Query($args);

    if (!$products->have_posts()) {
        return '<p>Hiện chưa có sản phẩm nào</p>';
    }

    ob_start();

    echo '<div class="beauty-products-grid">';

    while ($products->have_posts()) {
        $products->the_post();

        echo '<article class="beauty-product-card">';

        if (has_post_thumbnail()) {
            echo '<a href="' . esc_url(get_permalink()) . '" aria-label="' . esc_attr(get_the_title()) . '">';
            echo get_the_post_thumbnail(get_the_ID(), 'large');
            echo '</a>';
        }

        echo '<h3><a href="' . esc_url(get_permalink()) . '">';
        echo esc_html(get_the_title());
        echo '</a></h3>';

        echo '<div class="beauty-product-description">';
        echo esc_html(wp_trim_words(get_the_excerpt(), 22, ' [...]'));
        echo '</div>';

      echo '<a class="beauty-product-button" href="' . esc_url(get_permalink()) . '">' . esc_html('Tìm hiểu thêm') . '</a>';

        echo '</article>';
    }

    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('beauty_products', 'beauty_products_dynamic_shortcode');
