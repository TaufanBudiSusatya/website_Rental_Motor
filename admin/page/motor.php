<?php
$update = (isset($_GET['action']) AND $_GET['action'] == 'update') ? true : false;
if ($update) {
	$sql = $connection->query("SELECT * FROM motor WHERE id_motor='$_GET[key]'");
	$row = $sql->fetch_assoc();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$err = false;
	$file = $_FILES['gambar']['name'];
	if ($update) {
		if ($file) {
			$x = explode('.', $_FILES['gambar']['name']);
			$file_name = date("dmYHis").".".strtolower(end($x));
			if (! move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/motor/".$file_name)) {
				echo alert("Upload File Gagal!", "?page=motor");
				$err = true;
			}
			@unlink("../assets/img/motor/".$row["gambar"]);
		} else {
			$file_name = $row["gambar"];
		}
	} else {
		if (!$file) {
			echo alert("File gambar tidak ada!", "?page=motor");
			$err = true;
		}
		$x = explode('.', $_FILES['gambar']['name']);
		$file_name = date("dmYHis").".".strtolower(end($x));
		if (! move_uploaded_file($_FILES['gambar']['tmp_name'], "../assets/img/motor/".$file_name)) {
			echo alert("Upload File Gagal!", "?page=motor");
			$err = true;
		}
	}
	if ($update) {
		$sql = "UPDATE motor SET id_jenis='$_POST[id_jenis]', no_motor='$_POST[no_motor]', merk='$_POST[merk]', nama_motor='$_POST[nama_motor]', gambar='$file_name', harga='$_POST[harga]', status='$_POST[status]' WHERE id_motor='$_GET[key]'";
	} else {
		$sql = "INSERT INTO motor VALUES (NULL, '$_POST[id_jenis]', '$_POST[no_motor]', '$_POST[merk]', '$_POST[nama_motor]', '$file_name', '$_POST[harga]', '$_POST[status]')";
	}
	if (!$err) {
	  if ($connection->query($sql)) {
	    echo alert("Berhasil!", "?page=motor");
	  } else {
			echo alert("Gagal!", "?page=motor");
	  }
	}
}
if (isset($_GET['action']) AND $_GET['action'] == 'delete') {
  $connection->query("DELETE FROM motor WHERE id_motor='$_GET[key]'");
	echo alert("Berhasil!", "?page=motor");
}
?>
<div class="row">
	<div class="col-md-4 hidden-print">
	    <div class="panel panel-<?= ($update) ? "warning" : "info" ?>">
	        <div class="panel-heading"><h3 class="text-center"><?= ($update) ? "EDIT" : "TAMBAH" ?></h3></div>
	        <div class="panel-body">
	            <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST" enctype="multipart/form-data">
	                <div class="form-group">
	                    <label for="id_jenis">Jenis</label>
											<select class="form-control" name="id_jenis">
												<option>---</option>
												<?php $query = $connection->query("SELECT * FROM jenis"); while ($data = $query->fetch_assoc()): ?>
													<option value="<?=$data["id_jenis"]?>" <?= (!$update) ?: (($row["id_jenis"] != $data["id_jenis"]) ?: 'selected="on"') ?>><?=$data["nama"]?></option>
												<?php endwhile; ?>
											</select>
	                </div>
	                <div class="form-group">
	                    <label for="no_motor">No Motor</label>
	                    <input type="text" name="no_motor" class="form-control" <?= (!$update) ?: 'value="'.$row["no_motor"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="nama_motor">Nama Motor</label>
	                    <input type="text" name="nama_motor" class="form-control" <?= (!$update) ?: 'value="'.$row["nama_motor"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="merk">Merk</label>
	                    <input type="text" name="merk" class="form-control" <?= (!$update) ?: 'value="'.$row["merk"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="gambar">Gambar</label>
	                    <input type="file" name="gambar" class="form-control">
			                <?php if ($update): ?>
												<span class="help-block">*) Kosongkan jika tidak diubah</span>
											<?php endif; ?>
	                </div>
	                <div class="form-group">
	                    <label for="harga">Harga Sewa</label>
	                    <input type="text" name="harga" class="form-control" <?= (!$update) ?: 'value="'.$row["harga"].'"' ?>>
	                </div>
	                <div class="form-group">
	                    <label for="status">Status</label>
											<select class="form-control" name="status">
												<option>---</option>
												<option value="0" <?= (!$update) ?: (($row["status"] != 0) ?: 'selected="on"') ?>>Tidak Tersedia</option>
												<option value="1" <?= (!$update) ?: (($row["status"] != 1) ?: 'selected="on"') ?>>Tersedia</option>
											</select>
	                </div>
	                <button type="submit" class="btn btn-<?= ($update) ? "warning" : "info" ?> btn-block">Simpan</button>
	                <?php if ($update): ?>
										<a href="?page=motor" class="btn btn-info btn-block">Batal</a>
									<?php endif; ?>
	            </form>
	        </div>
	    </div>
	</div>
	<div class="col-md-8">
	    <div class="panel panel-info">
	        <div class="panel-heading"><h3 class="text-center">DAFTAR MOTOR</h3></div>
	        <div class="panel-body">
	            <table class="table table-condensed">
	                <thead>
	                    <tr>
	                        <th>No</th>
	                        <th>Jenis</th>
	                        <th>No Motor</th>
	                        <th>Nama</th>
	                        <th>Merk</th>
	                        <th>Harga</th>
	                        <th>Status</th>
	                        <th class="hidden-print"></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php $no = 1; ?>
	                    <?php if ($query = $connection->query("SELECT * FROM motor JOIN jenis USING(id_jenis)")): ?>
	                        <?php while($row = $query->fetch_assoc()): ?>
	                        <tr>
	                            <td><?=$no++?></td>
															<td><?=$row['nama']?></td>
															<td><?=$row['no_motor']?></td>
															<td><?=$row['nama_motor']?></td>
															<td><?=$row['merk']?></td>
															<td><?=$row['harga']?></td>
															<td><span class="label label-<?=($row['status']) ? "success" : "danger" ?>"><?=($row['status']) ? "Tersedia" : "Tidak Tersedia" ?></span></td>
	                            <td class="hidden-print">
	                                <div class="btn-group">
	                                    <a href="../assets/img/motor/<?=$row['gambar']?>" class="btn btn-info btn-xs fancybox">Lihat</a>
	                                    <a href="?page=motor&action=update&key=<?=$row['id_motor']?>" class="btn btn-warning btn-xs">Edit</a>
	                                    <a href="?page=motor&action=delete&key=<?=$row['id_motor']?>" class="btn btn-danger btn-xs">Hapus</a>
	                                </div>
	                            </td>
	                        </tr>
	                        <?php endwhile ?>
	                    <?php endif ?>
	                </tbody>
	            </table>
	        </div>
			    <div class="panel-footer hidden-print">
			        <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
			    </div>
	    </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$(".fancybox").fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		iframe : {
			preload: false
		}
	});
	$(".various").fancybox({
		maxWidth    : 800,
		maxHeight    : 600,
		fitToView    : false,
		width        : '70%',
		height        : '70%',
		autoSize    : false,
		closeClick    : false,
		openEffect    : 'none',
		closeEffect    : 'none'
	});
	$('.fancybox-media').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
			media : {}
		}
	});
});
</script>