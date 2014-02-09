<?php
class model_spyLink extends model
{
    // Получение текущего статуса ссылки
    public function get_history($link) {
        return model::factory('spy_history')->get_by_link($link);
    }
}