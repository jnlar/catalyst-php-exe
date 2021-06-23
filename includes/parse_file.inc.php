<?php
class Parse_file {
	private $file;
	private $user_details = [];

	public function __construct($file) {
		$this->file = file($file);
	}

	public function split_string() {
		foreach ($this->file as $line) {
			array_push($this->user_details, explode(",", $line));
		}

		var_dump($this->split_line);
	}
}
?>
