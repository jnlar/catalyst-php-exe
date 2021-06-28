<?php

require_once 'vendor/autoload.php';

use League\Csv\Reader;

/*
* Class for handling CSV file operations, validation and string formatting
*/
class parse {
	private static $header;
	private $get_csv;
	private $csv;
	public static $user_data;

	/*
	* If file option is given, run CSV formatting methods
	*
	* @return methods
	*/
	public function __construct() {
		if (opt::$args->hasOpt('file')) {
			$this->get_file();
			$this->read_file();
			$this->get_records();
			$this->remove_duplicates();
		}
	}

	/*
	* Read csv file from path,
	*
	* @return array
	*/
	private function read_file() {
		try {
			$this->csv = Reader::createFromPath($this->get_csv);
			$this->csv->setHeaderOffset(0);
		} catch (Exception $e) {

			// NOTE: doesn't accomodate for invalid file format
			opt::error_die("red", "ERROR: No file specified with --file flag\n\n");
		}
	}

	/*
	* Get the value given to --file option
	*
	* @return string
	*/
	private function get_file() {
		return $this->get_csv = opt::$args->getOpt('file');
	}

	/*
	* Run email validation tests with --dry_run
	*
	*
	* @return string
	*/
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

  /*
  * Returns either true of false dependent on
  * whether the value to the email property is a valid email
  *
  * @param string $user
  * @return boolean
  */
	public static function check_valid_email($user) {
		if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
			return true;
		} else return false;
	}

	private function get_records() {
		self::$header = array_map('trim', $this->csv->getHeader());

		/*
		* Returns a 2 dimensional array where each element in the array is an
		* assosciative array containing formatted user data
		*/
		self::$user_data = array_map('self::return_formatted_data', iterator_to_array($this->csv->getRecords(self::$header)));
	}

  /*
  * Remove digits, spaces, special characters, tabs from strings
	* and lower all -> uppercase first character/s of strings
	*
  * @param string $user_names
  * @return string
  */
	private function clean_names($user_names) {
		$names = str_replace(' ',  '', ucfirst(strtolower($user_names)));
		return preg_replace('/[0-9!@#$%^&*()\t\-\+\-]/', '', $names);
	}

  /*
  * Remove line breaks, spaces and convert email string to lowercase
	*
  * @param string $email
  * @return string
  */
	private function clean_email($email) {
		return preg_replace("/[\n\s\-]/", '', strtolower($email));
	}

  /*
  * Create new formatted key value pairs for user details
	*
  * @param string $user_details
  * @return array
  */
	private function return_formatted_data($user_details) {
		return [
			'name' => $this->clean_names($user_details['name']),
			'surname' => $this->clean_names($user_details['surname']),
			'email' => $this->clean_email(strtolower($user_details['email']))
		];
	}

  /*
  * Remove duplicate elements in array
	*
  * @return array
  */
	private function remove_duplicates() {
		self::$user_data = array_unique(self::$user_data, SORT_REGULAR);
	}
}
