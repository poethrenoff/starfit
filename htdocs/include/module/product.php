<?php
class module_product extends module
{
    protected function action_index()
    {
        $catalogue_id = 0;
        $catalogue_name = get_param('catalogue');
        
        if ($catalogue_name) {
            if (is_numeric($catalogue_name)) {
                try {
                    $catalogue = model::factory('catalogue')->get($catalogue_name);
                    
                    header('HTTP/1.1 301 Moved Permanently');
                    header('Location: '  . $catalogue->get_catalogue_url());
                    exit;
                } catch (AlarmException $e) {
                    not_found();
                }
            } else {
                try {
                    $catalogue = model::factory('catalogue')->get_by_name($catalogue_name);
                } catch (AlarmException $e) {
                    not_found();
                }
                
                if (!$catalogue->get_catalogue_active()) {
                    not_found();
                }
                
                $catalogue_id = $catalogue->get_id();
            }
        }
        
        $catalogue_list = model::factory('catalogue')->get_list(
            array('catalogue_active' => 1, 'catalogue_parent' => $catalogue_id), array('catalogue_order' => 'asc')
        );
        
        if (count($catalogue_list)) {
            $catalogue_tree = model::factory('catalogue')->get_tree(
                model::factory('catalogue')->get_list(
                    array('catalogue_active' => 1), array('catalogue_order' => 'asc')
                ), $catalogue_id
            );
            
            $this->view->assign($catalogue_tree);
            $this->content = $this->view->fetch('module/product/catalogue');
        } else {
            $filter_list = model::factory('filter')->get_list(
                array('filter_catalogue' => $catalogue_id), array('filter_order' => 'asc')
            );
            $product_list = model::factory('product')->get_list(
                array('product_active' => 1, 'product_catalogue' => $catalogue_id), array('product_order' => 'asc')
            );
            
            $this->view->assign('catalogue', $catalogue);
            $this->view->assign('filter_list', $filter_list);
            $this->view->assign('product_list', $product_list);
            $this->content = $this->view->fetch('module/product/product');
        }
        
        $meta = meta::factory('catalogue')->get($catalogue_id);
        if ($meta->get_meta_title()) {
            $this->output['meta_title'] = $meta->get_meta_title();
        }
        if ($meta->get_meta_keywords()) {
            $this->output['meta_keywords'] = $meta->get_meta_keywords();
        }
        if ($meta->get_meta_description()) {
            $this->output['meta_description'] = $meta->get_meta_description();
        }
    }
    protected function action_filter()
    {
        $catalogue_name = get_param('catalogue');
        $filter_name = get_param('filter');
        
        try {
            $catalogue = model::factory('catalogue')->get_by_name($catalogue_name);
            $filter = model::factory('filter')->get_by_name($catalogue, $filter_name);
        } catch (AlarmException $e) {
            not_found();
        }
        
        $filter_list = model::factory('filter')->get_list(
            array('filter_catalogue' => $catalogue->get_id()), array('filter_order' => 'asc')
        );
        $product_list = model::factory('product')->get_by_filter($filter);
        
        $this->view->assign('catalogue', $catalogue);
        $this->view->assign('filter', $filter);
        $this->view->assign('filter_list', $filter_list);
        $this->view->assign('product_list', $product_list);
        $this->content = $this->view->fetch('module/product/product');
        
        $meta_catalogue = meta::factory('catalogue')->get($catalogue->get_id());
        $meta_filter = meta::factory('filter')->get($filter->get_id());
        if ($meta_catalogue->get_meta_title() || $meta_filter->get_meta_title()) {
            $this->output['meta_title'] = $meta_filter->get_meta_title() ?: $meta_catalogue->get_meta_title();
        }
        if ($meta_catalogue->get_meta_keywords() || $meta_filter->get_meta_keywords()) {
            $this->output['meta_keywords'] = $meta_filter->get_meta_keywords() ?: $meta_catalogue->get_meta_keywords();
        }
        if ($meta_catalogue->get_meta_description() || $meta_filter->get_meta_description()) {
            $this->output['meta_description'] = $meta_filter->get_meta_description() ?: $meta_catalogue->get_meta_description();
        }
    }
    
    protected function action_item()
    {
        try {
            $product = model::factory('product')->get(id());
        } catch (AlarmException $e) {
            not_found();
        }
        
        if (!$product->get_product_active()) {
            not_found();
        }
        
        $catalogue_name = get_param('catalogue');
        if (is_numeric($catalogue_name)) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '  . $product->get_product_url());
            exit;
        }
        
        $this->view->assign($product);
        $this->content = $this->view->fetch('module/product/item');
        
        $meta = meta::factory('product')->get(id());
        $this->output['meta_title'] = $meta->get_meta_title() ?: $product->get_product_title();
        $this->output['meta_keywords'] = $meta->get_meta_keywords() ?: $product->get_product_title();
        $this->output['meta_description'] = $meta->get_meta_description() ?: $product->get_product_title();
    }
    
    protected function action_vote()
    {
        try {
            $product = model::factory('product')->get(id());
        } catch (AlarmException $e) {
            not_found();
        }
        
        if (!$product->get_product_active()) {
            not_found();
        }
        
        $product->add_mark(min(5, max(1, init_string('mark'))))->save();
        
        $this->content = json_encode(
            array('rating' => $product->get_product_rating())
        );
    }
    
    protected function action_menu()
    {
        $catalogue_tree = model::factory('catalogue')->get_tree(
            model::factory('catalogue')->get_list(
                array('catalogue_active' => 1), array('catalogue_order' => 'asc')
            )
        );
        
        $this->view->assign($catalogue_tree);
        $this->content = $this->view->fetch('module/product/menu');
    }
    
    protected function action_marker()
    {
        $marker_novelty = model::factory('marker')->get_by_name('novelty');
        $product_novelty_list = model::factory('product')->get_by_marker($marker_novelty);
        $marker_leader = model::factory('marker')->get_by_name('leader');
        $product_leader_list = model::factory('product')->get_by_marker($marker_leader);
        $marker_discount = model::factory('marker')->get_by_name('discount');
        $product_discount_list = model::factory('product')->get_by_marker($marker_discount);
        
		foreach (array('novelty', 'leader', 'discount') as $marker_name) {
			$marker = model::factory('marker')->get_by_name($marker_name);
			$product_list = model::factory('product')->get_by_marker($marker);
			
			$marker_view = new view();
			$marker_view->assign('marker', $marker);
			$marker_view->assign('product_list', $product_list);
			
			$this->content .= $marker_view->fetch('module/product/marker');
		}
    }
    
    protected function action_marker_list()
    {
        $marker_name = get_param('marker');
		
        $marker = model::factory('marker')->get_by_name($marker_name);
		$product_list = model::factory('product')->get_by_marker($marker, 1000);
		
        $this->view->assign('marker', $marker);
		$this->view->assign('product_list', $product_list);
        $this->content = $this->view->fetch('module/product/marker');
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Дополнительные параметры хэша модуля
    protected function ext_cache_key()
    {
        $catalogue_name = get_param('catalogue');
        $filter_name = get_param('filter');
        return parent::ext_cache_key() + (
            $catalogue_name && in_array($this->action, array('index', 'filter', 'item')) ?
                array('_name' => $catalogue_name, '_filter' => $filter_name) : array());
    }
}