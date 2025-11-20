<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Tambah Data Operator</small>
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

            <?= form_open('operator/post', ['id' => 'formOperator']); ?>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <button type="submit" name="submit" value="1" class="btn btn-primary btn-sm">Simpan</button>
                <?= anchor('operator','Kembali',['class'=>'btn btn-danger btn-sm']); ?>

            <?= form_close(); ?>

        </div>
    </div>
</div>