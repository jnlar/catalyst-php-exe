<?php
require_once 'vendor/autoload.php';

use League\Csv\Reader;

class parse {
	private static $header;
	private static $get_csv;
	private static $csv;
	public static $user_data;

	public function __construct() {
		if (get_opt::$args->hasOpt('file')) {
			self::get_file();
			self::read_file();
			self::get_records();
			self::remove_duplicate();
		}
	}

	private static function read_file() {
		try {
			self::$csv = Reader::createFromPath(self::$get_csv);
			self::$csv->setHeaderOffset(0);
		} catch (Exception $e) {
			die(get_opt::$cli->red("ERROR: No file specified with --file flag\n"));
		}
	}

	private static function get_file() {
		return self::$get_csv = get_opt::$args->getOpt('file');
	}

	public static function dry_run() {
		echo sprintf("Running validation tests on emails from \$data['email'] \n\n");

    foreach (self::$user_data as $user) {
			if (!self::check_valid_email($user)) {
				echo get_opt::$cli->green(sprintf("OK: %s \n", $user['email']));
			} else {
				echo get_opt::$cli->red(sprintf("INVALID: %s \n", $user['email']));
			}
    }
  }

	public static function check_valid_email($user) {
		if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
			return true;
		} else return false;
	}

	private static function get_records() {
		self::$header = array_map('trim', self::$csv->getHeader());
		self::$user_data = array_map(
			'self::reformat_user_data',
			iterator_to_array(self::$csv->getRecords(self::$header)));
	}

	private static function clean_names($user_names) {
		$names = str_replace(' ',  '', ucfirst(strtolower($user_names)));
		return preg_replace('/[!@#$%^&*()\t\-\+\-]/', '', $names);
	}

	private static function clean_email($email) {
		return preg_replace("/[\n\s\-]/", '', strtolower($email));
	}

	private static function reformat_user_data($user_details) {
		return [
			'name' => self::clean_names($user_details['name']),
			'surname' => self::clean_names($user_details['surname']),
			'email' => self::clean_email(strtolower($user_details['email']))
		];
	}

	public static function remove_duplicate() {
		self::$user_data = array_unique(self::$user_data, SORT_REGULAR);
	}
}
