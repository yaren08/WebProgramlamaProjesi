<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('adminlogin') && $this->uri->segment(2) && $this->uri->segment(2)!='login')
		{
			redirect('admin');
		}
	}


	public function index()
	{
		if($this->session->userdata('adminlogin')){redirect('admin/panel');}
		$this->load->view('admin/login');
	}

public function urunler()
{
	$data['head']="Ürünler";
	$data['products']=Urunler::select();
	$this->load->view('admin/product/products', $data);
}


public function urunekle()
{
	$data['head']="Ürün Ekle";
	$data['subcategory']=Kategori::select();
	$this->load->view('admin/product/addproduct', $data);

}

public function deletefunction()
{
	if($this->session->userdata('deletefunction'))
	{
		$this->session->unset_userdata('deletefunction');

	}else
	{
		$this->session->set_userdata('deletefunction',true);
	}

	back();
}


public function sil($table,$id)
{
	if(!$this->session->userdata('deletefunction')){}
		switch($table)
	{
	case "product":
		$product=Urunler::find($id);
		if($product)
		{
			StokTipi::delete(['product'=>$id]);
			Stoklar::delete(['product'=>$id]);
			$images=Resimler::select(['product'=>$id]);
			foreach($images as $image)
			{
				unlink($image->path);
				Resimler::delete($image->id);
			}
			unlink($product->qrcode);
			Urunler::delete($id);
			

		}
	break;
	case "stock":
	Stoklar::delete($id);
	break;
	case "category":
	Kategori::delete($id);
	break;
	case "option":
	$option=Secenekler::find($id);
	Altsecenekler::delete(['option_id'=>$option->id]);
	Secenekler::delete($id);
	break;
	case "suboption":
	$altsecenek=Altsecenekler::find($id);
	Stoklar::delete(['suboption'=>$altsecenek->id]);
	Stoklar::delete(['suboption2'=>$altsecenek->id]);
	Altsecenekler::delete($id);
	break;

	}
	flash('success','check','silme işlemi başarıyla gerçekleşti.');
	back();
}


public function urunduzenle($id)
{
	if(isPost())
	{
		if(postvalue('product')) //ürün update kısmı
		{
			$data=['category'=>postvalue('category'),
				  'subcategory'=>postvalue('subcategory'),
				  'title'=>postvalue('title'),
				  'description'=>postvalue('desc'),
				  'price'=>postvalue('price'),
				  'discount'=>postvalue('discount'),
				  'tag'=>postvalue('tag'),
				];
				Urunler::update($id,$data);
				flash('success', 'check', 'Ürün bilgieri güncellendi.');
				back();
		}

	}

	$urun=Urunler::find($id);
	if(!$urun){flash('warning','Hata','Böyle bir ürün bulunamadı.'); back();}
	$data['product']=$urun;
	$data['images']=Resimler::select(['product'=>$id]);
	$data['stocks']=Stoklar::select(['product'=>$id]);
	$data['type']=StokTipi::find(['product'=>$id]);
	$data['head']="Ürün Düzenle";
	$data['subcategory']=Kategori::select();
	$this->load->view('admin/product/editproduct', $data);

}


public function urunresimsil($id)
{
	$resim=Resimler::find($id);
	if($resim->master==1){flash('warning', 'ban', 'Kapak fotoğrafı silinemez.'); back();}
	unlink($resim->path);
	Resimler::delete($id);
	flash('success', 'check', 'Resim başarıyla silindi');
	back();
}

public function urunresimkapak($id)
{
	$resim=Resimler::find($id);
	Resimler::update(['product'=>$resim->product],['master'=>0]);
	$data=['master'=>1];
	Resimler::update($id,$data);
	flash('success', 'check', 'Resim başarıyla kapak resmi seçildi.');
	back();
}



public function urunresimekle($id)
{

	if(isPost())
	{
		$urun=Urunler::find($id);	
		$config['upload_path']="assets/upload/products/";
		$config['allowed_types']="jpg|png|jpeg";
		$config['file_name']=$urun->seo.$urun->id;
		$this->upload->initialize($config);
		if($this->upload->do_upload('file'))
		{
			$image=$this->upload->data();
            $path=$config['upload_path'].$image['file_name'];
            $data=['product'=>$id,'path'=>$path];
            Resimler::insert($data);
		}
	}

	$data['head']="Ürün Ekle";
	$data['subcategory']=Kategori::select();
	$this->load->view('admin/product/addproductimage', $data);

}

