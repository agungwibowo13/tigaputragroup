<?php
	class CategoryController extends FrontendController {
		public function __construct() {
			$this->views = 'modules/category/frontend/views/category/';
		}

		public function index() {
			$categories = Category::model()->findAll(array(
				'condition' => 'is_deleted = 0',
			));


			// $products = Product::model()->findAll(array(
			// 	'condition' => 'category_id = :category_id AND is_deleted = 0',
			// 	'params'	=> [':category_id' => $category->categ]
			// ));

			$this->page_title 	 = 'Kategori Produk';
			$this->crumbs[] = array(
				'url' 	=> $this->createUrl(''),
				'name' 	=> '<i class="fa fa-home"></i>'
			);

			$this->crumbs[] = array(
				'url' 	=> $this->createUrl('product/category'),
				'name' 	=> 'Kategori Produk'
			);

			return $this->render('index', array(
				'categories' => $categories,
			));
		}
	}