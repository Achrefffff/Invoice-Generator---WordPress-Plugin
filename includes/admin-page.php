<?php
if (isset($_POST['generate_invoice'])) {
    $client_name = sanitize_text_field($_POST['client_name']);
    $client_email = sanitize_email($_POST['client_email']);
    $amount = sanitize_text_field($_POST['amount']);
    $description = sanitize_textarea_field($_POST['description']);
    $currency = sanitize_text_field($_POST['currency']);
    $tva = floatval($_POST['tva']);

    $invoice = new Invoice_Generator();
    $pdf_path = $invoice->generate_pdf($client_name, $client_email, $amount, $description, $currency, $tva);

    $to = $client_email;
    $subject = 'Votre facture';
    $body = 'Bonjour ' . $client_name . ',<br>Veuillez trouver ci-joint votre facture.';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachments = array($pdf_path);

    wp_mail($to, $subject, $body, $headers, $attachments);

    echo '<div class="notice notice-success is-dismissible"><p>Facture générée et envoyée avec succès ! <a href="' . esc_url($pdf_path) . '" target="_blank">Télécharger la Facture</a></p></div>';
}
?>

<div class="wrap invoice-form">
    <h1>Créer une nouvelle facture</h1>
    <form method="post">
        <label for="client_name">Nom du client :</label>
        <input type="text" id="client_name" name="client_name" required><br><br>

        <label for="client_email">Email du client :</label>
        <input type="email" id="client_email" name="client_email" required><br><br>

        <label for="amount">Montant :</label>
        <input type="number" id="amount" name="amount" required><br><br>

        <label for="currency">Devise :</label>
        <select id="currency" name="currency">
            <option value="EUR">EUR</option>
            <option value="USD">USD</option>
            <option value="GBP">GBP</option>
        </select><br><br>

        <label for="tva">TVA (%):</label>
        <input type="number" id="tva" name="tva" required><br><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <input type="submit" name="generate_invoice" value="Générer la Facture">
    </form>
</div>
