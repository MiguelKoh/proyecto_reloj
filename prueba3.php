<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="assets/plugins/jqueryui/jquery-ui.css">
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/plugins/jqueryui/jquery-ui.js"></script>
  <script>
   
  $( function() {
    $( "#datepicker" ).datepicker({
        format: "yyyy",
    viewMode: "years", 
    minViewMode: "years"
});
    $( "#datepicker" ).datepicker();
  } );

  </script>
</head>
<body>
<p>Date: <input type="text" id="datepicker"></p>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Document</title>
    

</head>
<body><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>

    <input type="text" id="datepicker" />

    <script>
$("#datepicker").datepicker( {
    format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
    minViewMode: "years"
});
</script>
</body>
</html>