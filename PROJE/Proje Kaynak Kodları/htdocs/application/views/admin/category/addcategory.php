<?php $this->load->view('admin/include/header'); ?>
<?php $this->load->view('admin/include/leftmenu'); ?>
<div class="row">
	<div class="col-md-8">
<div class="box box-solid">
	<div class="box-body">

		<form method="post" action="<?php echo base_url('admin/kategoriekle');?>">
			<div class="form-group">
				<label>Kategori İsmi</label>
				<input type="text" name="category" placeholder="Kategori İsmi Giriniz." required class="form-control">

			</div>
			<div class="form-group">
				<label>Üst Kategori İsmi</label>
				<select type="text" name="topcategory" placeholder="Kategori İsmi Giriniz." required class="form-control">
					<option value="1">Kedi</option>
					<option value="2">Köpek</option>
					<option value="3">Tavşan</option>
				</select>

			</div>
			<div class="form-group">
				<button type="submit"class="btn btn-block btn-flat btn-success"> Ekle</button>
</div>
		</form>
	</div>
</div>

	</div>
	

<?php $this->load->view('admin/include/footer'); ?>