<?php $this->load->view('admin/include/header'); ?>
<?php $this->load->view('admin/include/leftmenu'); ?>
<div class="row">
	<div class="col-md-3">

		<div class="small-box bg-red">
<div class="inner">
<h3>Kategori Oluştur</h3>
</div>
<div class="icon">
<i class="fa fa-plus"></i>
</div>
<a href="<?php echo base_url('admin/kategoriekle') ?>" class="small-box-footer">Tıkla <i class="fa fa-arrow-right"></i></a>
</div>

	</div>
	<div class="col-md-9">
		<table id="category" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Kategori İsmi</th>
					<th>Üst Kategori</th>
					<th>İşlemler</th>
</tr>
</thead>
<tbody>
	<?php foreach($categories as $category) { ?>
	<tr>
	<td><?=$category->name; ?></td>
	<td><?php if($category->topcategory==1){echo "Kedi";}elseif($category->topcategory==2){echo "Köpek";}else{echo "Tavşan";} ?></td>
	<td>
		<a href="<?php echo base_url('admin/kategoriduzenle/'.$category->id); ?>" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> Düzenle</a>
		<?php deletebutton('category',$category->id); ?>

	</td>
</tr>
<?php } ?>
</tbody>
</table>

	</div>
	
</div>

<?php $this->load->view('admin/include/footer'); ?>