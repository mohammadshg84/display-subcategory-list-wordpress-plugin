<?php
/*
Plugin Name: نمایش زیرمجموعه‌های دسته‌بندی
Description: نمایش لیست زیرمجموعه‌های یک دسته‌بندی خاص در برگه با شورت‌کات
Version: 1.1
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
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    // ساخت خروجی HTML
    $output = '<div class="subcategories-container">';
    
    if (!empty($subcategories)) {
        foreach ($subcategories as $subcategory) {
            $output .= '<div class="subcategory-item">';
            
            // دریافت تصویر شاخص (فرض بر استفاده از تابع سفارشی یا افزونه)
            $category_image = '';
            if (function_exists('get_field')) { // پشتیبانی از ACF
                $category_image = get_field('category_image', 'category_' . $subcategory->term_id);
            } elseif (function_exists('z_taxonomy_image_url')) { // پشتیبانی از افزونه Category Featured Image
                $category_image = z_taxonomy_image_url($subcategory->term_id);
            }
            
            if ($category_image) {
                $output .= '<div class="subcategory-image">';
                $output .= '<img src="' . esc_url($category_image) . '" alt="' . esc_attr($subcategory->name) . '">';
                $output .= '</div>';
            }
            
            $output .= '<div class="subcategory-content">';
            $output .= '<h3><a href="' . get_category_link($subcategory->term_id) . '">';
            $output .= esc_html($subcategory->name).'</a></h3>';
            
            // اضافه کردن توضیحات دسته‌بندی
            if (!empty($subcategory->description)) {
                $output .= '<p class="subcategory-description">' . wp_kses_post($subcategory->description) . '</p>';
            }
            
            $output .= '</div>'; // پایان subcategory-content
            $output .= '</div>'; // پایان subcategory-item
        }
    } else {
        $output .= '<p>هیچ زیرمجموعه‌ای یافت نشد.</p>';
    }
    
    $output .= '</div>';

    return $output;
}

// ثبت شورت‌کد
add_shortcode('subcategories', 'display_subcategories_shortcode');

// اضافه کردن استایل
function subcategories_styles() {
    echo '
    <style>
        .subcategories-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
        }
        .subcategory-item {
            width: 100%;
            max-width: 300px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #fff;
        }
        .subcategory-image img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .subcategory-content h3 {
            margin: 10px 0;
            font-size: 1.2em;
        }
        .subcategory-content a {
            text-decoration: none;
            color: #0073aa;
        }
        .subcategory-content a:hover {
            text-decoration: underline;
        }
        .subcategory-description {
            font-size: 0.9em;
            color: #555;
        }
    </style>';
}
add_action('wp_head', 'subcategories_styles');