<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Data Barang</small>
        </h2>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= anchor('barang/post', 'Tambah Barang', ['class' => 'btn btn-danger btn-sm']); ?>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <?= $pagination; ?>
                    </div>
                    <div class="col-md-6">
                        <form method="get" action="<?= site_url('barang'); ?>" 
                            class="form-inline pull-right">
                            <div class="form-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Cari nama atau kategori..."
                                    value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                            <a href="<?= site_url('barang'); ?>" class="btn btn-default btn-sm">Reset</a>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($record->num_rows() > 0):
                                foreach ($record->result() as $r): 
                            ?>
                            <tr>
                                <td><?= $start_number++; ?></td>
                                <td><?= $r->nama_barang; ?></td>
                                <td>Rp <?= number_format($r->harga, 0, ',', '.'); ?></td>
                                <td><?= $r->nama_kategori; ?></td>
                                <td>
                                    <?= anchor('barang/edit/'.$r->barang_id, 'Edit', ['class' => 'btn btn-primary btn-xs']); ?> |
                                    <?= anchor('barang/delete/'.$r->barang_id, 'Delete', [
                                        'class' => 'btn btn-danger btn-xs',
                                        'onclick' => "return confirm('Yakin ingin menghapus data ini?')"
                                    ]); ?>
                                </td>
                            </tr>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>