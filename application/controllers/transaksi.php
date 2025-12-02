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

    public function laporan_detail($id_transaksi = null)
    {
        if ($id_transaksi === null) {
            show_404();
            return;
        }

        $data['record'] = $this->model_transaksi->laporan_detail($id_transaksi);

        if ($data['record']->num_rows() == 0) {

            $data['first'] = null;
            $data['nama_customer'] = "-";
            $data['periode'] = "Tidak ada data";

        } else {

            $first = $data['record']->row();

            $data['first'] = $first;

            $data['nama_customer'] = $first->nama_customer;
            $data['periode'] = date("d/m/Y", strtotime($first->tanggal_transaksi));
        }

        $this->template->load("template", "transaksi/laporan_detail", $data);
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

        $filename = 'Laporan_Transaksi_'.date('d-M-Y_His').'.xls';
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        $tanggal1 = $this->session->userdata('laporan_tanggal1');
        $tanggal2 = $this->session->userdata('laporan_tanggal2');

        if ($tanggal1 && $tanggal2) {
            $data = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
            $periode = date('d/m/Y', strtotime($tanggal1))." s/d ".date('d/m/Y', strtotime($tanggal2));
        } else {
            $data = $this->model_transaksi->laporan_default();
            $periode = "Semua Periode";
        }

        echo '<table border="1" cellpadding="5" cellspacing="0">';
        
        // Header
        echo '<tr>
            <td colspan="6" align="center" bgcolor="#2c3e50" style="color: white; font-size: 18pt; font-weight: bold;">
                LAPORAN TRANSAKSI PENJUALAN
            </td>
        </tr>';
        
        echo '<tr>
            <td colspan="6" align="center" bgcolor="#34495e" style="color: white; font-size: 11pt;">
                Periode: '.$periode.'
            </td>
        </tr>';
        
        echo '<tr>
            <td colspan="6" align="center" bgcolor="#ecf0f1" style="font-size: 9pt;">
                Dicetak pada: '.date('d F Y, H:i:s').' WIB
            </td>
        </tr>';
        
        echo '<tr><td colspan="6"></td></tr>';

        // Table Header
        echo '<tr>
            <th width="40" bgcolor="#27ae60" style="color: white;">No</th>
            <th width="100" bgcolor="#27ae60" style="color: white;">Tanggal</th>
            <th width="200" bgcolor="#27ae60" style="color: white;">Nama Customer</th>
            <th width="150" bgcolor="#27ae60" style="color: white;">Operator</th>
            <th width="120" bgcolor="#27ae60" style="color: white;">Total Transaksi</th>
            <th width="100" bgcolor="#27ae60" style="color: white;">Status</th>
        </tr>';

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r) {
            $bg_color = ($no % 2 == 0) ? '#f8f9f9' : '#ffffff';
            
            echo '<tr>
                <td align="center" bgcolor="'.$bg_color.'">'.$no.'</td>
                <td align="center" bgcolor="'.$bg_color.'">'.date('d/m/Y', strtotime($r->tanggal_transaksi)).'</td>
                <td bgcolor="'.$bg_color.'">'.strtoupper($r->nama_customer).'</td>
                <td bgcolor="'.$bg_color.'">'.$r->nama_lengkap.'</td>
                <td align="right" bgcolor="'.$bg_color.'">Rp '.number_format($r->total, 0, ',', '.').'</td>
                <td align="center" bgcolor="'.$bg_color.'">Selesai</td>
            </tr>';
            
            $grand_total += $r->total;
            $no++;
        }

        echo '<tr><td colspan="6"></td></tr>';
        
        echo '<tr>
            <td colspan="4" align="right" bgcolor="#d5dbdb" style="font-weight: bold;">TOTAL TRANSAKSI</td>
            <td align="right" bgcolor="#d5dbdb" style="font-weight: bold;">'.($no-1).' Transaksi</td>
            <td bgcolor="#d5dbdb"></td>
        </tr>';
        
        echo '<tr>
            <td colspan="4" align="right" bgcolor="#27ae60" style="color: white; font-weight: bold; font-size: 11pt;">GRAND TOTAL</td>
            <td align="right" bgcolor="#27ae60" style="color: white; font-weight: bold; font-size: 11pt;">Rp '.number_format($grand_total, 0, ',', '.').'</td>
            <td bgcolor="#27ae60"></td>
        </tr>';

        echo '</table>';
        exit;
    }

    function pdf()
    {
        $this->load->library('cfpdf');
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 15);

        $tanggal1 = $this->session->userdata('laporan_tanggal1');
        $tanggal2 = $this->session->userdata('laporan_tanggal2');

        if ($tanggal1 && $tanggal2) {
            $data = $this->model_transaksi->laporan_periode($tanggal1, $tanggal2);
            $periode = date('d/m/Y', strtotime($tanggal1))." s/d ".date('d/m/Y', strtotime($tanggal2));
        } else {
            $data = $this->model_transaksi->laporan_default();
            $periode = "Semua Periode";
        }

        // Header dengan Border
        $pdf->SetFillColor(44, 62, 80); // Dark blue
        $pdf->Rect(10, 10, 190, 25, 'F');
        
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica','B',18);
        $pdf->SetXY(10, 15);
        $pdf->Cell(190, 8, 'LAPORAN TRANSAKSI PENJUALAN', 0, 1, 'C');
        
        $pdf->SetFont('Helvetica','',11);
        $pdf->SetX(10);
        $pdf->Cell(190, 7, 'Periode: '.$periode, 0, 1, 'C');

        // Info tanggal cetak
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetFont('Helvetica','I',8);
        $pdf->SetXY(10, 38);
        $pdf->Cell(190, 5, 'Dicetak pada: '.date('d F Y, H:i:s').' WIB', 0, 1, 'C');

        $pdf->Ln(5);

        $pdf->SetFont('Helvetica','B',9);
        $pdf->SetFillColor(39, 174, 96); // Green
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetDrawColor(34, 153, 84); // Dark green untuk border
        $pdf->SetLineWidth(0.3);
        
        $pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
        $pdf->Cell(28, 8, 'Tanggal', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Nama Customer', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Operator', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Total', 1, 0, 'C', true);
        $pdf->Cell(22, 8, 'Status', 1, 1, 'C', true);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Helvetica','',8);
        $pdf->SetDrawColor(189, 195, 199); // Light gray untuk border

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r)
        {
            // Alternating row colors
            if ($no % 2 == 0) {
                $pdf->SetFillColor(248, 249, 249); // Very light gray
            } else {
                $pdf->SetFillColor(255, 255, 255); // White
            }

            $pdf->Cell(10, 7, $no, 1, 0, 'C', true);
            $pdf->Cell(28, 7, date('d/m/Y', strtotime($r->tanggal_transaksi)), 1, 0, 'C', true);
            
            $nama_customer = (strlen($r->nama_customer) > 28) ? 
                             substr($r->nama_customer, 0, 25).'...' : 
                             $r->nama_customer;
            $pdf->Cell(50, 7, $nama_customer, 1, 0, 'L', true);
            
            $nama_operator = (strlen($r->nama_lengkap) > 25) ? 
                            substr($r->nama_lengkap, 0, 22).'...' : 
                            $r->nama_lengkap;
            $pdf->Cell(45, 7, $nama_operator, 1, 0, 'L', true);
            
            $pdf->Cell(35, 7, 'Rp '.number_format($r->total, 0, ',', '.'), 1, 0, 'R', true);
            $pdf->Cell(22, 7, 'Selesai', 1, 1, 'C', true);

            $grand_total += $r->total;
            $no++;
        }

        // Spacing
        $pdf->Ln(2);

        // Summary row
        $pdf->SetFont('Helvetica','B',9);
        $pdf->SetFillColor(213, 219, 219);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(133, 7, 'TOTAL TRANSAKSI', 1, 0, 'R', true);
        $pdf->Cell(35, 7, ($no-1).' Transaksi', 1, 0, 'C', true);
        $pdf->Cell(22, 7, '', 1, 1, 'C', true);

        // Grand Total
        $pdf->SetFont('Helvetica','B',10);
        $pdf->SetFillColor(39, 174, 96);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(133, 8, 'GRAND TOTAL', 1, 0, 'R', true);
        $pdf->Cell(57, 8, 'Rp '.number_format($grand_total, 0, ',', '.'), 1, 1, 'R', true);

        // Footer
        $pdf->Ln(10);
        $pdf->SetFont('Helvetica','I',8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(190, 5, 'Dokumen ini dicetak otomatis oleh sistem', 0, 1, 'C');

        $pdf->Output('I', 'Laporan_Transaksi_'.date('d-M-Y').'.pdf');
    }

    public function excel_detail($id_transaksi)
    {
        if (ob_get_length()) ob_end_clean();

        $filename = 'Detail_Transaksi_'.$id_transaksi.'_'.date('d-M-Y_His').'.xls';

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        $data = $this->model_transaksi->laporan_detail($id_transaksi);

        if ($data->num_rows() == 0) {
            echo "Tidak ada data transaksi.";
            exit;
        }

        $first = $data->row();

        echo '<table border="1" cellpadding="5" cellspacing="0">';

        // HEADER
        echo '<tr>
            <td colspan="5" align="center" bgcolor="#2c3e50" 
                style="color:white; font-size:18pt; font-weight:bold;">
                DETAIL TRANSAKSI CUSTOMER
            </td>
        </tr>';

        echo '<tr>
            <td colspan="5" align="center" bgcolor="#34495e" 
                style="color:white; font-size:11pt;">
                '.$first->nama_customer.' - Tanggal '.$first->tanggal_transaksi.'
            </td>
        </tr>';

        echo '<tr>
            <td colspan="5" align="center" bgcolor="#ecf0f1" 
                style="font-size:9pt;">
                Dicetak pada: '.date('d F Y, H:i:s').' WIB
            </td>
        </tr>';

        echo '<tr><td colspan="5"></td></tr>';

        // TABLE HEADER
        echo '<tr>
            <th bgcolor="#27ae60" style="color:white;">No</th>
            <th bgcolor="#27ae60" style="color:white;">Nama Barang</th>
            <th bgcolor="#27ae60" style="color:white;">Qty</th>
            <th bgcolor="#27ae60" style="color:white;">Harga</th>
            <th bgcolor="#27ae60" style="color:white;">Subtotal</th>
        </tr>';

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r) {

            $bg = ($no % 2 == 0) ? '#f8f9f9' : '#ffffff';

            echo '<tr>
                <td align="center" bgcolor="'.$bg.'">'.$no.'</td>
                <td bgcolor="'.$bg.'">'.$r->nama_barang.'</td>
                <td align="center" bgcolor="'.$bg.'">'.$r->qty.'</td>
                <td align="right" bgcolor="'.$bg.'">Rp '.number_format($r->harga, 0, ',', '.').'</td>
                <td align="right" bgcolor="'.$bg.'">Rp '.number_format($r->subtotal, 0, ',', '.').'</td>
            </tr>';

            $grand_total += $r->subtotal;
            $no++;
        }

        echo '<tr><td colspan="5"></td></tr>';

        echo '<tr>
            <td colspan="4" align="right" bgcolor="#27ae60" 
                style="color:white; font-weight:bold; font-size:12pt;">
                GRAND TOTAL
            </td>
            <td align="right" bgcolor="#27ae60" 
                style="color:white; font-weight:bold; font-size:12pt;">
                Rp '.number_format($grand_total, 0, ',', '.').'
            </td>
        </tr>';

        echo '</table>';
        exit;
    }

    public function pdf_detail($id_transaksi)
    {
        $this->load->library('cfpdf');
        $pdf = new FPDF('P', 'mm', 'A4');

        $data = $this->model_transaksi->laporan_detail($id_transaksi);

        if ($data->num_rows() == 0) {
            echo "Data tidak ditemukan.";
            return;
        }

        $first = $data->row();

        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 15);

        $pdf->SetFillColor(44, 62, 80);
        $pdf->Rect(10, 10, 190, 25, 'F');

        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Helvetica','B',18);
        $pdf->SetXY(10, 15);
        $pdf->Cell(190, 8, 'DETAIL TRANSAKSI CUSTOMER', 0, 1, 'C');

        $pdf->SetFont('Helvetica','',11);
        $pdf->SetTextColor(230,230,230);
        $pdf->Cell(190, 6, 
            $first->nama_customer.' - Tanggal '.date('d/m/Y', strtotime($first->tanggal_transaksi)), 
            0, 1, 'C');

        $pdf->SetFont('Helvetica','I',8);
        $pdf->SetTextColor(150,150,150);
        $pdf->Cell(190, 5, 
            'Dicetak pada: '.date('d F Y, H:i:s').' WIB', 
            0, 1, 'C');

        $pdf->Ln(5);

        $pdf->SetFont('Helvetica','B',9);
        $pdf->SetFillColor(39,174,96);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetDrawColor(34,153,84);

        $pdf->Cell(10,8,'No',1,0,'C',true);
        $pdf->Cell(65,8,'Nama Barang',1,0,'C',true);
        $pdf->Cell(20,8,'Qty',1,0,'C',true);
        $pdf->Cell(40,8,'Harga',1,0,'C',true);
        $pdf->Cell(45,8,'Subtotal',1,1,'C',true);

        $pdf->SetFont('Helvetica','',8);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(189,195,199);

        $no = 1;
        $grand_total = 0;

        foreach ($data->result() as $r) {

            if ($no % 2 == 0) {
                $pdf->SetFillColor(248,249,249);
            } else {
                $pdf->SetFillColor(255,255,255);
            }

            $pdf->Cell(10,7,$no,1,0,'C',true);
            $pdf->Cell(65,7,$r->nama_barang,1,0,'L',true);
            $pdf->Cell(20,7,$r->qty,1,0,'C',true);
            $pdf->Cell(40,7,'Rp '.number_format($r->harga,0,',','.'),1,0,'R',true);
            $pdf->Cell(45,7,'Rp '.number_format($r->subtotal,0,',','.'),1,1,'R',true);

            $grand_total += $r->subtotal;
            $no++;
        }

        $pdf->Ln(3);
        $pdf->SetFont('Helvetica','B',10);
        $pdf->SetFillColor(39,174,96);
        $pdf->SetTextColor(255,255,255);

        $pdf->Cell(135,8,'GRAND TOTAL',1,0,'R',true);
        $pdf->Cell(45,8,'Rp '.number_format($grand_total,0,',','.'),1,1,'R',true);

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica','I',8);
        $pdf->SetTextColor(120,120,120);
        $pdf->Cell(190,5,'Dokumen ini dicetak otomatis oleh sistem',0,1,'C');

        $pdf->Output('I', 'Detail_Transaksi_'.$id_transaksi.'.pdf');
    }
}
?>