<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Edit Data Operator</small>
        </h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel-body">

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?= form_open('operator/edit/'.$record['id_operator'], ['id' => 'formEditOperator']); ?>

                <input type="hidden" name="id" value="<?= $record['id_operator']; ?>">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama"
                           value="<?= $record['nama_lengkap']; ?>">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username"
                           value="<?= $record['username']; ?>">
                </div>

                <div class="form-group">
                    <label>Password (biarkan kosong jika tidak diubah)</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <button type="submit" name="submit" value="1" class="btn btn-primary btn-sm">Update</button>
                <?= anchor('operator','Kembali',['class'=>'btn btn-danger btn-sm']); ?>
            
            <?= form_close(); ?>

        </div>
    </div>
</div>