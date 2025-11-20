<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            Laporan Transaksi
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <!-- Alert -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <!-- FORM FILTER -->
                <?php echo form_open('transaksi/laporan'); ?>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" 
                                   name="tanggal1" 
                                   class="form-control"
                                   value="<?= isset($tanggal1) ? $tanggal1 : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" 
                                   name="tanggal2" 
                                   class="form-control"
                                   value="<?= isset($tanggal2) ? $tanggal2 : ''; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" name="submit" value="1" class="btn btn-primary btn-block">
                            <i class="fa fa-search"></i> Filter
                        </button>
                    </div>
                </div>

                <?php echo form_close(); ?>

                <!-- FILTER -->
                <?php if (isset($is_filtered) && $is_filtered): ?>
                    <div class="alert alert-info" style="margin-top: 15px;">
                        <strong>Filter Aktif:</strong> 
                        Menampilkan data dari <strong><?= date('d/m/Y', strtotime($tanggal1)); ?></strong> 
                        sampai <strong><?= date('d/m/Y', strtotime($tanggal2)); ?></strong>
                        <a href="<?= site_url('transaksi/reset_filter'); ?>" class="btn btn-xs btn-warning pull-right">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Operator</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 
                        if ($record->num_rows() > 0):
                            $no = 1; 
                            $grand_total = 0;
                            foreach ($record->result() as $r): 
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($r->tanggal_transaksi)); ?></td>
                                <td><?= $r->nama_customer; ?></td>
                                <td><?= $r->nama_lengkap; ?></td>
                                <td class="text-right">Rp <?= number_format($r->total, 0, ',', '.'); ?></td>
                            </tr>
                        <?php 
                            $grand_total += $r->total;
                            endforeach; 
                        ?>

                            <tr class="info">
                                <th colspan="4" class="text-right">GRAND TOTAL</th>
                                <th class="text-right">Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
                            </tr>

                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    <em>Tidak ada data transaksi 
                                    <?php if (isset($is_filtered) && $is_filtered): ?>
                                        pada periode yang dipilih
                                    <?php endif; ?>
                                    </em>
                                </td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>

                <?php if ($record->num_rows() > 0): ?>
                <div style="margin-top: 15px;">
                    <a href="<?php echo base_url('transaksi/excel'); ?>" 
                       class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </a>

                    <a href="<?php echo base_url('transaksi/pdf'); ?>" 
                       class="btn btn-danger btn-sm" 
                       target="_blank">
                        <i class="fa fa-file-pdf-o"></i> Export PDF
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>