<?php
App::import('Vendor','tcpdf/tcpdf');
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, true);
//$tcpdf = new TCPDF();
$textfont = 'helvetica';
 
$this->Tcpdf->SetAuthor("Axia Merchant Services");
$this->Tcpdf->SetAutoPageBreak(true);
$this->Tcpdf->setPrintHeader(false);
$this->Tcpdf->setPrintFooter(false);
 
$this->Tcpdf->SetTextColor(0, 0, 0);
$this->Tcpdf->SetFont($textfont,'',9);
$this->Tcpdf->setVisibility('print'); 
$this->Tcpdf->AddPage();

//$this->Tcpdf->CheckBox(foo, 1, true);
// create some HTML content
$htmlcontent = <<<EOF
{$testvar}
EOF;
// output the HTML content
$this->Tcpdf->writeHTML($htmlcontent, true, false, true, false, '');
$this->Tcpdf->Output('/tmp/coversheet_' . $data['Application']['id'] . '.pdf', 'F');
?>