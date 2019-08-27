<?php
require 'Upload.php';
$sql = new Upload();
//TODAS AS MÚSICAS
$dados = $sql->getUpload();
$music = $sql->getUploadUnique();
$count = $sql->countMusicas();

if (isset($_FILES['arquivo'])) {
	//VERIFICA SE ALGUM ARQUIVO FOI ENVIADO
	if ($_FILES['arquivo']['size'][0] > 0) {
		$total = count($_FILES['arquivo']['tmp_name']) + intval($count['c']);
		//PERMITE SALVAR NO MAXIMO 10 ARQUIVOS NO DB
		if ($total <= 10) {
			for ($i=0; $i < count($_FILES['arquivo']['tmp_name']); $i++) {
				//VERIFICA SE ESTA NO FORMATO MP3
				if ($_FILES['arquivo']['type'][$i] == 'audio/mp3') {
					$arquivos = md5($_FILES['arquivo']['name'].time().rand(0,999)).'.mp3'; 
					$descricao = $_FILES['arquivo']['name'][$i];

					move_uploaded_file($_FILES['arquivo']['tmp_name'][$i], 'arquivos/'.$arquivos);
					$sql->setUpload($arquivos, $descricao);
					header('Location: index.php');
				} else {
					$alert = '<script>alert("1 ou mais arquivos não esta no formato MP3!"); window.location.href="index.php";</script>';
				}
			}
		} else {
			$alert = '<script>alert("Você só pode salvar no maximo 10 arquivos!"); window.location.href="index.php";</script>';
		}
	} else {
		$alert = '<script>alert("Nenhum Arquivo Enviado!"); window.location.href="index.php";</script>';
	}
}

//DELETANDO IMAGEM DO DB E DA PASTA
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id = addslashes($_GET['id']);
	$arquivo = $sql->getUploadUnique($id);
	unlink('arquivos/'.$arquivo['musica']);

	$sql->delUpload($id);
	header('Location: index.php');
}

if (isset($alert)) {
	echo $alert;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" href="favicon.png" />
	<title>PLAYLIST</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
<div class="container">
	<h3>Crie sua Playlist</h3>
	<form method="POST" enctype="multipart/form-data">
		Arquivos:<br>
		<input type="file" class="form-control" name="arquivo[]" multiple="" required=""><br>
		<button class="btn btn-primary btn-block btn-lg">Enviar</button>
	</form>
	<hr>
	<div class="row text-center">
		
		<audio id="music" src="arquivos/<?=$music['musica']; ?>" controls="controls" autoplay="autoplay">
		</audio>

		<div id="descricao"><?=str_replace('.mp3', '', $music['descricao']); ?></div>

		<hr>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Músicas</th>
						<th width="100">Excluir</th>
					</tr>
				</thead>
			
				<?php if (!empty($dados)): ?>
				<?php foreach ($dados as $m): ?>
				<tbody>
					<tr>
						<td>
							<button value="<?=$m['id']; ?>" class="btn btn-block btn-default music"><?=htmlspecialchars(str_replace(".mp3", "", $m['descricao'])); ?></button>
						</td>
						<td>
							<a class="btn btn-danger btn-block" href="?id=<?=$m['id']; ?>">Deletar</a>
						</td>
					</tr>
				</tbody>
				<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>
</body>
</html>