

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="col-md-4 col-md-offset-2" style="padding-top: 100px;">
       
            <div class="panel-heading"><center><h1>Welkom <?php echo $_SESSION["user"]->name; ?></h1></center></div>
            <ul class="list-group">
                <button class="btn btn-primary btn-lg btn-block">Incident melden</button>
                <button class="btn btn-primary btn-lg btn-block">Status van uw lopende incidenten bekijken</button>
                <button class="btn btn-primary btn-lg btn-block">Alle incidenten inzien</button>
            </ul>
        
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
