<?php
ob_start(); 
$message = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['company_name']) && isset($_POST['company_phone']) && isset($_POST['company_email']) && isset($_POST['company_website'])) {
        
        update_option('invoice_company_name', sanitize_text_field($_POST['company_name']));
        update_option('invoice_company_phone', sanitize_text_field($_POST['company_phone']));
        update_option('invoice_company_email', sanitize_email($_POST['company_email']));
        update_option('invoice_company_website', esc_url($_POST['company_website']));

        
        $message = 'Les informations ont été enregistrées avec succès. Vous pouvez aller à l\'onglet "Générer Facture" pour générer votre facture.';
    }
}

ob_end_clean(); 
?>
<div class="wrap invoice-form">
    <h1>Informations sur l'entreprise</h1>

    <?php if (!empty($message)) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html($message); ?></p>
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="company_name">Nom de l'entreprise :</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo esc_attr(get_option('invoice_company_name')); ?>" required><br><br>

        <label for="company_phone">Numéro de téléphone :</label>
        <input type="text" id="company_phone" name="company_phone" value="<?php echo esc_attr(get_option('invoice_company_phone')); ?>" required><br><br>

        <label for="company_email">Email de l'entreprise :</label>
        <input type="email" id="company_email" name="company_email" value="<?php echo esc_attr(get_option('invoice_company_email')); ?>" required><br><br>

        <label for="company_website">Site web de l'entreprise :</label>
        <input type="text" id="company_website" name="company_website" value="<?php echo esc_attr(get_option('invoice_company_website')); ?>" required><br><br>

        <input type="submit" value="Enregistrer les Informations">
    </form>
</div>
