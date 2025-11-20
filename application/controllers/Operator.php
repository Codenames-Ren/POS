<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_operator', 'operator');
        $this->load->library('pagination');
        chek_session();
    }

    public function index()
    {
        $keyword = $this->input->get('keyword');

        $config['base_url'] = site_url('operator/index');
        $config['per_page'] = 8;
        $config['uri_segment'] = 3;
        $config['reuse_query_string'] = TRUE;

        $config['total_rows'] = $keyword ?
            $this->operator->count_search($keyword) :
            $this->operator->count_all();

        $config['full_tag_open'] = '<ul class="pagination pagination-sm" style="margin:0">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['record'] = $keyword ?
            $this->operator->cariData($keyword, $config['per_page'], $offset) :
            $this->operator->tampildata($config['per_page'], $offset);

        $data['pagination'] = $this->pagination->create_links();
        $data['start_number'] = $offset + 1;

        $this->template->load('template', 'operator/lihat_data', $data);
    }

    public function post()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $nama     = $this->input->post('nama', true);
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);

            // Validasi
            if (empty($nama) || empty($username) || empty($password)) {
                $this->session->set_flashdata('error', 'Semua field wajib diisi.');
                redirect('operator/post', 'refresh');
                return;
            }

            if ($this->operator->is_username_exist($username)) {
                $this->session->set_flashdata('error', 'Username sudah dipakai.');
                redirect('operator/post', 'refresh');
                return;
            }

            $data = [
                'nama_lengkap' => $nama,
                'username'     => $username,
                'password'     => md5($password),
                'last_login'   => date('Y-m-d')
            ];

            $insert = $this->db->insert('operator', $data);

            if ($insert) {
                $this->session->set_flashdata('success', 'Data operator berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data operator.');
            }

            redirect('operator', 'refresh');
            return;
        }

        $this->template->load('template', 'operator/form_input');
    }

    public function edit($id = null)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $id       = $this->input->post('id', true);
            $nama     = $this->input->post('nama', true);
            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);

            if (empty($id) || !is_numeric($id)) {
                $this->session->set_flashdata('error', 'ID operator tidak valid.');
                redirect('operator', 'refresh');
                return;
            }

            if (empty($nama) || empty($username)) {
                $this->session->set_flashdata('error', 'Nama & username wajib diisi.');
                redirect('operator/edit/'.$id, 'refresh');
                return;
            }

            if ($this->operator->is_username_exist($username, $id)) {
                $this->session->set_flashdata('error', 'Username sudah digunakan.');
                redirect('operator/edit/'.$id, 'refresh');
                return;
            }

            $data = [
                'nama_lengkap' => $nama,
                'username'     => $username,
            ];

            if (!empty($password)) {
                $data['password'] = md5($password);
            }

            $this->db->where('id_operator', $id);
            $update = $this->db->update('operator', $data);

            if ($update) {
                $this->session->set_flashdata('success', 'Data operator berhasil diupdate.');
            } else {
                $this->session->set_flashdata('error', 'Gagal update data operator.');
            }

            redirect('operator', 'refresh');
            return;
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'ID operator tidak valid.');
            redirect('operator', 'refresh');
            return;
        }

        $record = $this->operator->get_one($id);

        if ($record->num_rows() == 0) {
            $this->session->set_flashdata('error', 'Operator tidak ditemukan.');
            redirect('operator', 'refresh');
            return;
        }

        $data['record'] = $record->row_array();
        $this->template->load('template', 'operator/form_edit', $data);
    }

    public function delete($id)
    {
        if (!empty($id) && is_numeric($id)) {
            $this->db->where('id_operator', $id);
            $delete = $this->db->delete('operator');

            if ($delete) {
                $this->session->set_flashdata('success', 'Data operator berhasil dihapus.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data operator.');
            }
        } else {
            $this->session->set_flashdata('error', 'ID operator tidak valid.');
        }

        redirect('operator', 'refresh');
    }
}