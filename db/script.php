<?php

// ini_set('display_errors', 1);

require __DIR__ . '/../PHPMailer/class.phpmailer.php';

$db = new PDO('mysql:dbname=baneco;host=127.0.0.1', 'admin', 'CoboserDB@3431#');

$sql = 'SELECT
      id_cobranza,
      id_emision,
      COUNT(*) AS total,
      "AP" AS producto
    FROM s_ap_cobranza
    GROUP BY id_emision
    HAVING (COUNT(*) > 1 AND COUNT(*) < 12) OR COUNT(*) > 12
    LIMIT 0, 22
    UNION
    SELECT
      id_cobranza,
      id_emision,
      COUNT(*) AS total,
      "VI" AS producto
    FROM s_vi_cobranza
    GROUP BY id_emision
    HAVING (COUNT(*) > 1 AND COUNT(*) < 12) OR COUNT(*) > 12;';

$sth = $db->prepare($sql);
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);

$msg = '';

ob_start();
?>
    <table style="font-size: 11px; font-weight: bold;" border="1" cellpadding="3">
        <thead>
        <tr>
            <td>Producto</td>
            <td>ID Cobranza</td>
            <td>ID Emision</td>
            <td>Registos</td>
        </tr>
        </thead>
        <tbody>
        <?php if (count($result) > 0): ?>
        <?php foreach ($result as $data): ?>
            <tr>
                <td><?= $data['producto'] ;?></td>
                <td><?= $data['id_cobranza'] ;?></td>
                <td><?= $data['id_emision'] ;?></td>
                <td><?= $data['total'] ;?></td>
            </tr>
        <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
<?php

$msg = ob_get_clean();

$mail = new PHPMailer();

$mail->Host     = 'mmamani@coboser.com';
$mail->From     = 'mmamani@coboser.com';
$mail->FromName = 'Sudamericana';
$mail->Subject  = 'Baneco - Registros duplicados Cobranzas - ' . date('d/m/Y');

$mail->addAddress('emontano@sudseguros.com', 'Ernesto Montaño');
$mail->addAddress('mmamani@coboser.com', 'Miguel Mamani');
$mail->addAddress('cchalco@coboser.com', 'Miguel Mamani');
$mail->addAddress('jvera@coboser.com', 'Juan Pablo Vera');

$mail->Body     = $msg;
$mail->AltBody  = $msg;

if ($mail->send()) {
    var_dump('OK');
} else {
    var_dump('Error');
}

?>
