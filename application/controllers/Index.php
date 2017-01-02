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
        $this->load->helper('url');
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

    function suite_detailes() {
        $id = $this->uri->segment(3);
        $page = $this->index_model->get_suite_detailes($id);
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function fullnews() {
        $id = $this->uri->segment(3);
        $page = $this->index_model->get_fullnews_page($id);
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function news() {
        $page = $this->index_model->get_news_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function faq() {
        $page = $this->index_model->get_faq_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function testimonials() {
        $page = $this->index_model->get_testimonials_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function policies() {
        $page = $this->index_model->get_policy_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function subscribe() {
        $page = $this->index_model->get_subscribe_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function about() {
        $page = $this->index_model->get_about_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function contact() {
        $page = $this->index_model->get_contact_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function get_campus_data() {
        $data = $this->index_model->get_campus_data();
        echo $data;
    }

    public function send_contact_request() {
        $contact = $this->input->post('contact');
        $page = $this->index_model->add_contact_request(json_decode($contact));
        $data = array('page' => $page);
        $this->load->view('content', $data);
    }

    public function terms() {
        $page = $this->index_model->get_terms_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    public function privacy() {
        $page = $this->index_model->get_privacy_policy_page();
        $data = array('page' => $page);
        $this->load->view('header');
        $this->load->view('content', $data);
        $this->load->view('footer');
    }

    function subscribe_user() {
        $subs = $this->input->post('subs');
        $subs_data = json_decode($subs);
        $page = $this->index_model->subscribe_user($subs_data);
        $data = array('page' => $page);
        $this->load->view('content', $data);
    }

}
