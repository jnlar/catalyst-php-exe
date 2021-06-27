<?php
require_once 'vendor/autoload.php';

use League\Csv\Reader;

class parse {
	private static $header;
	private static $get_csv;
	private static $csv;
	public static $user_data;

	public function __construct() {
		if (opt::$args->hasOpt('file')) {
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

			// NOTE: doesn't accomodate for invalid file format
			opt::error_die("red", "ERROR: No file specified with --file flag\n\n");
		}
	}

	private static function get_file() {
		return self::$get_csv = opt::$args->getOpt('file');
	}

	public static function dry_run() {
		echo sprintf("Running validation tests on emails from \$csv['email']\n\n");

    foreach (self::$user_data as $user) {
			if (!self::check_valid_email($user)) {
				opt::print_color("green", "OK: %s \n", $user['email']);
			} else {
				opt::print_color("red", "INVALID: %s \n", $user['email']);
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
		self::$user_data = array_map('self::return_formatted_data', iterator_to_array(self::$csv->getRecords(self::$header)));
	}

	// remove digits, special characters, tabs from string and lower -> uppercase first character of strings
	private static function clean_names($user_names) {
		$names = str_replace(' ',  '', ucfirst(strtolower($user_names)));
		return preg_replace('/[0-9!@#$%^&*()\t\-\+\-]/', '', $names);
	}

	// remove line breaks, spaces and convert email string to lowercase
	private static function clean_email($email) {
		return preg_replace("/[\n\s\-]/", '', strtolower($email));
	}

	private static function return_formatted_data($user_details) {
		return [
			'name' => self::clean_names($user_details['name']),
			'surname' => self::clean_names($user_details['surname']),
			'email' => self::clean_email(strtolower($user_details['email']))
		];
	}

	// NOTE: do we need a function for this? probably better to have, idk :^)
	private static function remove_duplicate() {
		self::$user_data = array_unique(self::$user_data, SORT_REGULAR);
	}
}
