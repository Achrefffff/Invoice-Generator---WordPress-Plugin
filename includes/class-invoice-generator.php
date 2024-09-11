<?php

class Invoice_Generator {

    public function generate_pdf($client_name, $client_email, $amount, $description, $currency, $tva) {
        // recup  info de l'entreprise
        $company_name = get_option('invoice_company_name', 'Nom de l\'entreprise');
        $company_phone = get_option('invoice_company_phone', 'Téléphone');
        $company_email = get_option('invoice_company_email', 'Email');
        $company_website = get_option('invoice_company_website', 'Site web');

        // nouvelle instance de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Couleurs et marges
        $pdf->SetFillColor(230, 230, 230); // Couleur de fond pour les titres
        $pdf->SetDrawColor(180, 180, 180); // Couleur des bordures
        $pdf->SetMargins(10, 10, 10);

        
        $pdf->Image(INVOICE_GEN_PLUGIN_DIR . '/assets/img/developer.jpg', 10, 10, 40);

        // Informations sur l'entreprise
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode($company_name), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 6, utf8_decode($company_phone), 0, 1, 'R');
        $pdf->Cell(0, 6, utf8_decode($company_email), 0, 1, 'R');
        $pdf->Cell(0, 6, utf8_decode($company_website), 0, 1, 'R');
        $pdf->Ln(10);

        
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, 50, 200, 50);
        $pdf->Ln(10);

        // Titre de la facture
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(0, 12, utf8_decode('FACTURE'), 0, 1, 'C', true);
        $pdf->Ln(10);

        // Informations sur le client
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 8, utf8_decode('Client : ') . utf8_decode($client_name), 0, 1);
        $pdf->Cell(100, 8, utf8_decode('Email : ') . utf8_decode($client_email), 0, 1);
        $pdf->Ln(5);

        // Détails de la facture 
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(100, 10, utf8_decode('Description'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, utf8_decode('Montant'), 1, 0, 'C', true);
        $pdf->Cell(40, 10, utf8_decode('TVA (%)'), 1, 1, 'C', true);

        
        $pdf->SetFont('Arial', '', 12);
        
        
        $y_before = $pdf->GetY();  
        $pdf->MultiCell(100, 10, utf8_decode($description), 1);

        $y_after = $pdf->GetY();  

        $pdf->SetXY(110, $y_before);  

        // Cellule pour le montant et la TVA, alignée avec la hauteur de la description
        $pdf->Cell(40, ($y_after - $y_before), number_format($amount, 2) . ' ' . utf8_decode($currency), 1);
        $pdf->Cell(40, ($y_after - $y_before), number_format($tva, 2) . '%', 1, 1);
        
        // Calcul du total avec TVA
        $total = $amount + ($amount * $tva / 100);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, utf8_decode('Montant total (TTC)'), 1, 0);
        $pdf->Cell(80, 10, number_format($total, 2) . ' ' . utf8_decode($currency), 1, 1, 'R');
        $pdf->Ln(10);

        // ajaout  note en bas
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 10, utf8_decode('Merci pour votre confiance. Veuillez nous contacter pour toute question concernant cette facture.'));
        $pdf->Ln(5);

        // enregistrement du fichier PDF
        $upload_dir = wp_upload_dir();
        $pdf_dir = $upload_dir['basedir'] . '/factures/';
        if (!file_exists($pdf_dir)) {
            mkdir($pdf_dir, 0755, true);
        }

        // Générer un nom unique pour le fichier PDF
        $pdf_filename = 'facture-' . time() . '.pdf';
        $pdf_path = $pdf_dir . $pdf_filename;
        $pdf->Output('F', $pdf_path);

        global $wpdb;
        $table_name = $wpdb->prefix . 'invoices';
        $wpdb->insert(
            $table_name,
            array(
                'client_name' => $client_name,
                'client_email' => $client_email,
                'amount' => $amount,
                'currency' => $currency,
                'tva' => $tva,
                'description' => $description,
                'pdf_path' => $pdf_path
            )
        );

        return $upload_dir['baseurl'] . '/factures/' . $pdf_filename;
    }
}
