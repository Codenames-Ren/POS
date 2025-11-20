<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Edit Data Barang</small>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel-body">
            <?= form_open('barang/edit'); ?>
                <input type="hidden" name="id_barang" value="<?= $record['barang_id']; ?>">

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang"
                        value="<?= $record['nama_barang']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga Barang</label>
                    <input type="number" class="form-control" name="harga"
                        value="<?= $record['harga']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?= $k->kategori_id; ?>"
                                <?= ($record['kategori_id'] == $k->kategori_id) ? 'selected' : ''; ?>>
                                <?= $k->nama_kategori; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button>
                <?= anchor('barang', 'Kembali', ['class' => 'btn btn-danger btn-sm']); ?>
            <?= form_close(); ?>
        </div>
    </div>
</div>
