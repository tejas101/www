<?php

	if (isset($_POST['typeofdata'])) {
		switch ($_POST['typeofdata']) {
			case 'mclists': echo displayResult(getMCLists($_POST['mydata'][1])); break;
			case 'styleslist': echo displayResult(getStylesList($_POST['mydata'][0])); break;
		}
	}
	
	function getMCLists($defaultlist) {
		$retval = array();
		foreach ($defaultlist as $key => $value) {
			$retval[] = array('text' => $value, 'value' => $key);
		}
		return $retval;
	}
	
	function getStylesList($defaultlist) {
		$retval = array();
		foreach ($defaultlist as $key => $value) {
			$retval[] = array('text' => $value[1], 'value' => $key);
		}
		return $retval;
	}
	
	function displayResult($res) {
		$retval = "";
		$retval .= "[";
		for ($i = 0; $i < count($res); $i++) {
			$retval .= "{";
			$retval .= "text: '" . $res[$i]['text'] . "',";
			$retval .= " value: '" . $res[$i]['value'] . "'";
			$retval .= "}";
			if ($i + 1 < count($res)) $retval .= ", ";
		}
		$retval .= "]";
		return $retval;
	}
?>