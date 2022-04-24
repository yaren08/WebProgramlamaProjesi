<?php $this->load->view('admin/include/header'); ?>
<?php $this->load->view('admin/include/leftmenu'); ?>
<div class="row">
	<div class="col-md-6">
<div class="box box-solid">
	<div class="box-body">

		<form method="post" action="<?php echo base_url('admin/urunduzenle/'.$product->id);?>">
			<div class="form-group">
				<label>Ürün İsmi</label>
				<input type="text" name="title" required class="form-control" value="<?=$product->title?>">
			</div>
			<div class="form-group">
				<label>Ürün Kategorisi</label>
					<select class="form-control" name="category">
						<option <?php if($product->category==1){echo "selected";} ?> value="1">Kedi</option>
						<option <?php if($product->category==2){echo "selected";} ?> value="2">Köpek</option>
						<option <?php if($product->category==3){echo "selected";} ?> value="3">Tavşan</option>
					</select>
				</div>
				<div class="form-group">
				<label>Ürün Alt Kategorisi</label>
					<select class="form-control" name="subcategory">
						<?php foreach($subcategory as $category) { ?>
							<option <?php if($product->subcategory==$category->id) {echo "selected";} ?> value="<?=$category->id?>"><?=$category->name?></option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-6">
							<label>Ürün Fiyatı</label>
							<input type="text" class="form-control" name="price" value="<?=$product->price?>">
						</div>
						<div>
							<div class="col-xs-6">
								<label>İndirimli Fiyatı</label>
								<input type="text" class="form-control" name="discount" value="<?=$product->discount?>">
							</div>
						</div>
					</div>

					<div class="form-group">
				<label>Ürün Tag</label>
				<input type="text" name="tag" required class="form-control" value="<?=$product->tag?>">
			</div>
			<div class="form-group">
				<label>Ürün Açıklaması</label>
				<textarea class="form-control" rows="3" name="desc"><?=$product->description?></textarea>
			</div>

			<div class="form-group">
				<button name="product" value="1" type="submit"class="btn btn-block btn-flat btn-success"> Güncelle</button>
			</div>
		</form>
	</div>
</div>
</div>
	
<div class="col-md-6">
	<div class="box box-solid">
		<div class="box-body">
			<div class="row">
		<?php foreach($images as $image){ ?>
			<div class="col-sm-4">
			<img class="img-responsive img-lg" src="<?php echo base_url($image->path); ?>">
				<a href="<?php echo base_url('admin/urunresimsil/'.$image->id)?>" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Fotoğrafı Sil </a>
				<?php if($image->master==0) { ?>
				<a href="<?php echo base_url('admin/urunresimkapak/'.$image->id)?>" class="btn btn-xs btn-success"><i class="fa fa-camera"></i> Kapak Resmi Yap </a>
				<?php } ?>
		</div>
		<?php } ?>
	</div><hr>
	<form class="dropzone" action="<?php echo base_url('admin/urunresimekle/'.$product->id);?>" enctype="multipart/form-data" method="post">
	</form>
</div>
</div>
</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card">
<div class="card-header">
<h3 class="card-title">Ürün Stok Bilgisi</h3>

</div>

<div class="card-body table-responsive p-0">
<table class="table table-hover text-nowrap">

<tr>
<th><?php echo Secenekler::find($type->options)->name; ?></th>
<?php if(Secenekler::find($type->options2)){ ?>
<th><?php echo Secenekler::find($type->options2)->name; ?></th>
<?php } ?>
<th>Stok Sayısı</th>
<th>İşlemler</th>
</tr>

<?php foreach($stocks as $stock){ ?>

<tr>
<td><?=AltSecenekler::find($stock->suboption)->name ?></td>
<?php if(AltSecenekler::find($stock->suboption2)) { ?>	
<td><?=AltSecenekler::find($stock->suboption2)->name ?></td>
<?php } ?>
<td><?=$stock->stock?></td>
<td>
	<a href="<?php echo base_url('admin/urunstokguncelle/'.$stock->id);?>" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Düzenle</a>
	<?php deletebutton('stock',$stock->id); ?>
</td>
</tr>

<?php } ?>
</table>
</div>
</div>
</div>
</div>
</div>
</div>




<?php $this->load->view('admin/include/footer'); ?>
