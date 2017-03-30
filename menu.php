<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $stmt = $conn->prepare("SELECT * from `menu`");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $menu = [];
    foreach ($stmt->fetchAll() as $k => $v) { 
        $menu[] = $v;
    }

} catch (exception $e) {

}

function tree(&$menu, $parentId = 0) {

	if (!$parentId) {
		//its parent
		$opener = "<ul>";
		$closer = "</ul>";
		$inner = "";

		foreach ($menu as $link) {
			if (!$link['parent_id']) {
				$inner .= "<li>";
				$inner .= $link['title'];
				$inner .= tree($menu, $link['id']);
				$inner .= "</li>";
			}
		}

		return $inner ? ($opener . $inner . $closer) : '';

	} else {
		//its child
		$opener = "<ul>";
		$closer = "</ul>";
		$inner = "";

		foreach ($menu as $link) {
			if ($link['parent_id'] == $parentId) {
				$inner .= "<li>";
				$inner .= $link['title'];
				$inner .= tree($menu, $link['id']);
				$inner .= "</li>";
			}
		}

		return $inner ? ($opener . $inner . $closer) : '';
	}

}


function tree2(&$menu, $parentId = 0) {

	if (!$parentId) {
		//its parent
		$opener = "<ul class=\"parent\">";
		$closer = "</ul>";
		$inner = "";

		foreach ($menu as $link) {
			if (!$link['parent_id']) {
				$inner .= "<li id=\"{$link['id']}\" data-pid=\"{$link['parent_id']}\">";
				$inner .= $link['title'];
				$inner .= tree2($menu, $link['id']);
				$inner .= "</li>";
			}
		}

		return $inner ? ($opener . $inner . $closer) : '';

	} else {
		//its child
		$opener = "<ul class=\"child\" data-pid=\"{$parentId}\">";
		$closer = "</ul>";
		$inner = "";

		foreach ($menu as $link) {
			if ($link['parent_id'] == $parentId) {
				$inner .= "<li id=\"{$link['id']}\" data-pid=\"{$link['parent_id']}\">";
				$inner .= $link['title'];
				$inner .= tree2($menu, $link['id']);
				$inner .= "</li>";
			}
		}

		return $inner ? ($opener . $inner . $closer) : '';
	}

}

//echo tree($menu);
echo tree2($menu);