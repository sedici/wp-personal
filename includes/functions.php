<?php
add_action( 'init', 'cptui_register_my_cpts_personal' );
function cptui_register_my_cpts_personal() {
/**
 * Post Type: Personal.
 */

$labels = array(
    "name" => __( "Personal", "" ),
    "singular_name" => __( "Persona", "" ),
    "menu_name" => __( "Personal", "" ),
    "all_items" => __( "Todo el Personal", "" ),
    "add_new" => __( "Agregar Personal", "" ),
    "add_new_item" => __( "Agregar nuevo Personal", "" ),
    "edit_item" => __( "Editar Personal", "" ),
    "new_item" => __( "Nuevo Personal", "" ),
    "view_item" => __( "Ver Personal", "" ),
    "view_items" => __( "Ver Personal", "" ),
    "search_items" => __( "Buscar Personal", "" ),
    "not_found" => __( "No se encontro el Personal", "" ),
    "not_found_in_trash" => __( "No se encontro el Personal en la papelera", "" ),
    "parent_item_colon" => __( "Personal Padre", "" ),
    "featured_image" => __( "Foto del Personal", "" ),
    "set_featured_image" => __( "Seleccionar la imagen", "" ),
    "remove_featured_image" => __( "Remover la imagen", "" ),
    "use_featured_image" => __( "Utilizar la imagen", "" ),
    "archives" => __( "Archivar al personal", "" ),
    "insert_into_item" => __( "Insert en Personal", "" ),
    "uploaded_to_this_item" => __( "Subir al personal", "" ),
    "filter_items_list" => __( "Filtrar lista de personal", "" ),
    "items_list_navigation" => __( "Navegación de la lista de personal", "" ),
    "items_list" => __( "Lista de Personal", "" ),
    "attributes" => __( "Atributos del Personal", "" ),
    "parent_item_colon" => __( "Personal Padre", "" ),
);

$args = array(
    "label" => __( "Personal", "" ),
    "labels" => $labels,
    "description" => "",
    "public" => true,
    "publicly_queryable" => true,
    "show_ui" => true,
    "show_in_rest" => false,
    "rest_base" => "",
    "has_archive" => "personal",
    "show_in_menu" => true,
    "exclude_from_search" => false,
    "capability_type" => "post",
    "capabilities" => array(
    'create_posts' => 'create_personal',
    'delete_others_posts' => 'delete_others_personales',
    'delete_private_posts' => 'delete_private_personales',
    'delete_published_posts' => 'delete_published_personales',
    'edit_private_posts' => 'edit_private_personales',
    'edit_published_posts' => 'edit_published_personales',
    'edit_post' => 'edit_personal',
    'edit_posts' => 'edit_personales',
    'edit_others_posts' => 'edit_other_personales',
    'publish_posts' => 'publish_personales',
    'read_post' => 'read_personal',
    'read_private_posts' => 'read_private_personales',
    'delete_post' => 'delete_personal'
),
    "map_meta_cap" => true,
    "hierarchical" => false,
    "rewrite" => array( "slug" => "personal", "with_front" => true ),
    "query_var" => true,
    "supports" => array( "title", "thumbnail" ),
    "taxonomies" => array( "categorias" ),
);

register_post_type( "personal", $args );
}
function cptui_register_my_taxes_categorias() {

    /**
     * Taxonomy: Categorias.
     */
    
    $labels = array(
        "name" => __( "Categorias", "" ),
        "singular_name" => __( "Categoria", "" ),
    );
    
    $args = array(
        "label" => __( "Categorias", "" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => true,
        "label" => "Categorias",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'categorias', 'with_front' => true, ),
        "show_admin_column" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => true,
    );
    register_taxonomy( "categorias", array( "personal" ), $args );
    }
    
    add_action( 'init', 'cptui_register_my_taxes_categorias' );

