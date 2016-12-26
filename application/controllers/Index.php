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
    }

    public function elearning_suites() {
        $page = $this->index_model->get_elearning_suites_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function news() {
        $page = 'News ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function faq() {
        $page = 'FAQ ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function testimonials() {
        $page = 'Testimonials ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function policies() {
        $page = 'Policies ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function subscribe() {
        $page = 'Subscribe ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function about() {
        $page = 'About ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function contact() {
        $page = 'Contact ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function terms() {
        $page = 'Terms ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function privacy() {
        $page = 'Privacy ...';
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

}
