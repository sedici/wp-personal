<?php
$args = [];
foreach ($this->getInputsPersonal() as $item) {
    $item['default_value'] = get_post_meta($post->ID, $item['name'], true);
    $args[] = $item;
}
echo register_field_group($args);

function register_field_group($args)
{
    $input = '<div class="inptuts-personal">';
    wp_nonce_field('mi_meta_box_nonce', 'meta_box_nonce');
    foreach ($args as $value) {
        $input .= "<div class='personal-label'> <label for='" . $value['name'] . "'>" . $value['label'] . "</label><p class='description'>" . $value['instructions'] . " </p></div>";
        if ($value['type'] != 'wp_editor') {
            $input .= "<div class=''><input type=" . $value['type'] . "  name=" . $value['name'] . " placeholder='" . $value['placeholder'] . "' value='" . $value['default_value'] . "'></div>";
        } else {
            ob_start();
            wp_editor($value['default_value'],$value['name']);
            $_edit_form_advanced_output = ob_get_clean();
            $input.= '<div class="prueba">'.$_edit_form_advanced_output . '</div>';
//            $input .= "<div class=''><textarea type= text  rows='10' name=" . $value['name'] . "' value='" . $value['default_value'] . "'>".$value['default_value'] ."</textarea></div>";
        }
    }
    return $input . '</div>';
}


