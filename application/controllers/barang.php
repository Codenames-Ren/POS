<?php
class Barang extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Model_barang');
        $this->load->model('Model_kategori');
        $this->load->library('pagination');
        chek_session();
    }

    function index() {
        $config['base_url'] = site_url('barang/index');
        $config['per_page'] = 8;
        
        $keyword = $this->input->get('keyword');
        
        if ($keyword) {
            $config['total_rows'] = $this->Model_barang->count_search($keyword);
        } else {
            $config['total_rows'] = $this->Model_barang->count_all();
        }
        
        $config['full_tag_open'] = '<ul class="pagination pagination-sm" style="margin: 0;">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['use_page_numbers'] = FALSE;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        if ($keyword) {
            $data['record'] = $this->Model_barang->cariData($keyword, $config['per_page'], $offset);
        } else {
            $data['record'] = $this->Model_barang->tampildata($config['per_page'], $offset);
        }
        
        $data['pagination'] = $this->pagination->create_links();
        $data['start_number'] = $offset + 1;

        $this->template->load('template', 'barang/lihat_data', $data);
    }

    function post() {
        if (isset($_POST['submit'])) {
            $data = array(
                'nama_barang'  => $this->input->post('nama_barang', true),
                'harga' => $this->input->post('harga', true),
                'kategori_id'  => $this->input->post('kategori_id', true)
            );

            $this->Model_barang->post($data);
            redirect('barang');
        } else {
            $data['kategori'] = $this->Model_kategori->tampildata()->result();
            $this->template->load('template', 'barang/form_input', $data);
        }
    }

    function edit() {
        if (isset($_POST['submit'])) {
            $id = $this->input->post('id_barang', true);
            $data = array(
                'nama_barang'  => $this->input->post('nama_barang', true),
                'harga'        => $this->input->post('harga', true),
                'kategori_id'  => $this->input->post('kategori_id', true) 
            );

            $this->Model_barang->edit($id, $data);
            redirect('barang');
        } else {
            $id = $this->uri->segment(3);
            $data['record'] = $this->Model_barang->get_one($id)->row_array();
            $data['kategori'] = $this->Model_kategori->tampildata()->result();
            $this->template->load('template', 'barang/form_edit', $data);
        }
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->Model_barang->delete($id);
        redirect('barang');
    }
}