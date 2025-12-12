<?php
// This file is generated. Do not modify it manually.
return array(
	'personal-block' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/personal-block',
		'version' => '0.1.0',
		'title' => 'Bloque de Personal',
		'category' => 'widgets',
		'icon' => 'businessperson',
		'description' => 'Bloque para listar el personal.',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'orderBy' => array(
				'type' => 'string',
				'default' => 'date-desc'
			),
			'categories' => array(
				'type' => 'array',
				'default' => array(
					
				)
			),
			'columns' => array(
				'type' => 'number',
				'default' => 3
			)
		),
		'textdomain' => 'personal-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
