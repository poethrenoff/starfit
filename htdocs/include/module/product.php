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
            $product_list = model::factory('product')->get_list(
                array('product_active' => 1, 'product_catalogue' => $catalogue_id), array('product_order' => 'asc')
            );
            
            $this->view->assign('catalogue', $catalogue);
            $this->view->assign('product_list', $product_list);
            $this->content = $this->view->fetch('module/product/product');
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
        
        $novelty_view = new view();
        $novelty_view->assign('marker', $marker_novelty);
        $novelty_view->assign('product_list', $product_novelty_list);
        $leader_view = new view();
        $leader_view->assign('marker', $marker_leader);
        $leader_view->assign('product_list', $product_leader_list);
        
        $this->content = $novelty_view->fetch('module/product/marker') . $leader_view->fetch('module/product/marker');
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Дополнительные параметры хэша модуля
    protected function ext_cache_key()
    {
        $catalogue_name = get_param('catalogue');
        return parent::ext_cache_key() + (
            $catalogue_name && in_array($this->action, array('index', 'item')) ? array('_name' => $catalogue_name
        ) : array());
    }
}