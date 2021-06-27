<?php
require_once 'vendor/autoload.php';

use League\Csv\Reader;

class parse {
	private static $header;
	private $get_csv;
	private $csv;
	public static $user_data;

	public function __construct() {
		if (opt::$args->hasOpt('file')) {
			$this->get_file();
			$this->read_file();
			$this->get_records();
			$this->remove_duplicates();
		}
	}

	private function read_file() {
		try {
			$this->csv = Reader::createFromPath($this->get_csv);
			$this->csv->setHeaderOffset(0);
		} catch (Exception $e) {

			// NOTE: doesn't accomodate for invalid file format
			opt::error_die("red", "ERROR: No file specified with --file flag\n\n");
		}
	}

	private function get_file() {
		return $this->get_csv = opt::$args->getOpt('file');
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

	private function get_records() {
		self::$header = array_map('trim', $this->csv->getHeader());

		/*
		* returns a 2 dimensional array where each element in the array is an
		* assosciative array containing formatted user data
		*/
		self::$user_data = array_map('self::return_formatted_data', iterator_to_array($this->csv->getRecords(self::$header)));
	}

	// remove digits, spaces, special characters, tabs from strings and lower all -> uppercase first character/s of strings
	private function clean_names($user_names) {
		$names = str_replace(' ',  '', ucfirst(strtolower($user_names)));
		return preg_replace('/[0-9!@#$%^&*()\t\-\+\-]/', '', $names);
	}

	// remove line breaks, spaces and convert email string to lowercase
	private function clean_email($email) {
		return preg_replace("/[\n\s\-]/", '', strtolower($email));
	}

	// create new formatted key value pairs for user details
	private function return_formatted_data($user_details) {
		return [
			'name' => $this->clean_names($user_details['name']),
			'surname' => $this->clean_names($user_details['surname']),
			'email' => $this->clean_email(strtolower($user_details['email']))
		];
	}

	private function remove_duplicates() {
		self::$user_data = array_unique(self::$user_data, SORT_REGULAR);
	}
}
