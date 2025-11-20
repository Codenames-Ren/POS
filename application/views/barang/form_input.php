<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Tambah Data Barang</small>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= form_open('barang/post'); ?>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" name="nama_barang" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k->kategori_id; ?>">
                                    <?= $k->nama_kategori; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <?= anchor('barang', 'Kembali', ['class' => 'btn btn-danger btn-sm']) ?>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
