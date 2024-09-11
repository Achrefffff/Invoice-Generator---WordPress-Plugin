<?php
/*
Plugin Name: Professional Invoice Generator-PIG
Description: Un plugin pour générer et gérer des factures en PDF avec envoi automatique par e-mail.
Version: 1.1
Author: Achref CHOUIKH
*/

define('INVOICE_GEN_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Charger la bibliothèque FPDF et la classe principale
require_once INVOICE_GEN_PLUGIN_DIR . '/lib/fpdf.php';
require_once INVOICE_GEN_PLUGIN_DIR . '/includes/class-invoice-generator.php';

//  la table pour stocker les factures lors de l'activation du plugin
register_activation_hook(__FILE__, 'create_invoice_table');
function create_invoice_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'invoices';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        client_name varchar(255) NOT NULL,
        client_email varchar(255) NOT NULL,
        amount float NOT NULL,
        currency varchar(10) NOT NULL,
        tva float NOT NULL,
        description text NOT NULL,
        pdf_path varchar(255) NOT NULL,
        date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// ajout  pages au menu du tableau de bord WordPress
add_action('admin_menu', 'invoice_generator_menu');
function invoice_generator_menu() {
    add_menu_page(
        'Paramètres de Facturation',
        'Paramètres Facturation',
        'manage_options',
        'invoice-generator-settings',
        'invoice_generator_settings_page',
        'dashicons-admin-settings',
        6
    );

    add_submenu_page(
        'invoice-generator-settings',
        'Générateur de Factures',
        'Générer Facture',
        'manage_options',
        'invoice-generator-form',
        'invoice_generator_form_page'
    );
}


add_action('admin_enqueue_scripts', 'enqueue_invoice_generator_styles');
function enqueue_invoice_generator_styles() {
    wp_enqueue_style('invoice-generator-style', plugins_url('/assets/css/style.css', __FILE__));
}

// Page des paramètres de facturation
function invoice_generator_settings_page() {
    include_once INVOICE_GEN_PLUGIN_DIR . '/includes/admin-page-settings.php';
}

// Page de gestion des factures
function invoice_generator_form_page() {
    include_once INVOICE_GEN_PLUGIN_DIR . '/includes/admin-page-form.php';
}

