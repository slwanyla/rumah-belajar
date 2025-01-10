<?php
    if (!$isLogin) {
        header('Location:index.php');
    }
?>
<div class="row mb-4" style="margin-top:-90px">
    <?php foreach($kls_saya->getData($session->get('id_pengguna')) as $row){
        $materi   = $detail->getMateri($row->id); 
        foreach($materi as $row2){
            $materiID[] = $row2->id;
        }   
    ?>
    <div class="col-lg-4 mb-4">
        <div class="card">
            <img class="card-img-top" src="master/assets/img/<?= $row->gambar ?>" width="100%" height="230">
            <div class="card-body">
                <h5 class="card-title"><?= $row->judul ?></h5>
                <p class="card-text"><?= substr($row->deskripsi, 0, 115).'...' ?></p>
            </div>
            <div class="card-body">
                <a href="materi.php?kelas=<?= $row->id ?>&materi=<?= $materiID[0] ?>" target="blank" class="btn btn-info card-link btn-block">Belajar Sekarang</a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>