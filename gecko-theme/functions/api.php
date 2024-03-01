<?php
namespace GeckoTheme;

const API_NAMESPACE = 'learn-hunting/v1';

class Api {
	public $namespace = API_NAMESPACE;

	public function __construct() {
		add_action( 'rest_api_init', [$this, 'rest_api_init']);
	}

	public function rest_api_init() {
		return;
	}
}
