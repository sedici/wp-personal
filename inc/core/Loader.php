<?php

namespace Personal\Inc\Core;

/**
 * Registra actions y filters del plugin
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */

/**
 * Administra una lista de hooks y los ejecuta
 */
class Loader {

	/**
	 *  Array de acciones.
	 *
	 * @var      array    $actions
	 */
	protected $actions;

	/**
	 * Array de filters
	 *
	 * @var      array    $filters
	 */
	protected $filters;



	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Agrega un action hook para ejecutarse después en wordpress
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
     * Agrega un filter hook para ejecutarse después en wordpress
     */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}


	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);

		return $hooks;

	}

	/**
	 * Registra filters y actions en Wordpress.
	 *
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

}
