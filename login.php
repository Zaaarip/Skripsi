<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="img/unugiri.ico" />
    <title>APRESA - Sign In</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
  <div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-transparent mb-0">
          <h3 class="text-center"> <span class="font-weight-bold text-info"> Sign In</span></h3>
        </div>
        

        <div class="card-body">
          <form action="proses_login.php" method="POST">
          <div class="form-group row">
		    <label for="text" class="col-sm-3 col-form-label">Username</label>
		    <div class="col-sm-9">
			<input type="text" class="form-control" name="username">
		    </div>
	      </div>

        <div class="form-group row">
		  <label for="password" class="col-sm-3 col-form-label">Password </label>
		  <div class="col-sm-9">
			<input type="password" class="form-control" name="password">
		  </div>
	      </div>

        <br>
          <div class="form-group">
          <button type="submit" name="kirim" class="btn btn-info col-12" > 
            LOGIN
          </button>   
          </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>