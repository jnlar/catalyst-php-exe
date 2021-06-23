<?php
class Parse_file {
	private $valid_email;
	private $file;
	private static $user_details = [];
	public static $filtered_user_details = [];

	public function __construct($file) {
		$this->file = file($file);
	}

	public function test_formatting() {
		$this->split_string();
		$this->format_string();
		var_dump(self::$filtered_user_details);
	}

	private function split_string() {
		foreach ($this->file as $line) {
			array_push(self::$user_details, explode(",", $line));
		}
	}

	private function format_string() {
		foreach (self::$user_details as $line) {
			$line[0] = $this->clean_names($line[0]);
			$line[1] = $this->clean_names($line[1]);
			$line[2] = $this->clean_email($line[2]);
			$line[3] = $this->validate_email($line[2]);

			array_push(self::$filtered_user_details, $line);
		}
	}

	private function clean_names($names) {
		$string = str_replace(' ',  '', ucfirst(strtolower($names)));

		return preg_replace('/[!@#$%^&*()\t\-\+\-]/', '', $string);
	}

	private function clean_email($email) {
		$lower = preg_replace("/[\n\s\-]/", '', strtolower($email));
		return $lower;
	}

	private function validate_email($email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else return false;
	}
}
?>
