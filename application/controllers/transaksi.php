<?php
class Transaksi extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(['model_barang','model_transaksi']);
        chek_session();
    }

    function index()
    {
        $action = $this->input->post('action');

        if ($action === 'add')
        {
            if (!$this->session->userdata('session_cart')) {
                $this->session->set_userdata('session_cart', uniqid('trx_', true));
            }

            $this->model_transaksi->simpan_barang();
            redirect('transaksi');
        }

        $data['barang'] = $this->model_barang->tampildata();
        $data['detail'] = $this->model_transaksi->tampilkan_detail_transaksi()->result();

        $this->template->load('template','transaksi/form_transaksi', $data);
    }

    function hapusitem()
    {
        $id = $this->uri->segment(3);
        $this->model_transaksi->hapusitem($id);
        redirect('transaksi');
    }

    public function batal()
    {
        $session_cart = $this->session->userdata('session_cart');

        if ($session_cart) {
            $this->db->where('session_cart', $session_cart);
            $this->db->delete('transaksi_detail');
        }

        $this->session->set_flashdata('success', 'Keranjang berhasil dikosongkan.');
        redirect('transaksi');
    }

    function selesai_belanja()
    {
        $nama_customer = $this->input->post('nama_customer');

        if (!$nama_customer) {
            $this->session->set_flashdata('error', 'Nama customer wajib diisi.');
            redirect('transaksi');
        }

        $session_cart = $this->session->userdata('session_cart');

        $cek_cart = $this->db->get_where('transaksi_detail', [
            'session_cart' => $session_cart,
            'status'       => '0'
        ])->num_rows();

        if ($cek_cart == 0) {
            $this->session->set_flashdata('error', 'Keranjang masih kosong! Tambahkan minimal 1 barang.');
            redirect('transaksi');
        }

        $this->model_transaksi->selesai_belanja($nama_customer);

        $this->session->set_flashdata('success', 'Transaksi berhasil diselesaikan.');

        redirect('transaksi');
    }

    function laporan()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            $tanggal1 = $this->input->post('tanggal1', true);
            $tanggal2 = $this->input->post('tanggal2', true);

            if (empty($tanggal1) || empty($tanggal2)) {
                $this->session->set_flashdata('error', 'Tanggal harus diisi semua!');
                redirect('transaksi/laporan', 'refresh');
                return;
            }

            if (strtotime($tanggal1) > strtotime($tanggal2)) {
                $this->session->set_flashdata('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
                redirect('transaksi/laporan', 'refresh');
                return;
            }

            $this->session->set_userdata([
                'laporan_tanggal1' => $tanggal1,
                'laporan_tanggal2' => $tanggal2
            ]);

            $data['record'] = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
            $data['tanggal1'] = $tanggal1;
            $data['tanggal2'] = $tanggal2;
            $data['is_filtered'] = true;
        }
        else
        {
            if ($this->session->userdata('laporan_tanggal1') && 
                $this->session->userdata('laporan_tanggal2'))
            {
                $tanggal1 = $this->session->userdata('laporan_tanggal1');
                $tanggal2 = $this->session->userdata('laporan_tanggal2');

                $data['record'] = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
                $data['tanggal1'] = $tanggal1;
                $data['tanggal2'] = $tanggal2;
                $data['is_filtered'] = true;
            }
            else
            {
                $data['record'] = $this->model_transaksi->laporan_default();
                $data['is_filtered'] = false;
            }
        }

        $this->template->load('template','transaksi/laporan', $data);
    }

    function reset_filter()
    {
        $this->session->unset_userdata('laporan_tanggal1');
        $this->session->unset_userdata('laporan_tanggal2');
        
        redirect('transaksi/laporan', 'refresh');
    }

    function excel()
    {
        if (ob_get_length()) ob_end_clean();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=laporan_transaksi_".date('Ymd_His').".xls");

        $tanggal1 = $this->session->userdata('laporan_tanggal1');
        $tanggal2 = $this->session->userdata('laporan_tanggal2');

        if ($tanggal1 && $tanggal2) {
            $data = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
            $periode = "Periode: ".date('d/m/Y', strtotime($tanggal1))." - ".date('d/m/Y', strtotime($tanggal2));
        } else {
            $data = $this->model_transaksi->laporan_default();
            $periode = "Semua Transaksi";
        }

        echo "<table border='1'>
            <tr>
                <th colspan='5' style='text-align:center; font-size:14pt;'>
                    <b>LAPORAN TRANSAKSI</b>
                </th>
            </tr>
            <tr>
                <th colspan='5' style='text-align:center;'>
                    {$periode}
                </th>
            </tr>
            <tr>
                <th style='background-color:#4CAF50; color:white;'>No</th>
                <th style='background-color:#4CAF50; color:white;'>Tanggal</th>
                <th style='background-color:#4CAF50; color:white;'>Nama Customer</th>
                <th style='background-color:#4CAF50; color:white;'>Operator</th>
                <th style='background-color:#4CAF50; color:white;'>Total Transaksi</th>
            </tr>";

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r) {
            echo "<tr>
                <td>{$no}</td>
                <td>".date('d/m/Y', strtotime($r->tanggal_transaksi))."</td>
                <td>{$r->nama_customer}</td>
                <td>{$r->nama_lengkap}</td>
                <td style='text-align:right;'>Rp ".number_format($r->total, 0, ',', '.')."</td>
            </tr>";
            $grand_total += $r->total;
            $no++;
        }

        echo "<tr style='background-color:#f0f0f0;'>
            <th colspan='4' style='text-align:right;'>GRAND TOTAL</th>
            <th style='text-align:right;'>Rp ".number_format($grand_total, 0, ',', '.')."</th>
        </tr>";

        echo "</table>";
        exit;
    }

    function pdf()
    {
        $this->load->library('cfpdf');
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();

        $tanggal1 = $this->session->userdata('laporan_tanggal1');
        $tanggal2 = $this->session->userdata('laporan_tanggal2');

        if ($tanggal1 && $tanggal2) {
            $data = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
            $periode = date('d/m/Y', strtotime($tanggal1))." - ".date('d/m/Y', strtotime($tanggal2));
        } else {
            $data = $this->model_transaksi->laporan_default();
            $periode = "Semua Transaksi";
        }

        // Header
        $pdf->SetFont('Helvetica','B',16);
        $pdf->Cell(0, 10, 'LAPORAN TRANSAKSI', 0, 1, 'C');
        
        $pdf->SetFont('Helvetica','',10);
        $pdf->Cell(0, 7, 'Periode: '.$periode, 0, 1, 'C');
        $pdf->Cell(0, 3, '', 0, 1);

        // Table Header
        $pdf->SetFont('Helvetica','B',10);
        $pdf->SetFillColor(76, 175, 80); // Green
        $pdf->SetTextColor(255, 255, 255); // White
        
        $pdf->Cell(10, 7, 'No', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Tanggal', 1, 0, 'C', true);
        $pdf->Cell(50, 7, 'Nama Customer', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Operator', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'Total', 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Helvetica','',9);

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r)
        {
            $pdf->Cell(10, 6, $no, 1, 0, 'C');
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($r->tanggal_transaksi)), 1, 0, 'C');
            $pdf->Cell(50, 6, $r->nama_customer, 1, 0);
            $pdf->Cell(40, 6, $r->nama_lengkap, 1, 0);
            $pdf->Cell(35, 6, 'Rp '.number_format($r->total, 0, ',', '.'), 1, 1, 'R');

            $grand_total += $r->total;
            $no++;
        }

        $pdf->SetFont('Helvetica','B',10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(130, 7, 'GRAND TOTAL', 1, 0, 'R', true);
        $pdf->Cell(35, 7, 'Rp '.number_format($grand_total, 0, ',', '.'), 1, 1, 'R', true);

        $pdf->Output();
    }
}
?>