function add_theme_caps() {
// gets the administrator role
$admins = get_role( 'administrator' );
$admins->add_cap( 'create_personal' );
$admins->add_cap( 'delete_published_personales' );
$admins->add_cap( 'edit_published_personales' );
$admins->add_cap( 'edit_personal' );
$admins->add_cap( 'edit_personales' );
$admins->add_cap( 'publish_personales' );
$admins->add_cap( 'read_personal' );
$admins->add_cap( 'delete_personal' );
}
add_action( 'admin_init', 'add_theme_caps');
add_action( 'add_meta_boxes', 'personal_custom_metabox' );
function personal_custom_metabox() {
    add_meta_box( 'personal_meta', __( 'Información del personal', 'personal' ), 'personal_display_callback', 'personal' );
} 
function personal_display_callback($post){
    prueba();
    $web1 = get_post_meta( $post->ID, 'web1', true );
	$web2 = get_post_meta( $post->ID, 'web2', true );
	
	// Usaremos este nonce field más adelante cuando guardemos en twp_save_meta_box()
	wp_nonce_field( 'mi_meta_box_nonce', 'meta_box_nonce' );
	?>
	<div class="acf-label">
<label for="acf-field_59dd22c252521"><img src="http://produccion.uids.testing.sedici.unlp.edu.ar/wp-content/themes/vantage_child/images/email.png" height="32"> Email </label><p class="description">Correo electrónico
	</p></div>
<div class="acf-input">
<div class="acf-input-wrap"><input type="email" id="acf-field_59dd22c252521" name="acf[field_59dd22c252521]" value="romina.couselo@ing.unlp.edu.ar" placeholder="Email" /></div></div>
</div>
<?php
	echo '<p><label for="web1_label">Web de referencia 1</label> <input type="text" name="web1" id="web1" value="'. $web1 .'" /></p>';
	echo '<p><label for="web2_label">Web de referencia 2</label> <input type="text" name="web2" id="web2" value="'. $web2 .'" /></p>';
}
function register_field_group($args){
    $input='';
    foreach ($args as  $value) {
        $input.="<div class='personal-label'>".$value['label']."</div>";
    }
    var_dump($input);die;

}
function prueba()
{ 
register_field_group( array (
        array (
            'key' => 'field_59dd22c252521',
            'label' => '<img src="'.plugins_url().'/personal/images/email.png" height="32"> Email ',
            'name' => 'email',
            'type' => 'email',
            'instructions' => 'Correo electrónico',
            'default_value' => '',
            'placeholder' => 'Email',
            'prepend' => '',
            'append' => '',
        ),
        array (
            'key' => 'field_59dd22fb52522',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/tel.png" height="32">	Teléfono',
            'name' => 'telefono',
            'type' => 'text',
            'instructions' => 'Teléfono',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd232d52523',
            'label' => 'Unidad de investigación',
            'name' => 'unidad_de_investigacion',
            'type' => 'text',
            'instructions' => 'Unidad de investigación a la que pertenece',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd235252524',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/grado_alcanzado.png" height="32">	 Grado Alcanzado',
            'name' => 'grado_alcanzado',
            'type' => 'text',
            'instructions' => 'Grado alcanzado',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59e0fba85c047',
            'label' => 'Categoría',
            'name' => 'categoria',
            'type' => 'taxonomy',
            'taxonomy' => 'categorias',
            'field_type' => 'multi_select',
            'allow_null' => 0,
            'load_save_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array (
            'key' => 'field_59dd238952525',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/google_scholar.png" width="32" height="32"> Google Scholar ',
            'name' => 'google_scholar',
            'type' => 'text',
            'instructions' => 'http://scholar.google.com/citations?user=xxxxxx',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd241852526',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/orcid.gif" width="32" height="32"> ORCID',
            'name' => 'orcid',
            'type' => 'text',
            'instructions' => 'https://orcid.org/xxxx-xxxx-xxxx-xxxx',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd244f52527',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/research-gate.png" width="32" height="32"> ResearchGate',
            'name' => 'researchgate',
            'type' => 'text',
            'instructions' => 'https://www.researchgate.net/profile/xxxxxxx',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd247f52528',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/sedici.png"	height="32"> SEDICI',
            'name' => 'sedici',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'none',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd24ac52529',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/cic_digital.png" height="32"> CIC',
            'name' => 'cic',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'none',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd24b65252a',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/conicet-digital.png" height="40"> CONICET',
            'name' => 'conicet',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'none',
            'maxlength' => '',
        ),
        array (
            'key' => 'field_59dd25286cb01',
            'label' => 'Foto',
            'name' => 'foto',
            'type' => 'image',
            'save_format' => 'object',
            'preview_size' => 'thumbnail',
            'library' => 'all',
        ),
        array (
            'key' => 'field_59dd25596cb02',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/biography.png" height="32">	Biografía',
            'name' => 'biografia',
            'type' => 'wysiwyg',
            'default_value' => '',
            'toolbar' => 'full',
            'media_upload' => 'yes',
        ),
        array (
            'key' => 'field_59dd25736cb03',
            'label' => '<img src="'.get_stylesheet_directory_uri().'/images/cv.png" height="32">	Curriculum Vitae',
            'name' => 'curriculum_vitae',
            'type' => 'file',
            'save_format' => 'object',
            'library' => 'all',
        )
        ));
}

add_action("manage_posts_custom_column",  "personal_custom_columns");

add_filter( 'manage_edit-personal_columns', 'columnas_post_type_personal' ) ;

function columnas_post_type_personal( $columnas ) {

$columnas = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Nombre',
    'email' => 'Email',
    'grado_alcanzado' => 'Grado Alcanzado',
    'foto'=>'Foto',
    'unidad_de_investigacion'=> 'Unidad de Investigación',
'redes'=> 'Redes sociales',
    'author' => 'Autor',
    'date' => 'Fecha'
);

