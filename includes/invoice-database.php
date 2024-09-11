<?php

function create_invoice_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'invoices';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        client_name varchar(255) NOT NULL,
        client_email varchar(255) NOT NULL,
        amount float NOT NULL,
        pdf_path varchar(255) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function save_invoice($client_name, $client_email, $amount, $pdf_path) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'invoices';

    $wpdb->insert(
        $table_name,
        [
            'client_name' => $client_name,
            'client_email' => $client_email,
            'amount' => $amount,
            'pdf_path' => $pdf_path
        ]
    );
}
