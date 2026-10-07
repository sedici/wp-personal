<?php
// This file is generated. Do not modify it manually.
return array(
	'personal-block' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/wp-personal-block',
		'version' => '0.1.0',
		'title' => 'Bloque de Personal',
		'category' => 'widgets',
		'icon' => 'businessperson',
		'description' => 'Lista el personal en Cartas, Lista o Tabla.',
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
			),
			'layout' => array(
				'type' => 'string',
				'enum' => array(
					'carta',
					'lista',
					'tabla'
				),
				'default' => 'carta'
			),
			'campos' => array(
				'type' => 'array',
				'default' => array(
					'foto',
					'rol',
					'grado',
					'unidad',
					'afiliaciones',
					'redes'
				)
			)
		),
		'textdomain' => 'personal-block',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => array(
			'file:./style-index.css',
			'personal-list-metabox',
			'personal-list-lista',
			'personal-list-table'
		),
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
