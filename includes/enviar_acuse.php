<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../includes/config.php';
require '../../includes/phpmailer/PHPMailer.php';
require '../../includes/phpmailer/SMTP.php';
require '../../includes/phpmailer/Exception.php';
require '../../includes/fpdf/fpdf.php';

function enviarAcuse($idPago, $emailDestino, $nombre, $concepto, $monto, $recargo) {
    $total = $monto + $recargo;
    $nombreArchivo = "acuse_pago_{$idPago}.pdf";
    $rutaCarpeta = realpath(__DIR__ . '/../docs/comprobantes');
    if (!$rutaCarpeta) {
        mkdir(__DIR__ . '/../docs/comprobantes', 0755, true);
        $rutaCarpeta = realpath(__DIR__ . '/../docs/comprobantes');
    }
    $rutaCompleta = $rutaCarpeta . DIRECTORY_SEPARATOR . $nombreArchivo;

    //generar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    //logo
    $logoPath = __DIR__ . '/../assets/logo_purple.png';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 80, 10, 50, 40);
    }
    $pdf->Ln(40);
    //encabezado
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Cluster Loto - Acuse de Recibo', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Pago de Mantenimiento', 0, 1, 'C');
    $pdf->Ln(10);
    //fecha
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Fecha: ' . date("d-m-Y"), 0, 1, 'R');
    $pdf->Ln(5);
    //mensaje
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(50, 10, 'Nombre:', 0, 0);
    $pdf->Cell(0, 10, $nombre, 0, 1, 'L', true);

    $pdf->Cell(50, 10, 'Concepto:', 0, 0);
    $pdf->Cell(0, 10, $concepto, 0, 1, 'L');

    $pdf->Cell(50, 10, 'Monto:', 0, 0);
    $pdf->Cell(0, 10, '$' . number_format($monto, 2), 0, 1, 'L', true);

    $pdf->Cell(50, 10, 'Recargo:', 0, 0);
    $pdf->Cell(0, 10, '$' . number_format($recargo, 2), 0, 1, 'L');

    $pdf->Cell(50, 10, 'Total Pagado:', 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '$' . number_format($total, 2), 0, 1, 'L', true);
    $pdf->Ln(15);

    $pdf->SetFont('Arial', 'I', 11);
    $pdf->MultiCell(0, 8, "Gracias por cumplir puntualmente con sus obligaciones de mantenimiento.\nEste comprobante es válido como constancia de pago.", 0, 'C');

    $pdf->Output('F', $rutaCompleta);

    //enviar correo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAIL_USERNAME');
        $mail->Password = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = getenv('MAIL_PORT');

        $mail->setFrom(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));
        $mail->addAddress($emailDestino, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Acuse de recibo - Pago de mantenimiento';

        $mail->Body = "
            <h3>Hola $nombre,</h3>
            <p>Tu pago ha sido verificado correctamente. Se adjunta el comprobante en PDF.</p>
            <ul>
                <li><strong>Concepto:</strong> $concepto</li>
                <li><strong>Monto:</strong> $$monto</li>
                <li><strong>Recargo:</strong> $$recargo</li>
                <li><strong>Total:</strong> $$total</li>
            </ul>
            <p>Gracias por mantenerte al corriente con tus cuotas de mantenimiento.</p>
        ";

        $mail->addAttachment($rutaCompleta, $nombreArchivo);
        $mail->send();

        require_once __DIR__ . '/../models/modeloPagos.php';
        PagoModel::actualizarComprobante($idPago, "comprobantes/$nombreArchivo");

        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        error_log("Exception: " . $e->getMessage());
        return false;
    }
}

function enviarAcuseRechazo($idPago, $emailDestino, $nombre, $concepto, $monto, $recargo) {
    $total = $monto + $recargo;
    $nombreArchivo = "acuse_rechazo_{$idPago}.pdf";
    $rutaCarpeta = realpath(__DIR__ . '/../docs/comprobantes');
    if (!$rutaCarpeta) {
        mkdir(__DIR__ . '/../docs/comprobantes', 0755, true);
        $rutaCarpeta = realpath(__DIR__ . '/../docs/comprobantes');
    }
    $rutaCompleta = $rutaCarpeta . DIRECTORY_SEPARATOR . $nombreArchivo;

    //generar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    //logo
    $logoPath = __DIR__ . '/../assets/logo_purple.png';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 80, 10, 50, 40);
    }
    $pdf->Ln(40);
    //encabezado
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Cluster Loto - Aviso de Rechazo de Pago', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Pago de Mantenimiento No Verificado', 0, 1, 'C');
    $pdf->Ln(10);
    //fecha
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Fecha: ' . date("d-m-Y"), 0, 1, 'R');
    $pdf->Ln(5);
    //intento de pago
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(50, 10, 'Nombre:', 0, 0);
    $pdf->Cell(0, 10, $nombre, 0, 1, 'L', true);

    $pdf->Cell(50, 10, 'Concepto:', 0, 0);
    $pdf->Cell(0, 10, $concepto, 0, 1, 'L');

    $pdf->Cell(50, 10, 'Monto:', 0, 0);
    $pdf->Cell(0, 10, '$' . number_format($monto, 2), 0, 1, 'L', true);

    $pdf->Cell(50, 10, 'Recargo:', 0, 0);
    $pdf->Cell(0, 10, '$' . number_format($recargo, 2), 0, 1, 'L');

    $pdf->Cell(50, 10, 'Total Intentado:', 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '$' . number_format($total, 2), 0, 1, 'L', true);
    $pdf->Ln(15);

    $pdf->SetFont('Arial', 'I', 11);
    $pdf->MultiCell(0, 8, "Tu pago no ha podido ser verificado. Es posible que haya un error en los datos o el comprobante enviado. Por favor revisa tu informacion o contacta a la administracion para resolver el problema.", 0, 'C');

    $pdf->Output('F', $rutaCompleta);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAIL_USERNAME');
        $mail->Password = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = 'tls';
        $mail->Port = getenv('MAIL_PORT');

        $mail->setFrom(getenv('MAIL_FROM'), getenv('MAIL_FROM_NAME'));
        $mail->addAddress($emailDestino, $nombre);
        $mail->isHTML(true);
        $mail->Subject = 'Aviso: Tu pago de mantenimiento fue rechazado';

        $mail->Body = "
            <h3>Hola $nombre,</h3>
            <p>Lamentamos informarte que tu pago con el concepto <strong>$concepto</strong> no ha podido ser verificado.</p>
            <p><strong>Monto:</strong> $$monto<br>
            <strong>Recargo:</strong> $$recargo<br>
            <strong>Total:</strong> $$total</p>
            <p>Te pedimos revisar tu comprobante o ponerte en contacto con administración para resolver este inconveniente.</p>
            <p>Se adjunta un aviso en formato PDF para tu referencia.</p>
        ";

        $mail->addAttachment($rutaCompleta, $nombreArchivo);
        $mail->send();

        // Opcional: registrar archivo de rechazo en DB
        require_once __DIR__ . '/../models/modeloPagos.php';
        PagoModel::actualizarComprobante($idPago, "comprobantes/$nombreArchivo");

        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        error_log("Exception: " . $e->getMessage());
        return false;
    }
}
?>
