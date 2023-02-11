<?php
include_once('common_bootstrap.php');
include_once('bootstrap.php');
include_once('config.php');
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();
/* $mpdf->WriteHTML('<h1>Hello world!</h1>');
$mpdf->Output(); */

include_once 'tplInvoice.php';
//$html = file_get_contents('tplInvoice.php');;

$path = (getenv('MPDF_ROOT')) ? getenv('MPDF_ROOT') : __DIR__;
require_once $path . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'margin_left' => 20,
	'margin_right' => 15,
	'margin_top' => 30,
	'margin_bottom' => 25,
	'margin_header' => 10,
	'margin_footer' => 10
]);

$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Notice");
$mpdf->SetAuthor("Nano");
$mpdf->SetWatermarkText("eNotice Ninja");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($html);
$mpdf->Output('invoice.pdf','F');
$mpdf->Output();
?>
<?php
