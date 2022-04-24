<?php $this->load->view('admin/include/header'); ?>
<?php $this->load->view('admin/include/leftmenu'); ?>
<div class="row">
	<div class="col-md-8">
<div class="box box-solid">
	<div class="box-body">

		<form method="post" action="<?php echo base_url('admin/urunstoktipiekle/'.$this->uri->segment(3));?>">
				<div class="row">
					<div class="col-xs-12">
				<div class="form-group">
				<label>Ürünün 1.Seçeneğini Belirleyiniz</label>
					<select class="form-control" name="subcategory">
						<?php foreach($options as $option) { ?>
							<option value="<?=$option->id?>"><?=$option->name?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div><div class="col-xs-12">
			
				<div class="form-group">
				<label>Ürünün 2.Seçeneğini Belirleyiniz <sup>*opsiyonel</sup></label>
					<select class="form-control" name="subcategory2">
						<option value="0">Seçim Yapınız</option>
						<?php foreach($options as $option) { ?>
							<option value="<?=$option->id?>"><?=$option->name?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<button name="step1" value="1" type="submit"class="btn btn-block btn-flat btn-success"> Ekle</button>
			</div>
		</form>
	</div>
</div>
</div>
	
<div class="col-md-4">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box title"> 3.Aşama </h3>
			<div class="box-body">
			<h2 align="center"> Ürün Seçenekleri ve Stok </h2>
		</div>
		</div>
	</div>
</div>


<?php $this->load->view('admin/include/footer'); ?>