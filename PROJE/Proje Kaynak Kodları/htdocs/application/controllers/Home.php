<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function index()
	{
		$this->load->view('front/home');
	}


	public function product($seo)
	{
		$product=Urunler::find(['seo'=>$seo]);
		if($product and $product->active==1)
		{  		
			$data['product']=$product;
			$data['stocks']=$this->db->from('stocks')->where('product',$product->id)->group_by('suboption2')->get()->result();
			$data['images']=Resimler::select(['product'=>$product->id],['master'=>'DESC']);
			$data['stocktype']=StokTipi::find(['product'=>$product->id]);
			$this->load->view('front/product/product',$data);
		}

	}


	public function category($category)
	{
		switch ($category) 
		{
			case 'kedi':
				$data['kategoriler']=Kategori::select(['topcategory'=>1]);
				$data['urunler']=Urunler::select(['category'=>1,'active'=>1]);
				$data['pageinfo']=['title'=>'KEDİ', 'subtitle'=>'Yeni Kedi Ürünleri','image'=>'empty'];

				
				break; 
				case 'kopek':
				$data['kategoriler']=Kategori::select(['topcategory'=>2]);
				$data['urunler']=Urunler::select(['category'=>2,'active'=>1]);
				$data['pageinfo']=['title'=>'KÖPEK', 'subtitle'=>'Yeni Köpek Ürünleri','image'=>'empty'];
				
				break;
				case 'tavsan':
				$data['kategoriler']=Kategori::select(['topcategory'=>3]);
				$data['urunler']=Urunler::select(['category'=>3,'active'=>1]);
				$data['pageinfo']=['title'=>'TAVŞAN', 'subtitle'=>'Yeni Tavşan Ürünleri','image'=>'empty'];
				
				break; 
			
			default:
				//hata kodu
				break;
		}

		$this->load->view('front/category/category',$data);
	}

public function subcategory($category, $subcategory)
{
		echo $category.$subcategory;
}
	
}