return $columnas;
}
function personal_custom_columns($column){
global $post;
$custom = get_post_custom();

switch ($column) {
case "email":
  echo $custom["email"][0];
  break;
case "unidad_de_investigacion":
  echo $custom["unidad_de_investigacion"][0];
  break;
case "grado_alcanzado":
  echo $custom["grado_alcanzado"][0];
  break;
case "foto":
  echo wp_get_attachment_image( $custom['foto'][0], array('100', '100'));
  break;
case "redes":
  if( !empty($custom['google_scholar'][0]) ):
echo '<a href="'.$custom['google_scholar'][0].'"><img src="'.get_stylesheet_directory_uri().'/images/google_scholar.png" width=32 height=32></a>';
  endif;
  if( !empty($custom['researchgate'][0]) ):
echo '<a href="'.$custom['researchgate'][0].'"><img src="'.get_stylesheet_directory_uri().'/images/research-gate.png" width=32 height=32></a>';
  endif;
  if( !empty($custom['orcid'][0]) ):
echo '<a href="'.$custom['orcid'][0].'"><img src="'.get_stylesheet_directory_uri().'/images/orcid.gif" width=32 height=32></a>';
  endif;
  if( !empty($custom['sedici'][0]) ):
echo '<img src="'.get_stylesheet_directory_uri().'/images/sedici.png"  height=32>';
  endif;
  if( !empty($custom['cic'][0]) ):
echo '<img src="'.get_stylesheet_directory_uri().'/images/cic_digital.png" height=32>';
  endif;
  if( !empty($custom['conicet'][0]) ):
echo '<img src="'.get_stylesheet_directory_uri().'/images/conicet-digital.png" height=32>';
  endif;
  if( !empty($custom['curriculum_vitae'][0]) ):
echo  '<a href="'.wp_get_attachment_url( $custom['curriculum_vitae'] [0]  ).'"><img src="'.get_stylesheet_directory_uri().'/images/cv.png" width=32 height=32></a>';
  endif;
  break;
}
}

add_filter( 'manage_edit-personal_sortable_columns', 'sort_personal' );
function sort_personal( $columnas ) {

$columnas['email'] = 'email_ord';

return $columnas;
}






// add_action('init','post_type_gatos');

// function post_type_gatos(){
//     //POST TYPE Gato
//     $labels = array(
// 		'name' => _x('Gatos', 'post type general name', THEMEDOMAIN),
// 		'singular_name' => _x('Gato', 'post type singular name', THEMEDOMAIN),
// 		'add_new' => _x('Añadir nuevo', 'book', THEMEDOMAIN),
// 		'add_new_item' => __('Añadir nuevo Gato', THEMEDOMAIN),
// 		'edit_item' => __('Editar Gato', THEMEDOMAIN),
// 		'new_item' => __('Nuevo Gato', THEMEDOMAIN),
// 		'view_item' => __('Ver Gato', THEMEDOMAIN),
// 		'search_items' => __('Buscar Gatos', THEMEDOMAIN),
// 		'not_found' =>  __('No se han encontrado gatos', THEMEDOMAIN),
// 		'not_found_in_trash' => __('No se han encontrado gatos en la papelera', THEMEDOMAIN), 
// 		'parent_item_colon' => ''
// 		);
//     $args = array(
// 		'labels' => $labels,
// 		'public' => true,
// 		'publicly_queryable' => true,
// 		'show_ui' => true, 
// 		'query_var' => true,
// 		'rewrite' => true,
// 		'capability_type' => 'post',
// 		'hierarchical' => true,
// 		'menu_position' => null,
// 		'supports' => array('title','editor', 'thumbnail', 'excerpt'),
// 		'menu_icon' => get_stylesheet_directory_uri().'/functions/images/sign.png'
// 		);
//     register_post_type( 'gatos', $args );
    
//     //TAXONOMIA DEL TIPO DE GATO
//     $labels = array(			  
//             'name' => _x( 'Tipos de Gato', 'taxonomy general name', THEMEDOMAIN ),
//             'singular_name' => _x( 'Tipo de Gato', 'taxonomy singular name', THEMEDOMAIN ),
//             'search_items' =>  __( 'Buscar tipo de Gato', THEMEDOMAIN ),
//             'all_items' => __( 'Todos los tipos de Gato', THEMEDOMAIN ),
//             'parent_item' => __( 'Tipo de Gato padre', THEMEDOMAIN ),
//             'parent_item_colon' => __( 'Tipo de Gato padre:', THEMEDOMAIN ),
//             'edit_item' => __( 'Editar tipo de Gato', THEMEDOMAIN ), 
//             'update_item' => __( 'Actualizar tipo de Gato', THEMEDOMAIN ),
//             'add_new_item' => __( 'Añadir nuevo tipo de Gato', THEMEDOMAIN ),
//             'new_item_name' => __( 'Nuevo nombre de tipo de Gato', THEMEDOMAIN ),
//             ); 							  

//     register_taxonomy(
//             'Tipo',
//             'gatos',
//             array(
//                     'public'=>true,
//                     'hierarchical' => true,
//                     'labels'=> $labels,
//                     'query_var' => true,
//                     'show_ui' => true,
//                     'rewrite' => array( 'slug' => 'perros', 'with_front' => false ),
//                     )
//             );
// }