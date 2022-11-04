<?php
$args = [];

foreach ($this->getInputsPersonal() as $item) {

    if (isset($item['repositories'])) {
        $repositories=[];
        foreach ($item['repositories'] as $repository) {
            $repository['default_value'] = get_post_meta($post->ID, $repository['name'], true);
            $repositories[] = $repository;
        }
        $args[] =array('repositories' => $repositories);
    } else {
        $item['default_value'] = get_post_meta($post->ID, $item['name'], true);
        $args[] = $item;

    }


}
echo register_personal_field_group($args);

function register_personal_field_group($args)
{

    $input = '<div class="inptuts-personal">';
    wp_nonce_field('mi_meta_box_nonce', 'meta_box_nonce');
    foreach ($args as $value) {
        if (isset($value['type']) && $value['type'] != 'wp_editor') {
            if (isset($value['repositories'])) {
                foreach ($value['repositories'] as $r) {
                    $input .= print_input($r);
                }
            } else {
                $input .= print_input($value);
            }
        } else {
            ob_start();
            wp_editor($value['default_value'], $value['name']);
            $_edit_form_advanced_output = ob_get_clean();
            $input .= '<div >' . $_edit_form_advanced_output . '</div>';

        }
    }

    return $input . '</div>';
}

function print_input($value)
{
    if(isset($value['instructions']) && isset($value['placeholder'])) {
        $input = "<div class='personal-label'> <label for='" . $value['name'] . "'>" . $value['label'] . "</label><p class='description'>" . $value['instructions'] . " </p></div>";
        $input .= "<div class=''><input type=" . $value['type'] . "  name=" . $value['name'] . " placeholder='" . $value['placeholder'] . "' value='" . $value['default_value'] . "'></div>";
        return $input;
    }
}