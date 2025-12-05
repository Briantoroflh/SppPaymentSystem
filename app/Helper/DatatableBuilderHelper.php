<?php

namespace App\Helper;

use Exception;

class DatatableBuilderHelper
{
    public static function create($params)
    {
        if (!isset($params['name'])) throw new Exception("Table name must be defined!");
        if (!isset($params['columns'])) throw new Exception("Table column must be defined!");
        if (!isset($params['url'])) throw new Exception("Table column must be defined!");

        $data['name']           = $params['name'];
        $data['url']            = $params['url'];
        $data['columns']        = $params['columns'];
        $data['searching']      = $params['searching'] ?? false;
        $data['ordering']       = $params['ordering'] ?? false;
        $data['method']         = $params['method'] ?? 'GET';

        return view('components.datatable', $data);
    }
}
