<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Data Kategori Barang</small>
        </h2>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= anchor('kategori/post', 'Tambah Kategori', ['class'=>'btn btn-danger btn-sm']); ?>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            foreach ($record->result() as $r): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $r->nama_kategori; ?></td>
                                <td>
                                    <?= anchor('kategori/edit/'.$r->kategori_id, 'Edit', ['class'=>'btn btn-primary btn-xs']); ?> |
                                    <?= anchor('kategori/delete/'.$r->kategori_id, 'Delete', [
                                        'class'=>'btn btn-danger btn-xs',
                                        'onclick'=>"return confirm('Yakin ingin menghapus kategori ini?');"
                                    ]); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
