<form class="form-inline hidden-print" action="<?=$_SERVER["REQUEST_URI"]?>" method="post">
		<label>Motor :</label>
		<select class="form-control" name="id_motor">
			<option>---</option>
			<?php $query = $connection->query("SELECT * FROM motor"); while ($r = $query->fetch_assoc()): ?>
				<option value="<?=$r["id_motor"]?>"><?=$r["nama_motor"]?> | <?=$r["no_motor"]?></option>
			<?php endwhile; ?>
		</select>
    <label>Tgl :</label>
    <input type="text" class="form-control" name="start">
    <label>s/d</label>
    <input type="text" class="form-control" name="stop">
		<button type="submit" class="btn btn-primary">Tampilkan</button>
	</form>
	<br>
	<?php if ($_POST): ?>
	  <div class="panel panel-info">
	    <div class="panel-heading"><h3 class="text-center">LAPORAN PENYEWAAN PERMOTOR</h3><br><h4 class="text-center">tgl: <?=$_POST["start"]." s/d ".$_POST["stop"]?></h4></div>
	    <div class="panel-body">
				<?php $query = $connection->query("SELECT d.no_motor, d.nama_motor, d.merk FROM transaksi a JOIN pelanggan b USING(id_pelanggan) JOIN motor d USING(id_motor) WHERE d.id_motor=$_POST[id_motor] AND a.tgl_sewa BETWEEN '$_POST[start]' AND '$_POST[stop]'"); ?>
				<?php while($row = $query->fetch_assoc()): ?>
					<form class="form-inline">
						<table>
							<tr>
								<td>
									<label>Nomor Motor</label>
								</td>
								<td>&nbsp;:&nbsp;
									<input type="text" value="<?=$row['no_motor']?>" class="form-control" disabled="on"><br>
								</td>
							</tr>
							<tr>
								<td>
									<label>Nama motor</label>
								</td>
								<td>&nbsp;:&nbsp;
									<input type="text" value="<?=$row['nama_motor']?>" class="form-control" disabled="on"><br>
								</td>
							</tr>
							<tr>
								<td>
									<label>Merk</label>
								</td>
								<td>&nbsp;:&nbsp;
									<input type="text" value="<?=$row['merk']?>" class="form-control" disabled="on">
								</td>
							</tr>
						</table>
					</form>
				<?php endwhile ?>
				<br>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Harga Sewa</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
								<?php $q = $connection->query("SELECT b.nama, d.harga FROM transaksi a JOIN pelanggan b USING(id_pelanggan) JOIN motor d USING(id_motor) WHERE d.id_motor=$_POST[id_motor] AND a.tgl_sewa BETWEEN '$_POST[start]' AND '$_POST[stop]'"); ?>
                <?php while($r = $q->fetch_assoc()): ?>
                <tr>
                    <td><?=$no++?></td>
										<td><?=$r['nama']?></td>
										<td>Rp.<?=number_format($r['harga'])?></td>
										
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
	    </div>
	    <div class="panel-footer hidden-print">
	        <a onClick="window.print();return false" class="btn btn-primary"><i class="glyphicon glyphicon-print"></i></a>
	    </div>
	  </div>
<?php endif; ?>