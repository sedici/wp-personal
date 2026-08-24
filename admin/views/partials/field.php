<?php
/**
 * Template para un campo del formulario de edición del post Personal.
 *
 * @var array $args Argumentos para configurar el campo.
 */

if (!defined('ABSPATH')) {
    exit;
}

$name              = esc_attr($args['name'] ?? '');
$label             = wp_kses_post($args['label'] ?? '');
$html_type         = esc_attr($args['type'] ?? 'text');
$placeholder       = esc_attr($args['placeholder'] ?? '');
$raw_default_value = $args['default_value'] ?? '';
$default_value     = is_array($raw_default_value) ? esc_attr($raw_default_value['url'] ?? '') : esc_attr($raw_default_value);
$instructions      = wp_kses_post($args['instructions'] ?? '');
$tooltip           = esc_attr($args['tooltip'] ?? '');
?>

<div class="personal-field-row">
        
    <div class="personal-label-col">
        <label for="<?php echo $name; ?>" style="display: flex; align-items: center; gap: 8px;">
            <strong><?php echo $label; ?></strong>
            <?php if (!empty($tooltip)) : ?>
                <span class="personal-tooltip dashicons dashicons-info-outline" data-tooltip="<?php echo $tooltip; ?>"></span>
            <?php endif; ?>
        </label>
        <?php if (!empty($instructions)) : ?>
            <p class="description"><?php echo $instructions; ?></p>
        <?php endif; ?>
    </div>

    <div class="personal-input-col">
        <?php if ($html_type === 'textarea') : ?>
            <textarea id="<?php echo $name; ?>" 
                      name="<?php echo $name; ?>" 
                      placeholder="<?php echo $placeholder; ?>" 
                      class="regular-text personal-input" 
                      rows="5"><?php echo $default_value; ?></textarea>

        <?php elseif ($html_type === 'checkbox') : ?>
            <?php $checked = ($default_value === '1') ? 'checked' : ''; ?>
            <input type="checkbox" 
                   id="<?php echo $name; ?>" 
                   name="<?php echo $name; ?>" 
                   value="1" 
                   class="personal-input-col" 
                   <?php echo $checked; ?>>

        <?php elseif ($html_type === 'file') : ?>
            <input type="file" 
                   id="<?php echo $name; ?>" 
                   name="<?php echo $name; ?>" 
                   class="regular-text personal-input">
            
            <?php 
            $file_url  = $default_value;
            $file_name = '';

            if (is_array($raw_default_value)) {
                if (!empty($raw_default_value['file'])) {
                    $file_name = basename($raw_default_value['file']);
                } elseif (!empty($file_url)) {
                    $file_name = basename($file_url);
                }
            } elseif (is_string($raw_default_value) && !empty($raw_default_value)) {
                $file_name = basename($raw_default_value);
            }

            if (!empty($file_url) && !empty($file_name)) : ?>
                <div class="personal-current-file" style="margin-top: 8px; display: flex; align-items: center; gap: 6px;">
                    <span class="dashicons dashicons-media-document" style="color: #666; font-size: 18px; width: 18px; height: 18px;"></span>
                    <span><strong>Archivo actual:</strong> 
                        <a href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($file_name); ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <input type="<?php echo $html_type; ?>" 
                   id="<?php echo $name; ?>" 
                   name="<?php echo $name; ?>" 
                   placeholder="<?php echo $placeholder; ?>" 
                   value="<?php echo $default_value; ?>" 
                   class="regular-text personal-input">
        <?php endif; ?>
    </div>

</div>
