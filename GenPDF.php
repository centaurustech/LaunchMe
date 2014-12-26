<?php
require('fpdf17/fpdf.php');

include('db_inc.php');
$db = new PDO($server . ':host=' . $host . ';dbname=' . $base, $user, $pass);
$idp = $_GET['id'];
$request = 'SELECT * FROM projet WHERE ID=' . $idp . ';';
$result = $db->query($request);

if ($result = $result->fetch()) {
    $name = $result['Nom'];
    $description = $result['Description'];
    $goal = $result['SommeNecessaire'];
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,10,$name,0,0,'C');
$pdf->Ln(30);
$pdf->SetFont('Arial','',16);
$pdf->MultiCell(0,10,$description,0,2);
$pdf->Ln(20);
$pdf->SetFont('Arial','I',16);
$pdf->Cell(0,10,$goal.' euros',0,2);
$pdf->Output();
?>