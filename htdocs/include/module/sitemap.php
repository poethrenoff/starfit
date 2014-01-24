<?php
class module_sitemap extends module
{
	protected function action_index()
	{
		$site_url = 'http://' . $_SERVER['HTTP_HOST'];
		
		$sitemap = array();
		
		// Главная
		$sitemap[] = array(
			'loc' => $site_url,
			'lastmod' => date( 'Y-m-d', time() - 60 * 60 * 24 * mt_rand( 0, 6 ) ),
			'changefreq' => 'weekly',
			'priority' => 1 );
		
		// Текстовые страницы
		foreach ( array( '/about', '/delivery', '/warranty', '/advantage', '/contact' ) as $page )
			$sitemap[] = array(
				'loc' => $site_url . $page,
				'lastmod' => date( 'Y-m-d', time() - 60 * 60 * 24 * mt_rand( 0, 6 ) ),
				'changefreq' => 'weekly',
				'priority' => 0.5 );
		
		// Статьи
		$article_list = model::factory('article')->get_list(
			array('article_active' => 1), array('article_order' => 'asc')
		);
		foreach ( $article_list as $article_item ) {
			$sitemap[] = array(
				'loc' => $site_url . $article_item->get_article_url(),
				'lastmod' => date( 'Y-m-d', time() - 60 * 60 * 24 * mt_rand( 0, 6 ) ),
				'changefreq' => 'weekly',
				'priority' => 0.5 );
		}
		
		// Каталог
		$catalogue_list = model::factory('catalogue')->get_list(
			array('catalogue_active' => 1), array('catalogue_order' => 'asc')
		);
		$catalogue_ids = array();
		foreach ( $catalogue_list as $catalogue_item ) {
			$sitemap[] = array(
				'loc' => $site_url . $catalogue_item->get_catalogue_url(),
				'lastmod' => date( 'Y-m-d', time() - 60 * 60 * 24 * mt_rand( 0, 6 ) ),
				'changefreq' => 'weekly',
				'priority' => 0.5 );
			$catalogue_ids[] = $catalogue_item->get_id();
		}
		
		// Товары
		$product_list = model::factory('product')->get_list(
			array('product_active' => 1), array('product_id' => 'asc')
		);
		foreach ( $product_list as $product_item ) {
			if (in_array($product_item->get_product_catalogue(), $catalogue_ids)) {
				$sitemap[] = array(
					'loc' => $site_url . $product_item->get_product_url(),
					'lastmod' => date( 'Y-m-d', time() - 60 * 60 * 24 * mt_rand( 0, 6 ) ),
					'changefreq' => 'weekly',
					'priority' => 0.3 );
			}
		}
		
		header( 'Content-type: text/xml; charset: UTF-8' );
		
		$this->view->assign('sitemap', $sitemap);
		$this->content = $this->view->fetch('module/sitemap/index');
	}
}