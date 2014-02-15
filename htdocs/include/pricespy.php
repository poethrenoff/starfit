<?php
class pricespy {

    protected $curl = null;

    /**
     * Конструктор
     */
    public function __construct() {
        $this->curl = new curl();
    }
    
    /**
     * Разбор всех сайтов
     */
    public function parse() {
        $site_list = model::factory('spy_site')->get_list(array('site_active' => 1));
        foreach ($site_list as $site) {
            $this->parse_site($site);
        }
    }
    
    /**
     * Разбор конкретного сайта
     */
    public function parse_site(model $site) {
        $link_list = model::factory('spy_link')->get_list(array('link_site' => $site->get_id(), 'link_active' => 1));
        foreach ($link_list as $link) {
            $this->parse_link($link);
        }
    }
    
    /**
     * Разбор конкретной ссылки
     */
    public function parse_link(model $link) {
        $history_price = 0;
        $history_error = '';
        
        try {
            $result = $this->curl->get($link->get_link_url());
            
            $site = model::factory('spy_site')->get($link->get_link_site());
            if ($site->get_site_utf8()) {
                $site_pattern = '/' . $site->get_site_pattern() . '/iu';
            } else {
                $site_pattern = '/' . iconv('UTF-8', 'Windows-1251', $site->get_site_pattern()) . '/i';
            }
            
            if (@preg_match($site_pattern, $result, $matches)) {
                $history_price = $matches[1];
                if ($site->get_site_thousands_sep()) {
                    $history_price = str_replace($site->get_site_thousands_sep(), '', $history_price);
                }
                if ($site->get_site_dec_point()) {
                    $history_price = str_replace($site->get_site_dec_point(), '.', $history_price);
                }
            } else {
                $history_error = 'Цена не найдена';
            }
        } catch (Exception $e) {
            $history_error = 'Ошибка соединения';
        }
        
        $history = model::factory('spy_history')
            ->set_history_link($link->get_id())
            ->set_history_price($history_price)
            ->set_history_error($history_error)
            ->save();
    }
    
    /**
     * Возвращает отчет
     */
    public function get_report() {
        $report = array();
        $site_list = model::factory('spy_site')->get_list(array('site_active' => 1));
        foreach ($site_list as $site) {
            $report = array_merge($report, $this->get_report_site($site));
        }
        return $report;
    }
    
    /**
     * Возвращает отчет по сайту
     */
    public function get_report_site(model $site) {
        $report = array();
        $link_list = model::factory('spy_link')->get_list(array('link_site' => $site->get_id(), 'link_active' => 1));
        foreach ($link_list as $link) {
            $report = array_merge($report, $this->get_report_link($link));
        }
        return $report;
    }
    
    /**
     * Возвращает отчет по ссылке
     */
    public function get_report_link(model $link) {
        $report = array();
        if ($history = $link->get_history($link)) {
            $report[] = $history;
        }
        return $report;
    }
}
