<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            Detail Transaksi Customer
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Detail Transaksi Customer: <?= htmlspecialchars($nama_customer) ?></strong>
            </div>

            <div class="panel-body">

                <!-- Info Periode -->
                <div class="alert alert-info">
                    <strong>Periode:</strong> <?= htmlspecialchars($periode) ?>
                </div>

                <!-- Tombol kembali -->
                <a href="<?= base_url('transaksi/laporan'); ?>" class="btn btn-warning btn-sm" style="margin-bottom: 10px;">
                    <i class="fa fa-arrow-left"></i> Kembali ke Laporan
                </a>

                <div class="table-responsive" style="margin-top: 10px;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="info">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php
                        $no = 1;
                        $grand = 0.0;

                        if (isset($record) && $record->num_rows() > 0):
                            foreach ($record->result() as $row):

                                $qty = isset($row->qty) ? (float)$row->qty : 0;
                                $harga = isset($row->harga) ? (float)$row->harga : 0;
                                $subtotal = isset($row->subtotal) ? (float)$row->subtotal : ($qty * $harga);
                                $nama_barang = isset($row->nama_barang) ? $row->nama_barang : '-';

                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($nama_barang); ?></td>
                                <td><?= number_format($qty, 0, ',', '.'); ?></td>
                                <td class="text-right">Rp <?= number_format($harga, 0, ',', '.'); ?></td>
                                <td class="text-right">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>

                        <?php
                                $grand += $subtotal;
                            endforeach;
                        else:
                        ?>

                            <tr>
                                <td colspan="5" class="text-center"><em>Tidak ada detail transaksi pada periode ini.</em></td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                        <tfoot>
                            <tr class="info">
                                <th colspan="4" class="text-right">TOTAL</th>
                                <th class="text-right">Rp <?= number_format($grand, 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
                <?php if ($record->num_rows() > 0): ?>
                <div style="margin-top: 15px;">
                    <a href="<?= base_url('transaksi/excel_detail/'.$first->transaksi_id); ?>"  
                    class="btn btn-success btn-sm" target="_blank">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </a>

                    <a href="<?= base_url('transaksi/pdf_detail/'.$first->transaksi_id); ?>"  
                    class="btn btn-danger btn-sm" target="_blank">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>
