<div class="row">
    <div class="col-md-12">
        <h2 class="page-header">
            POS (Point of Sale) <small>Data Operator</small>
        </h2>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= anchor('operator/post', 'Tambah Operator', ['class'=>'btn btn-danger btn-sm']) ?>
            </div>
            <div class="panel-body">

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-6">
                        <?= isset($pagination) ? $pagination : ''; ?>
                    </div>

                    <div class="col-md-6">
                        <form method="get" action="<?= site_url('operator'); ?>" 
                              class="form-inline pull-right">
                            <div class="form-group">
                                <input type="text"
                                       name="keyword"
                                       class="form-control"
                                       placeholder="Cari nama / username..."
                                       value="<?= $this->input->get('keyword'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                            <a href="<?= site_url('operator'); ?>" class="btn btn-default btn-sm">Reset</a>
                        </form>
                    </div>
                </div>

                <!-- TABEL DATA -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Login Terakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if ($record->num_rows() > 0):
                            $no = isset($start_number) ? $start_number : 1;
                            foreach ($record->result() as $r): ?>
                            <tr class="gradeU">
                                <td><?= $no; ?></td>
                                <td><?= $r->nama_lengkap; ?></td>
                                <td><?= $r->username; ?></td>
                                <td><?= $r->last_login; ?></td>
                                <td class="center">
                                    <?= anchor('operator/edit/'.$r->id_operator,'Edit',[
                                        'class' => 'btn btn-primary btn-xs'
                                    ]); ?> |
                                    <?= anchor('operator/delete/'.$r->id_operator,'Delete',[
                                        'class' => 'btn btn-danger btn-xs',
                                        'onclick' => "return confirm('Yakin ingin menghapus operator ini?')"
                                    ]); ?>
                                </td>
                            </tr>
                        <?php 
                                $no++;
                            endforeach;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data operator</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
