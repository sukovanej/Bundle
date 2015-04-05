<?php
class HFormItem {
	const TYPE_TEXT = 'text';
	const TYPE_PASSWORD = 'password';
	const TYPE_HIDDEN = 'hidden';
	const TYPE_RADIO = 'radio';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_SUBMIT = 'submit';
	const TYPE_RESET = 'reset';
	const TYPE_IMAGE = 'image';
	const TYPE_FILE = 'file';
	const TYPE_SELECT = 'select';
	const TYPE_OPTION = 'option';
	
	const TYPE_NEW_TABLE = 'new_table';
	
	const MODE_DATETIME = 'datetime';
	const MODE_EMAIL = 'email';
	const MODE_NUMBER = 'number';
	const MODE_PLAINTEXT = 'plaintext';
	
	const REQUIRE_ON = true;
	const REQUIRE_OFF = false;
	
	public function __construct($name, $type = self::TYPE_TEXT, $require = self::REQUIRE_OFF, $mode = self::MODE_PLAINTEXT) {
		$this->Attributes = array();
		$this->Classes = array();
		$this->SubItems = array();
		
		$this->Type = $type;
		$this->Mode = $mode;
		$this->Require = $require;
		$this->Name = $name;
		
		$this->Label = "";
		$this->Value = "";
		
		if ($this->Type == self::TYPE_OPTION) {
			$this->Text = "";
		} else if ($this->Type == self::TYPE_CHECKBOX) {
			$this->Value = $this->Name;
		}

		if (in_array($this->Type, array(self::TYPE_TEXT, self::TYPE_PASSWORD, self::TYPE_TEXTAREA, self::TYPE_SELECT))) {
			$this->addClass("form-control");
		} else if (in_array($this->Type, array(self::TYPE_SUBMIT, self::TYPE_RESET, self::TYPE_FILE))) {
			$this->addClass("btn");
			$this->addClass("btn-primary");
		}
	}
	
	public function setValue($value) {
		$this->Value = $value;
		return $this;
	}
	
	public function setLabel($label) {
		$this->Label = $label;
		return $this;
	}
	
	// Classes
	
	public function addClass($classname) {
		$this->Classes[] = $classname;
		return $this;
	}
	
	public function removeClass($classname) {
		unset($this->Classes[$classname]);
		return $this;
	}
	
	// Attributes
	
	public function setAttribute($attr, $value = "") {
		$this->Attributes[$attr] = $value;
		return $this;
	}
	
	public function removeAttribute($attr) {
		unset($this->Attributes[$attr]);
		return $this;
	}
	
	// SubItem
	
	public function addSubItem($item) {
		$this->SubItems[] = $item;
		return $this;
	}
	
	public function removeSubItem($item) {
		if ($item < count($this->SubItems)) {
			unset($this->SubItems[$item]);
			return $this;
		}
		
		return false;
	}
	
	// Render HTML code
	
	public function render() {
		if($this->Type == self::TYPE_TEXTAREA) {
			$result = '<textarea name="' . $this->Name . '" ' . $this->generateClassesString() . ' ' . $this->generateAttributesString() . '>' . $this->Value . '</textarea>';
		} else if ($this->Type == self::TYPE_SELECT) {
			$result = '<select name="' . $this->Name . '">' . "\n";
			foreach($this->SubItems as $item) {
				$result .= $item->render();
			}
			$result .= '</select>' . "\n";
		} else if ($this->Type == self::TYPE_OPTION) {
			$result = "\t\n" . '<option value="' . $this->Name . '"' . $this->generateClassesString() . ' ' . $this->generateAttributesString() . '>' . $this->Value  . '</option>';
		} else if ($this->Type == self::TYPE_NEW_TABLE) {
			$result = '</table><table ' . $this->generateClassesString() . '>';
		} else {
			$result = '<input name="' . $this->Name . '" type="' . $this->Type . '" ' . $this->generateClassesString() . ' ' . $this->generateAttributesString() . ' value="' . $this->Value . '" />';
		}
		
		return $result;
	}
	
	private function generateClassesString() {
		if (count($this->Classes) != 0) {
			$result = "class='";
			
			$i = 0;
			foreach($this->Classes as $class) {
				$result .= $class;
				
				if (++$i != count($this->Classes))
					$result .= " ";
			}
			
			return ($result . "'");
		} else {
			return "";
		}
	}
	
	private function generateAttributesString() {
		if (count($this->Attributes) != 0) {
				$result = "";
			foreach($this->Attributes as $attr => $val) {
				if (is_int($attr))
					$result .= $val . ' ';
				else
					$result .= $attr . '="' . $val . '" ';
			}
			
			return ($result);
		} else {
			return "";
		}
	}
}
