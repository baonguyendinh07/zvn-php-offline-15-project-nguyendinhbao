<?php
class Validate
{

	// Error array
	private $errors	= array();

	// Source array
	private $source	= array();

	// Rules array
	private $rules	= array();

	// Result array
	private $result	= array();

	// Contrucst
	public function __construct($source)
	{
		$this->source = $source;
	}

	// Add rules
	public function addRules($rules)
	{
		$this->rules = array_merge($rules, $this->rules);
	}

	// Get error
	public function getError()
	{
		return $this->errors;
	}

	// Set error
	public function setError($element, $message)
	{
		$this->errors[$element] = $message;
	}

	// Get result
	public function getResult()
	{
		return $this->result;
	}

	// Add rule
	public function addRule($element, $type, $options = null, $required = true)
	{
		$this->rules[$element] = array('type' => $type, 'options' => $options, 'required' => $required);
		return $this;
	}

	// Run
	public function run()
	{
		foreach ($this->rules as $element => $value) {
			if ($value['required'] == true && trim($this->source[$element]) == null) {
				$this->setError($element, 'không được bỏ trống');
			} else {
				switch ($value['type']) {
					case 'int':
						$this->validateInt($element, $value['options']['min'], $value['options']['max']);
						break;
					case 'string':
						$this->validateString($element, $value['options']['min'], $value['options']['max']);
						break;
					case 'url':
						$this->validateUrl($element);
						break;
					case 'email':
						$this->validateEmail($element);
						break;
					case 'status':
						$this->validateStatus($element, $value['options']);
						break;
					case 'group':
						$this->validateGroupID($element, $value['options']);
						break;
					case 'username':
						$this->validateUsername($element, $value['options']);
						break;
					case 'password':
						$this->validatePassword($element, $value['options']);
						break;
					case 'date':
						$this->validateDate($element, $value['options']['start'], $value['options']['end']);
						break;
					case 'existRecord':
						$this->validateExistRecord($element, $value['options']);
						break;
					case 'string-notExistRecord':
						$this->validateString($element, $value['options']['min'], $value['options']['max']);
						$this->validateNotExistRecord($element, $value['options']);
						break;
					case 'email-notExistRecord':
						$this->validateEmail($element);
						$this->validateNotExistRecord($element, $value['options']);
						break;
					case 'file':
						$this->validateFile($element, $value['options']);
						break;
				}
			}
			if (!array_key_exists($element, $this->errors)) {
				$this->result[$element] = $this->source[$element];
			}
		}
		$eleNotValidate = array_diff_key($this->source, $this->errors);
		$this->result	= array_merge($this->result, $eleNotValidate);
	}

