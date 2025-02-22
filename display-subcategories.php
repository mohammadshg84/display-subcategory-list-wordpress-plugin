<?php
/*
Plugin Name: نمایش زیرمجموعه‌های دسته‌بندی
Description: نمایش لیست زیرمجموعه‌های یک دسته‌بندی خاص در برگه با شورت‌کات
Version: 1.0
Author: محمدصادق حسن‌پور
Author URI: https://hspr.ir
License: GPL2
*/

// تابع اصلی برای دریافت و نمایش زیرمجموعه‌ها
function display_subcategories_shortcode($atts) {
    // تنظیم پیش‌فرض پارامترها
    $atts = shortcode_atts(array(
        'parent_id' => 0, // آی‌دی دسته‌بندی والد
    ), $atts);

    // دریافت زیرمجموعه‌ها
    $subcategories = get_categories(array(
        'parent' => intval($atts['parent_id']),
        'hide_empty' => false, // نمایش دسته‌بندی‌های خالی هم انجام بشه
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    // ساخت خروجی HTML
    $output = '<ul class="subcategories-list">';
    
    if (!empty($subcategories)) {
        foreach ($subcategories as $subcategory) {
            $output .= '<li>';
            $output .= '<a href="' . get_category_link($subcategory->term_id) . '">';
            $output .= esc_html($subcategory->name);
            $output .= '</a> (' . $subcategory->count . ')';
            $output .= '</li>';
        }
    } else {
        $output .= '<li>هیچ زیرمجموعه‌ای یافت نشد.</li>';
    }
    
    $output .= '</ul>';

    return $output;
}

// ثبت شورت‌کد
add_shortcode('subcategories', 'display_subcategories_shortcode');

// اضافه کردن استایل ساده
function subcategories_styles() {
    echo '
    <style>
        .subcategories-list {
            list-style-type: none;
            padding: 0;
        }
        .subcategories-list li {
            margin: 5px 0;
        }
        .subcategories-list a {
            text-decoration: none;
            color: #0073aa;
        }
        .subcategories-list a:hover {
            text-decoration: underline;
        }
    </style>';
}
add_action('wp_head', 'subcategories_styles');