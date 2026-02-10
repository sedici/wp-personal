<?php

namespace Personal\Inc\Admin;

class Csv_Importer
{
    public function __construct()
    {
    }


    public function show_csv_importer_view()
    {
        include_once dirname(__DIR__) . '/admin/views/csv-importer-view.php';
    }
}

?>