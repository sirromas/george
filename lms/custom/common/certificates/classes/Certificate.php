<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/pdf/html2pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class Certificate extends Utils {

    public $bootstrap_css;
    public $cert_css;
    public $path;

    function __construct() {
        parent::__construct();
        $this->cert_css = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/certificates/cert.css";
        $this->bootstrap_css = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/bootstrap.min.css";
        $this->path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/certificates";
    }

    function create_report_pdf_file($data) {
        $userid = $this->user->id;
        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->bootstrap_css);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($data, 2);
        $dir = $this->path . "/$userid";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . "/report.pdf";
        $pdf->Output($path, 'F');
    }

    function create_user_certificate($courseid, $data) {
        $userid = $this->user->id;


        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->cert_css);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($data, 2);


        $dir = $this->path . "/$userid/$courseid";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . "/certificate.pdf";
        $pdf->Output($path, 'F');

        /*
         *  
          try {
          $html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', 0);
          $html2pdf->pdf->SetDisplayMode('fullpage');

          $html2pdf->writeHTML($data);
          $html2pdf->Output($path);
          } // end try
          catch (Html2PdfException $e) {
          $formatter = new ExceptionFormatter($e);
          echo $formatter->getHtmlMessage();
          }
         * 
         */
    }

}
