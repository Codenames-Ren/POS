<?php
class Kategori extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Model_kategori');
        chek_session();
    }

    function index() {
        $data['record'] = $this->Model_kategori->tampildata();
        $this->template->load('template', 'kategori/lihat_data', $data);
    }

    function post() {
        if (isset($_POST['submit'])) {
            $nama = $this->input->post('nama_kategori', true);
            $data = ['nama_kategori' => $nama];
            $this->Model_kategori->post($data);
            redirect('kategori');
        } else {
            $this->template->load('template', 'kategori/form_input');
        }
    }

    function edit() {
        if (isset($_POST['submit'])) {
            $id   = $this->input->post('kategori_id', true);
            $nama = $this->input->post('nama_kategori', true);
            $data = ['nama_kategori' => $nama];
            $this->Model_kategori->edit($id, $data);
            redirect('kategori');
        } else {
            $id = $this->uri->segment(3);
            $data['record'] = $this->Model_kategori->get_one($id)->row_array();
            $this->template->load('template', 'kategori/form_edit', $data);
        }
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->Model_kategori->delete($id);
        redirect('kategori');
    }
}
