<?php
/*
Plugin Name: OopsyBuy Product Import Basic
Description: Basic product importer for AliExpress, file uploads, URLs, and pricing setup.
Version: 1.0.0
Author: Stephan Johnson / OopsyBuy
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/aliexpress-import.php';
require_once plugin_dir_path(__FILE__) . 'includes/file-import.php';
require_once plugin_dir_path(__FILE__) . 'includes/url-import.php';
require_once plugin_dir_path(__FILE__) . 'includes/pricing-setup.php';
require_once plugin_dir_path(__FILE__) . 'includes/product-search.php';
require_once plugin_dir_path(__FILE__) . 'includes/upgrade.php';
require_once plugin_dir_path(__FILE__) . 'includes/data-storage.php';

// Activation hook
function oopsybuy_product_import_basic_activate() {
    oopsybuy_product_import_basic_create_tables();
}
register_activation_hook(__FILE__, 'oopsybuy_product_import_basic_activate');

// Deactivation hook
function oopsybuy_product_import_basic_deactivate() {
    // Perform any necessary actions on plugin deactivation
}
register_deactivation_hook(__FILE__, 'oopsybuy_product_import_basic_deactivate');

// Add admin menu
function oopsybuy_product_import_basic_add_admin_menu() {
    add_menu_page(
        'OopsyBuy Product Import Basic',
        'OopsyBuy Import Basic',
        'manage_options',
        'oopsybuy-product-import-basic',
        'oopsybuy_product_import_basic_dashboard',
        'dashicons-cart'
    );
}
add_action('admin_menu', 'oopsybuy_product_import_basic_add_admin_menu');

// Dashboard function with tabs
function oopsybuy_product_import_basic_dashboard() {
    ?>
    <div class="wrap oopsybuy-dashboard">
        <h1>OopsyBuy Product Import Basic</h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=oopsybuy-product-import-basic&tab=aliexpress" class="nav-tab <?php echo ($_GET['tab'] == 'aliexpress' || !isset($_GET['tab'])) ? 'nav-tab-active' : ''; ?>">AliExpress Import</a>
            <a href="?page=oopsybuy-product-import-basic&tab=file" class="nav-tab <?php echo ($_GET['tab'] == 'file') ? 'nav-tab-active' : ''; ?>">File Import</a>
            <a href="?page=oopsybuy-product-import-basic&tab=url" class="nav-tab <?php echo ($_GET['tab'] == 'url') ? 'nav-tab-active' : ''; ?>">URL Import</a>
            <a href="?page=oopsybuy-product-import-basic&tab=pricing" class="nav-tab <?php echo ($_GET['tab'] == 'pricing') ? 'nav-tab-active' : ''; ?>">Pricing Setup</a>
            <a href="?page=oopsybuy-product-import-basic&tab=search" class="nav-tab <?php echo ($_GET['tab'] == 'search') ? 'nav-tab-active' : ''; ?>">Product Search</a>
            <a href="?page=oopsybuy-product-import-basic&tab=upgrade" class="nav-tab <?php echo ($_GET['tab'] == 'upgrade') ? 'nav-tab-active' : ''; ?>">Upgrade</a>
        </h2>

        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'aliexpress';
        switch ($tab) {
            case 'aliexpress':
                include plugin_dir_path(__FILE__) . 'includes/aliexpress-import.php';
                break;
            case 'file':
                include plugin_dir_path(__FILE__) . 'includes/file-import.php';
                break;
            case 'url':
                include plugin_dir_path(__FILE__) . 'includes/url-import.php';
                break;
            case 'pricing':
                include plugin_dir_path(__FILE__) . 'includes/pricing-setup.php';
                break;
            case 'search':
                include plugin_dir_path(__FILE__) . 'includes/product-search.php';
                break;
            case 'upgrade':
                include plugin_dir_path(__FILE__) . 'includes/upgrade.php';
                break;
            default:
                include plugin_dir_path(__FILE__) . 'includes/aliexpress-import.php';
                break;
        }
        ?>
    </div>
    <?php
}

// AJAX handler for search suggestions
function oopsybuy_get_search_suggestions() {
    $searchTerm = sanitize_text_field($_POST['searchTerm']);
    // Replace with your search suggestion logic
    $suggestions = ['Suggestion 1: ' . $searchTerm, 'Suggestion 2: ' . $searchTerm, 'Suggestion 3: ' . $searchTerm];
    wp_send_json($suggestions);
}
add_action('wp_ajax_oopsybuy_get_search_suggestions', 'oopsybuy_get_search_suggestions');

// AJAX handler for product search
function oopsybuy_perform_product_search() {
    $searchTerm = sanitize_text_field($_POST['searchTerm']);
    $imageData = isset($_POST['imageData']) ? $_POST['imageData'] : null;

    if ($imageData) {
        // Handle image-based search
        // Replace with your image search logic
        $results = [
            ['title' => 'Image Search Result 1', 'price' => '$29.99', 'source' => 'Amazon', 'link' => '#'],
            ['title' => 'Image Search Result 2', 'price' => '$34.99', 'source' => 'eBay', 'link' => '#'],
        ];
    } else {
        // Handle text-based search
        // Replace with your product search logic
        $results = [
            ['title' => 'Product 1: ' . $searchTerm, 'price' => '$19.99', 'source' => 'AliExpress', 'link' => '#'],
            ['title' => 'Product 2: ' . $searchTerm, 'price' => '$24.99', 'source' => 'Walmart', 'link' => '#'],
            ['title' => 'Product 3: ' . $searchTerm, 'price' => '$21.99', 'source' => 'eBay', 'link' => '#'],
        ];
    }

    wp_send_json($results);
}
add_action('wp_ajax_oopsybuy_perform_product_search', 'oopsybuy_perform_product_search');
add_action('wp_ajax_nopriv_oopsybuy_perform_product_search', 'oopsybuy_perform_product_search');
?>