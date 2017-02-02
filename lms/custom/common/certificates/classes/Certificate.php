<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/classes/Utils.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/common/certificates/pdf/mpdf/mpdf.php';

class Certificate extends Utils {

    public $bootstrap_css;
    public $custom_css;
    public $path;

    function __construct() {
        parent::__construct();
        $this->custom_css = $_SERVER['DOCUMENT_ROOT'] . "/assets/css/custom.css";
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
        $stylesheet = file_get_contents($this->custom_css);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($data, 2);
        $dir = $this->path . "/$userid/$courseid";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . "/certificate.pdf";
        $pdf->Output($path, 'F');
    }

}
