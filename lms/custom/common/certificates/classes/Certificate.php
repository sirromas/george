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
    public $report_css;

    function __construct() {
        parent::__construct();
        $this->cert_css = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/common/certificates/cert.css";
        $this->bootstrap_css = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/bootstrap.min.css";
        $this->path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/common/certificates";
        $this->report_css = "http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/practices/custom.css";
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

    function create_practice_summary_report($practiceid, $data) {
        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->report_css);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($data, 2);
        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/practices/$practiceid/report.pdf";
        $pdf->Output($path, 'F');
    }

    function create_user_certificate($courseid, $data, $userid) {
        $dir = $this->path . "/$userid/$courseid";
        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->cert_css);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($data, 2);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . "/certificate.pdf";
        $pdf->Output($path, 'F');
        return true;
    }

}