public function urunstoktipiekle($id)
{

	if(isPost())
	{
		if(postvalue('subcategory')==postvalue('subcategory2'))
		{

			flash('warning', 'remove', 'Ürün seçenekleri birbirinden farklı olmalıdır. ');
			back();
		}
			if(Stoktipi::count(['product'=>$id])==1)
			{

			flash('warning', 'remove', 'Zaten stok belirlenmiş.');
			back();	

			}	

					$data=['product'=>$id, 'options'=>postvalue('subcategory')];
					if(postvalue('subcategory2')!=0){$data['options2']=postvalue('subcategory2');}
					StokTipi::insert($data);
					flash('success', 'check', 'Stok eklendi.');
					redirect('admin/urunstokekle/'.$id);
	}

	$data['head']="Ürün Stok Ekle";
	$data['options']=Secenekler::select();
	$this->load->view('admin/product/addproductstocktype', $data);

}


public function urunstokekle($id)
{
	if(isPost())
	{
		if(Stoklar::find(['product'=>$id,'suboption'=>postvalue('subcategory'),'suboption2'=>postvalue('subcategory2')]))
		{
			flash('danger', 'remove', 'zaten stok girildi'); 
			back();
		}
		$data=['product'=>$id, 'suboption'=>postvalue('subcategory'),'suboption2'=>postvalue('subcategory2'),'stock'=>postvalue('stock')];
		Stoklar::insert($data);
		flash('success', 'check', ' stok başarıyla girildi'); 
		back();
	}

	$product=Urunler::find($id);
	if(!$product){flash('danger', 'remove', 'böyle bir ürün bulunamadı'); back();}
	$stocktype=StokTipi::find(['product'=>$product->id]);
	$secenek1=AltSecenekler::select(['option_id'=>$stocktype->options]);
	$secenek2=null;
	if($stocktype->options2!=null)
	{
		$secenek2=AltSecenekler::select(['option_id'=>$stocktype->options2]);

	}
	$data['option1']=$secenek1;
	$data['option2']=$secenek2;
	$data['type']=$stocktype;
	$data['stocks']=Stoklar::select(['product'=>$id]);
	$data['head']="Ürün Stoklarını Giriniz";
	$this->load->view('admin/product/addproductstock', $data);

}

public function urunstokguncelle($id)
{
	if(isPost())
	{
		$stock=Stoklar::find($id);
		$data=['product'=>$stock->product, 'suboption'=>postvalue('subcategory'),'suboption2'=>postvalue('subcategory2'),'stock'=>postvalue('stock')];
		Stoklar::update($id,$data);
		flash('success', 'check', ' stok başarıyla güncellendi'); 
		back();
	}


	$stock=Stoklar::find($id);
	$stocktype=StokTipi::find(['product'=>$stock->product]);
	$secenek1=AltSecenekler::select(['option_id'=>$stocktype->options]);
	$secenek2=null;
	if($stocktype->options2!=null)
	{
		$secenek2=AltSecenekler::select(['option_id'=>$stocktype->options2]);

	}
	$data['option1']=$secenek1;
	$data['option2']=$secenek2;
	$data['type']=$stocktype;
	$data['stock']=$stock;
	$data['head']="Ürün Stok Güncelle";
	$this->load->view('admin/product/editproductstock',$data);
}


public function uruncontroller($id=null)
{
	if(isPost())
	{
		if(postvalue('step1'))
		{
			$data=['category'=>postvalue('category'),
				  'subcategory'=>postvalue('subcategory'),
				  'title'=>postvalue('title'),
				  'description'=>postvalue('desc'),
				  'price'=>postvalue('price'),
				  'discount'=>postvalue('discount'),
				  'tag'=>postvalue('tag'),
				];
				Urunler::insert($data);
				$insert_id=$this->db->insert_id();
				$qrpath= 'assets/upload/qrcode/urun'.$insert_id.'.png';
				$params['data'] = 'urunid='.$insert_id;
				$params['level'] = 'H';
				$params['size'] = 5;
				$params['savename'] = FCPATH.$qrpath;
				$this->ciqrcode->generate($params);
				Urunler::update($insert_id,['qrcode'=>$qrpath]);
				redirect('admin/urunresimekle/'.$insert_id);
		}
	}

	$urun=Urunler::find($id);
	if($urun)
	{
		Urunler::update($id,['complete'=>1]);
		flash('success', 'check', ' ürün başarıyla girildi');
		redirect('admin/urunler'); 
	}
}



public function kategoriler()

