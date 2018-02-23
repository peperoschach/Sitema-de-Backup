<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Sistema de Backup</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
</head>
<body style="background-color: #f9f9f9">
  <div class="container">
    <div class="row">
      <div class="jumbotron mt-3">
        <br>
        <h1>Sistema de Backup de sites e banco de dados</h1>
        <p class="lead">Efetue o backup de seu banco de dados.</p>
        <br>
        <button onclick="GerarBackup();" class="btn btn-primary">Gerar Backup &raquo;</button>
        <br>
        <div class="alert"></div>
      </div>
    </div>
  </div>
 <script>
		function GerarBackup() {
			$.get('backup_aula.php?f=backup', {
				
			}, function (data) {
				if(data.status == 200) {
					$('.alert').html('Backup gerado com sucesso');
				} else if(data.status == 400){
					$('.alert').html('Não foi possível gerar o backup');
				}
			});
		}
	</script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>