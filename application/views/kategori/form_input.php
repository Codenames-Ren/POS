<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Tambah Kategori Barang</small>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= form_open('kategori/post'); ?>
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" placeholder="Masukkan nama kategori..." required>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <?= anchor('kategori', 'Kembali', ['class'=>'btn btn-danger btn-sm']); ?>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