{
    $data['head']="Ürün Kategorileri";
    $data['categories']=Kategori::select();
	$this->load->view('admin/category/categories',$data);
}

	public function kategoriekle()
	{
		if(isPost())
	{

	$data=['topcategory'=>postvalue('topcategory'),'name'=>postvalue('category'),'slug'=>sef(postvalue('category'))];
	Kategori::insert($data);
	flash('success','check','Kategori Başarıyla Eklendi');
	back();

	}

		$data['head']="Kategori Ekle";
		$this->load->view('admin/category/addcategory',$data);

	}

	public function kategoriduzenle($id)
	
	{
		if(isPost())
	{

$data=['topcategory'=>postvalue('topcategory'),'name'=>postvalue('category'),'slug'=>sef(postvalue('category'))];
Kategori::update($id,$data);
flash('success','check','Kategori Güncellendi');
back();

}



		$isExist=Kategori::find($id);
		if($isExist)
	{
		$data['category']=$isExist;
		$this->load->view('admin/category/editcategory',$data);
	}


	}


public function urunsecenekleri()

{
	$data['head']="Ürün Seçenekleri";
	$data['options']=Secenekler::select();
	$this->load->view('admin/options/options',$data);



}

public function secenekekle()
	{
		if(isPost())
	{

	$data=['name'=>postvalue('option'),'slug'=>sef(postvalue('option'))];
	Secenekler::insert($data);
	flash('success','check','Seçenek Başarıyla Eklendi');
	back();

	}

		$data['head']="Seçenek Ekle";
		$this->load->view('admin/options/addoption',$data);

	}

	public function secenekduzenle($id)
	
	{
		if(isPost())
	{

$data=['name'=>postvalue('option'),'slug'=>sef(postvalue('option'))];
Secenekler::update($id,$data);
flash('success','check','Seçenek Güncellendi');
back();

}



		$isExist=Secenekler::find($id);
		if($isExist)
	{
		$data['option']=$isExist;
		$this->load->view('admin/options/editoption',$data);
	}


	}

	
public function altsecenekler($id)
{
	$option=Secenekler::find($id);
	$data['head']=$option->name." için Alt Seçenekler";
	$data['suboptions']=AltSecenekler::select(['option_id'=>$id]);
	$data['option']=$option;
	$this->load->view('admin/options/suboptions',$data);

}


public function altsecenekekle($id)
{
	if(isPost())
	{
		$suboption=postvalue('suboption');
		$ara="-";
		if(strpos($suboption,$ara))
		{
				$value=explode('-',$suboption);
				foreach($value as $name)
				{

					AltSecenekler::insert(['option_id'=>$id,'name'=>$name]);
				}
					redirect('admin/altsecenekler/'.$id);

		}else
		{
			AltSecenekler::insert(['option_id'=>$id,'name'=>$suboption]);
			redirect('admin/altsecenekler/'.$id);
		}

	}
$this->load->view('admin/options/addsuboption');
}


public function altsecenekduzenle($id)
	
	{
		if(isPost())
	{
$suboption=AltSecenekler::find($id);
$data=['name'=>postvalue('option')];
AltSecenekler::update($id,$data);
redirect('admin/altsecenekler/'.$suboption->option_id);

}



		$isExist=AltSecenekler::find($id);
		if($isExist)
	{
		$data['suboption']=$isExist;
		$this->load->view('admin/options/editsuboption',$data);
	}


	}




	public function login()
	{
		
		$exist=Yonetim::find(['mail'=>$this->input->post('email'),'password'=>$this->input->post('sifre')]);
	if($exist)
	{
		$this->session->set_userdata('adminlogin',true);
		$this->session->set_userdata('admininfo',$exist);
		redirect('admin/panel');
	}else
	{

$hata="Email veya şifreniz hatalı.";

$this->session->set_flashdata('error',$hata);
redirect('admin');

	}

	}

	public function panel()
	{
        $data['head']="Yönetim Paneli";
		$this->load->view('admin/panel',$data);
	}

	public function ayarlar()
	{
$data['head']="Ayarlar";
$data['config']=Ayarlar::find(2);
		$this->load->view('admin/config',$data);
	}

	public function ayarlarpost()
	{
		$config=Ayarlar::find(postvalue('id'));
		$data=['title'=>postvalue('title'),
			   'info'=>postvalue('info'), 
			   'mail'=>postvalue('mail'), 
			   'facebook'=>postvalue('facebook'), 
			   'twitter'=>postvalue('twitter'),
			   'instagram'=>postvalue('instagram'),
			   'youtube'=>postvalue('youtube')];

			   	if($_FILES['logo']['size']>0)
			   	{
			   		
			   		$data['logo']=imageupload('logo','config','png');
			   		unlink($config->logo);
			   	}
			   	if($_FILES['icon']['size']>0)
			   	{
			 		
			 		$data['icon']=imageupload('icon','config','jpg|ico|png'); 
			 		unlink($config->icon);
			  	}

			 Ayarlar::update(postvalue('id'),$data);
			 back();

	}



	public function cikis()
	{

		$this->session->sess_destroy();
		redirect('admin');
	}
}
