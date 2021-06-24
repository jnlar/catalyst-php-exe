<?php
require_once 'vendor/autoload.php';

use League\Csv\Reader;

class parse {
	private static $get_csv;
	public static $csv;
	public static $user_data;

	public function __construct() {
		self::get_file();
		self::read_file();
	}

	private static function read_file() {
		try {
			self::$csv = Reader::createFromPath(self::$get_csv);
			self::$csv->setHeaderOffset(0);


		} catch (Exception $e) {
			die($e->getMessage().PHP_EOL);
		}
		var_dump(self::$csv);
	}

	private static function get_file() {
		self::$get_csv = get_opt::$args->getOpt('file');
		var_dump(self::$get_csv);
	}

	private static function filter_santize_string($data) {
		return trim(filter_var($data, FILTER_SANIZE_STRING));
	}

	private static function format_names($data) {
		return ucfirst(strtolower($data));
	}

	private static function stoud_invalid_email($user) {
		echo sprintf("Invalid Email: %s \n", $user['email']);
	}

	private static function reformat_user_data($user) {
		return [
			'name' => self::format_names(self::filter_sanitize_string($user['name'])),
			'surname' => self::format_names(self::filter_sanitize_string($user['surname'])),
			'email' => self::filter_santize_string(strtolower($user['email']))
		];
	}

	private static function is_valid_email($user) {
		return !filter_var($user['email'], FILTER_VALIDATE_EMAIL);
	}
}
