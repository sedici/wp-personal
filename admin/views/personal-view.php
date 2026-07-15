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
        $meta_val = get_post_meta($post->ID, $item['name'], true);
        // Si hay algo guardado en la base de datos, usamos eso. 
        // Si está vacío, preservamos el get_bloginfo('name') que viene de initializeInputsPersonal()
        if (!empty($meta_val)) {
            $item['default_value'] = $meta_val;
        }
        $args[] = $item;
    }

}
echo register_personal_field_group($args);

function register_personal_field_group($args)
{

    $input = '<div class="inptuts-personal">';
    wp_nonce_field('mi_meta_box_nonce', 'meta_box_nonce');
    foreach ($args as $value) {
        if (isset($value['repositories'])) {
            $value['type'] = "";
        }
        //Fix me (esta rara la condicion)
        if (isset($value['type']) or $value['type'] != 'wp_editor') {
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
    $instructions  = isset($value['instructions']) ? wp_kses_post($value['instructions']) : "";
    $placeholder   = isset($value['placeholder']) ? esc_attr($value['placeholder']) : "";
    $default_value = isset($value['default_value']) ? esc_attr($value['default_value']) : "";
    
    $html_type = isset($value['type']) ? esc_attr($value['type']) : "text";
    

    
    $name          = esc_attr($value['name']);
    $label         = isset($value['label']) ? wp_kses_post($value['label']) : "";

    $input = "<div class='personal-field-row'>";
    
    $input .= "<div class='personal-label-col'>";
    $input .= "  <label for='{$name}' style='display: flex; align-items: center; gap: 8px;'><strong>{$label}</strong></label>";
    if (!empty($instructions)) {
        $input .= "  <p class='description'>{$instructions}</p>";
    }
    $input .= "</div>";

   $input .= "<div class='personal-input-col'>";
   $input .= ($html_type === 'textarea') 
        ? "  <textarea id='{$name}' name='{$name}' placeholder='{$placeholder}' class='regular-text personal-input' rows='5'>{$default_value}</textarea>"
        : "  <input type='{$html_type}' id='{$name}' name='{$name}' placeholder='{$placeholder}' value='{$default_value}' class='regular-text personal-input'>";
        
    $input .= "</div>";

    $input .= "</div>";

    return $input;
}