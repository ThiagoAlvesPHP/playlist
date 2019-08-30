<?php
require 'Upload.php';
$sql = new Upload();

if (!empty($_POST['music'])) {
	$id = addslashes($_POST['music']);

	$music = $sql->getUploadUnique($id);
	echo json_encode($music);
}

if (!empty($_POST['duracao'])) {

	$music = $sql->getUploadUnique();
	echo json_encode($music);
}