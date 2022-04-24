<?php $this->load->view('admin/include/header'); ?>
<?php $this->load->view('admin/include/leftmenu'); ?>
<div class="row">
	<div class="col-md-3">

		<div class="small-box bg-red">
<div class="inner">
<h3>Ürün Oluştur</h3>
</div>
<div class="icon">
<i class="fa fa-plus"></i>
</div>
<a href="<?php echo base_url('admin/urunekle') ?>" class="small-box-footer">Tıkla <i class="fa fa-arrow-right"></i></a>
</div>

	</div>
	<div class="col-md-9">
		<table id="category" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Ürün İsmi</th>
					<th>Ürün Kategorisi</th>
					<th>Ürün Alt Kategorisi</th>
					<th>Ürün Fiyat</th>
					<th>İşlemler</th>
</tr>
</thead>
<tbody>
	<?php foreach($products as $product) { ?>
	<tr>
	<td><?=$product->title; ?></td>
	<td><?php if($product->category==1){echo "Kedi";}elseif($product->category==2){echo "Köpek";} else{echo "Tavşan";} ?></td>
	<td><?php echo Kategori::find($product->subcategory)->name; ?>
	<td><?php if($product->discount!=null) { echo "<del class='text-red'>" .$product->price. " TL </del> ".$product->discount." TL"; }else{echo $product->price." TL"; } ?>
	<td>
		<a href="<?php echo base_url('admin/urunduzenle/'.$product->id)?>" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> Güncelle </a>
		<?php deletebutton('product',$product->id); ?>
	</td>
</tr>
<?php } ?>
</tbody>
</table>

	</div>
	
</div>

<?php $this->load->view('admin/include/footer'); ?>