<?php

namespace Personal\Admin;

class Csv_Exporter
{
    /**
     * Genera y descarga el archivo CSV con el personal.
     */
    public function export()
    {
        // Obtener todo el personal
        $args = [
            'post_type' => 'personal',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ];

        $query = new \WP_Query($args);
        $personales = $query->posts;

        // Definir las cabeceras del CSV
        $headers = [
            'post_id',
            'email',
            'nombre_apellido',
            'telefono',
            'unidad_de_investigacion',
            'rol_unidad_de_investigacion',
            'grado_alcanzado',
            'google_scholar',
            'biografia',
            'orcid',
            'researchgate',
            'linkedin',
            'facebook',
            'twitter',
            'sedici',
            'cic',
            'conicet'
        ];

        // Preparar el archivo para la descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=personal_export_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // Escribir cabeceras
        fputcsv($output, $headers);

        // Escribir datos de cada personal
        foreach ($personales as $personal) {
            $id = $personal->ID;
            $data = [
                $id,
                get_post_meta($id, 'email', true),
                $personal->post_title,
                get_post_meta($id, 'telefono', true),
                get_post_meta($id, 'unidad_de_investigacion', true),
                get_post_meta($id, 'rol_unidad_de_investigacion', true),
                get_post_meta($id, 'grado_alcanzado', true),
                get_post_meta($id, 'google_scholar', true),
                get_post_meta($id, 'biografia', true),
                get_post_meta($id, 'orcid', true),
                get_post_meta($id, 'researchgate', true),
                get_post_meta($id, 'linkedin', true),
                get_post_meta($id, 'facebook', true),
                get_post_meta($id, 'twitter', true),
                get_post_meta($id, 'sedici', true),
                get_post_meta($id, 'cic', true),
                get_post_meta($id, 'conicet', true),
            ];
            fputcsv($output, $data);
        }

        fclose($output);
        exit;
    }
}