	// Validate Integer
	private function validateInt($element, $min = 0, $max = 0)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max)))) {
			$this->setError($element, 'không hợp lệ');
		}
	}

	// Validate String
	private function validateString($element, $min = 0, $max = 0)
	{
		$length = strlen($this->source[$element]);
		if ($length < $min) {
			$this->setError($element, 'quá ngắn');
		} elseif ($length > $max) {
			$this->setError($element, 'quá dài');
		} elseif (!is_string($this->source[$element])) {
			$this->setError($element, 'không hợp lệ');
		}
	}

	// Validate URL
	private function validateURL($element)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_URL)) {
			$this->setError($element, 'không hợp lệ');
		}
	}

	// Validate Email
	private function validateEmail($element)
	{
		if (!filter_var($this->source[$element], FILTER_VALIDATE_EMAIL)) {
			$this->setError($element, 'không hợp lệ');
		}
	}

	public function showErrors($key = true)
	{
		$xhtml = '<div class="alert alert-danger" role="alert"><ul style="padding: 0; margin: 0">';
		if ($key) {
			foreach ($this->errors as $key => $value) {
				if ($key == 'birthday') $key = "Ngày sinh";
				$xhtml .= '<li style="display:block; margin: 10px 0"><b>' . ucfirst($key) . '</b> ' . $value . '!</li>';
			}
		} else {
			foreach ($this->errors as $value) {
				$xhtml .= '<li style="display:block; margin: 10px 0">' . $value . '!</li>';
			}
		}
		$xhtml .= '</ul></div>';
		return $xhtml;
	}

	public function isValid()
	{
		if (count($this->errors) > 0) return false;
		return true;
	}

	// Validate Status
	private function validateStatus($element, $select)
	{
		if ($this->source[$element] != strval($select[0]) && $this->source[$element] != strval($select[1])) {
			$this->setError($element, 'vui lòng chọn trạng thái');
		}
	}

	// Validate GroupID
	private function validateGroupID($element, $options)
	{
		if (!in_array($this->source[$element], $options)) {
			$this->setError($element, 'vui lòng chọn nhóm');
		}
	}

	//Validate Username
	private function validateUsername($element, $options)
	{
		$pattern = '#^(?=.{' . $options['min'] . ',' . $options['max'] . '}$)(?![_.0-9])(?!.*[_.]{2})[a-z0-9._]+(?<![_.])$#';
		if (!preg_match($pattern, $this->source[$element])) {
			$this->setError($element, 'là không hợp lệ');
		};
	}

	// Validate Password
	private function validatePassword($element, $options)
	{
		// At least 1 number, at least 1 upper case
		$pattern = '#^(?=.*\d)(?=.*[A-Z]).*.{' . $options['min'] . ',' . $options['max'] . '}$#';
		// Php4567!
		if (!preg_match($pattern, $this->source[$element])) {
			$this->setError($element, 'không hợp lệ');
		};
	}

	// Validate Date
	private function validateDate($element, $start, $end)
	{
		// Start
		$arrDateStart 	= date_parse_from_format('d/m/Y', $start);
		$tsStart		= mktime(0, 0, 0, $arrDateStart['month'], $arrDateStart['day'], $arrDateStart['year']);

		// End
		$arrDateEnd 	= date_parse_from_format('d/m/Y', $end);
		$tsEnd			= mktime(0, 0, 0, $arrDateEnd['month'], $arrDateEnd['day'], $arrDateEnd['year']);

		// Current
		$arrDateCurrent	= date_parse_from_format('d/m/Y', $this->source[$element]);
		$tsCurrent		= mktime(0, 0, 0, $arrDateCurrent['month'], $arrDateCurrent['day'], $arrDateCurrent['year']);

		if ($tsCurrent < $tsStart || $tsCurrent > $tsEnd) {
			$this->setError($element, 'không hợp lệ');
		}
	}

	// Validate Exist record
	private function validateExistRecord($element, $options)
	{
		$database = $options['database'];

		$query	  = $options['query'];
		if ($database->isExist($query) == false) {
			$this->setError($element, 'Thông tin đăng nhập không đúng hoặc tài khoản chưa kích hoạt');
		}
	}

	// Validate Not Exist record
	private function validateNotExistRecord($element, $options)
	{
		$database = $options['database'];

		$query	  = $options['query'];	// SELECT id FROM user where username = 'admin'
		if ($database->isExist($query) == true) {
			$this->setError($element, 'đã tồn tại');
		}
	}

	// Validate File
	private function validateFile($element, $options)
	{
		if (!filter_var($this->source[$element]['size'], FILTER_VALIDATE_INT, array("options" => array("min_range" => $options['min'], "max_range" => $options['max'])))) {
			$this->setError($element, 'kích thước không phù hợp');
		}

		$ext = pathinfo($this->source[$element]['name'], PATHINFO_EXTENSION);
		if (in_array($ext, $options['extension']) == false) {
			$this->setError($element, 'phần mở rộng không phù hợp');
		}

		$ext = explode('/', $this->source[$element]['type'])[0];
		if ($options['fileType'] != $ext) {
			$this->setError($element, 'không phải là kiểu ' . $options['fileType']);
		}
	}
}
