<?php
	$form = new HForm("colorForm");
	
		$select = new HFormItem('DefaultThemeStyleType', HFormItem::TYPE_SELECT);
		$select
			->setValue(HConfiguration::Get("DefaultThemeStyleType"))
			->setLabel("Barva");
		
		$colors = array
		(
			"red" => "červená", 
			"blue" => "modrá",
			"green" => "zelená"
		);	
		
		foreach($colors as $color => $s_color) {
			$option = new HFormItem($color, HFormItem::TYPE_OPTION);
			$option->setValue($s_color);
			
			if (HConfiguration::Get("DefaultThemeStyleType") == $color)
				$option->setAttribute("selected");
			
			$select->addSubItem($option);
		}
		
		$submit = new HFormItem('submitButton', HFormItem::TYPE_SUBMIT);
		$submit->setValue("Uložit");
	
	$form
		->addItem($select)
		->addItem($submit)
		->onSubmit(
			function($obj) {
				global $select, $colors;
				$obj->SaveAsConfiguration();
				
				$i = 0;
				
				foreach($colors as $color => $s_color) {
					$option = new HFormItem($color, HFormItem::TYPE_OPTION);
					$option->setValue($s_color);
					
					$select->removeSubItem($i);
					$i++;
					
					if (HConfiguration::Get("DefaultThemeStyleType") == $color)
						$option->setAttribute("selected");
					
					$select->addSubItem($option);
				}
			}
		);
?>

<h2>Barevná varianta</h2>

<?php $form->render(); ?>
