<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Transaksi</small>
        </h2>
    </div>
</div>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo form_open('transaksi', ['class' => 'form-horizontal']); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nama Produk</label>
                        <div class="col-sm-10">
                            <select name="barang" class="form-control" required>
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($barang->result() as $b): ?>
                                    <option value="<?= $b->nama_barang; ?>">
                                        <?= $b->nama_barang; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" name="qty" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="action" value="add" class="btn btn-primary btn-sm">
                                Tambah Produk
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo form_open('transaksi/selesai_belanja', ['class' => 'form-horizontal']); ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nama Customer</label>
                        <div class="col-sm-10">
                            <input type="text" name="nama_customer" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm">
                                Checkout
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php $no = 1; $total = 0; ?>
                        <?php foreach ($detail as $r): ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <?= $r->nama_barang ?> -
                                    <?= anchor('transaksi/hapusitem/'.$r->t_detail_id, 'Hapus', ['style' => 'color:red;']) ?>
                                </td>
                                <td><?= $r->qty ?></td>
                                <td>Rp. <?= number_format($r->harga, 0) ?></td>
                                <td>Rp. <?= number_format($r->qty * $r->harga, 0) ?></td>
                            </tr>
                        <?php 
                            $total += ($r->qty * $r->harga);
                            $no++;
                        endforeach; ?>

                            <tr class="gradeA">
                                <td colspan="4">T O T A L</td>
                                <td>Rp. <?= number_format($total, 0); ?></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
