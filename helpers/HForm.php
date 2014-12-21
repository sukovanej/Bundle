<?php
class HForm {
	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	
	const TABLE_STYLE_DEFAULT = "default";
	
	const ERROR_REQUIRE = "e_require";
	
	private $OnErrorHandler;
	private $OnSubmitHandler;
	
	public function __construct($name, $method = self::METHOD_POST) {
		$this->Name = $name;
		$this->Attributes = array();
		$this->Classes = array();
		$this->Items = array();
		
		$this->Method = $method;
		$this->IsError = false;
	}
	
	public function getDataArray() {
		if ($this->Method == self::METHOD_POST)
			return $_POST;
		else
			return $_GET;
	}
	
	private function ReInstItemsValues() {
		foreach($this->Items as $item) {
			if ($item->Require && empty($this->getDataArray()[$item->Name])) {
				$func = $this->OnErrorHandler;
				$this->IsError = true;
				$func(self::ERROR_REQUIRE, $item, $this);
			} else {
				if ($item->Type == HFormItem::TYPE_CHECKBOX) {
					if (isset($this->getDataArray()[$item->Name]) && $this->getDataArray()[$item->Name] == $item->Value) {
						$item->Value = 1;
					} else {
						$item->Value = 0;
						$item->removeAttribute("checked");
					}
				} else {
					$item->Value = $this->getDataArray()[$item->Name];
				}
			}
		}
		
		return !$this->IsError;
	}
	
	public function addItem($item, $label = "") {
		$this->Items[$item->Name] = $item;
		return $this;
	}
	
	public function onSubmit($function) {
		$this->OnSubmitHandler = $function;
		
		return $this;
	}
	
	public function onError($function) {
		$this->OnErrorHandler = $function;
		return $this;
	}
	
	public function SaveAsConfiguration() {
		foreach ($this->Items as $item) {
			if ($item->Type != HFormItem::TYPE_SUBMIT) {
				HConfiguration::set($item->Name, $item->Value);
			}
		}
	}
	
	public function render($style = self::TABLE_STYLE_DEFAULT) {
		if (isset($this->getDataArray()["__HFormSubmitHelpInput_" . $this->Name])) {
			if($this->ReInstItemsValues()) {
				$func = $this->OnSubmitHandler;
				$func($this);
			}
		}
		
		$result = "\n" . '<form method="' . $this->Method . '" ' . $this->generateAttributesString() . ' ' . $this->generateClassesString() . ' >' . "\n\t" . '<table>';
		
		if ($style == self::TABLE_STYLE_DEFAULT)
			$style = "width:100px; white_space:nowrap; vertical-align:top;";
			
		foreach($this->Items as $item) {
			if ($item->Type == HFormItem::TYPE_NEW_TABLE) {
				$result .= $item->render();
				continue;
			}
			
			$result .= "\t<tr>\n\t\t<td style=\"" . $style . "\">" . $item->Label . "</td>\n\t\t<td>" . $item->render() . "</td>\n\t</tr>\n";
		}
			
		$result .= "\t</table>\n\t";
		$result .= '<input type="hidden" name="__HFormSubmitHelpInput_' . $this->Name . '" />' . "\n" . '</form>';
		echo $result;
	}
	
	private function generateClassesString() {
		if (count($this->Classes) != 0) {
			$result = "class='";
			
			$i = 0;
			foreach($this->Classes as $class) {
				$result .= $class;
				
				if (++$i != count($this->CLasses))
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
