<?php

/**
 * Description of Index
 *
 * @author sirromas
 */
class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('index_model');
    }

    public function index() {
        $page = $this->index_model->get_index_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
        //$this->output->enable_profiler(TRUE);
    }

}
