<?php

//import the portable database.....not attached
/* this connects to database using PDO */
function connect(){
	global $dbh;
	$dsn = 'mysql:dbname=otas;host=127.0.0.1';
	$user = 'root';
	$password = '';
	$dbh = new PDO($dsn, $user, $password);
	}

/* Execute a prepared statement by passing an array of values*/
function get_questions($cos, $ids){
	global $dbh;
	$qMarks = str_repeat('?,', count($ids) - 1) . '?';
	$sql = "SELECT question, optiona, optionb, optionc, optiond FROM $cos where id IN ($qMarks)";
	$sth = $dbh->prepare($sql);
	$sth->execute($ids);
	$data = $sth->fetchAll(PDO::FETCH_OBJ);
    //$sth->closeCursor();

    //header("content-type:application/json");
    return json_encode($data);
    
}
$cos = 'pmp';
$id = [10, 15, 20, 25, 30, 35, 40, 45, 50];
connect();
$ans = get_questions($cos, $id);

?>
<html>
<head>
<meta charset="utf-8">
<title>Test Interface</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <!-- Include roboto.css to use the Roboto web font, material.css to include the theme and ripples.css to style the ripple effect -->
    <link href="css/roboto.min.css" rel="stylesheet">
    <link href="css/material.min.css" rel="stylesheet">
        <link href="css/ripples.min.css" rel="stylesheet">
      <script src="js/jquery-1.10.2.min.js"></script>
	   <script src="js/bootstrap.min.js"> </script>
		<script src="js/ripples.min.js"> </script>
        <script src="js/material.min.js"> </script>
	<script src="js/amplify.core.min.js"> </script>
	<script src="js/amplify.store.min.js"> </script>
	<script src="js/slick.js"> </script>
	<style type="text/css">
	body{
		background: #fff;
	}
	label{
		font-size: 1.2em;
	}
	#wrapper{
		max-width: 960px;
		margin: 30px auto;
		padding: 20px;
		border: 1px solid #a3a3a3;
	}
	#mainfocus{
		border: solid 1px #a3a3a3;	
	}
	</style>
</head>
<body>
<div id="wrapper" class="well lg-well">
<header id="header">
<div class="text-right"><button class="btn btn-lg btn-danger">Time Left</button></div>
<h3 id="qtn_title">1 of 10</h3>
</header>
<hr/>

<form id="quest">

</form>
<div class="progress">
  <div class="progress-bar-info progress-bar-striped active" role="progressbar" style="width: 2%; line-height: 20px">60 0f 100</div>
	</div>


<div class="row">
	<div class="col-sm-6">
		<button class="btn btn-primary" id="prev" disabled> Previous</button>
		<button class="btn btn-info" id="next">Next</button>
	</div>
	<div class="col-sm-6 text-right">
		<button class="btn btn-default">16 answered</button>
		<button type="submit" class="btn btn-danger" id="submitBtn" disabled>Submit</button>
	</div>
</div>


</div>
<script>
var questions = <?php echo $ans; ?>;
var noq = questions.length;
//amplify.store('quest', questions);//store expiry and others
//var quest = amplify.store('quest');

	function display(){
		var q = 0;
		  for(var i in questions){
		  	q++;
 	 $("#quest").append("<div>"+ "<p class='lead'><span class='big'>" + "</span>"+questions[i].question+ "</p>" + 
 	 								"<div class='radio radio-info'><label><input type='radio' name='Q"+ q +"' value='A'> A. "+ questions[i].optiona + "</label></div>" +
 	 								"<div class='radio radio-info'><label><input type='radio' name='Q"+ q +"' value='B'> A. "+ questions[i].optionb + "</label></div>" +
 	 								"<div class='radio radio-info'><label><input type='radio' name='Q"+ q +"' value='C'> A. "+ questions[i].optionc + "</label></div>" +
 	 								"<div class='radio radio-info'><label><input type='radio' name='Q"+ q +"' value='D'> A. "+ questions[i].optiond + "</label></div>" +
 	 										" </div>");
 	 						}

		 		}
		
	function nextQuest(qtn){
		//next slide
		$('#quest').slick('slickNext');
		var currentSlide = $('#quest').slick('slickCurrentSlide');
  		//enable prev button
		$('#prev').prop('disabled', false);
  		//enable submit button
  		
  		//update title
  		qtn--;
  		if(currentSlide >= qtn){
  			$('#next').prop('disabled', true);
  			$('#submitBtn').prop('disabled', false);
  			}
  		
					}
	function prevQuest(){
		$('#quest').slick('slickPrev');
		// var currentSlide = $('#quest').slick('slickCurrentSlide');
		// $('#next').prop('disabled', false);
		// 	if(currentSlide < 1){
  // 			$('#prev').prop('disabled', true);
  			
  // 			}
		
	}

	function postSlide(qtn){
		//get current slide
		var currentSlide = $('#quest').slick('slickCurrentSlide');
		//write the question number
		currentSlide++;
		percent = currentSlide * 100 / qtn;
		percent = percent.toString() + '%';
		var title = currentSlide + ' of ' + qtn;
		$("#qtn_title").replaceWith("<h3 id='qtn_title'>" + title + "</h3>");
		qtn--;
		//alert(title);
		$('.progress div').css({'width': percent});;
		if(currentSlide > qtn){
  			$('#next').prop('disabled', true);
  			$('#submitBtn').prop('disabled', false);
  			} else if (currentSlide <= 1){
  			$('#prev').prop('disabled', true);
  			} else {
  				$('#prev').prop('disabled', false);
  				$('#next').prop('disabled', false);
  			}

	}

</script>
<script type="text/javascript" src="js/material.min.js"></script> 
<script>		
$(document).ready(function(){
	$.material.init();
	//var start = 1;
	$('#prev').prop('disabled', true);
	$('#next').prop('disabled', false);
	$('#submitBtn').prop('disabled', true);
	display();
  $('#quest').slick({
  	arrows: false,
  	focusOnSelect: true,
  	infinite: false,
  });
  $('#next').click(function(){
  	$('#quest').slick('slickNext');
  	postSlide(noq);
  		});
  $('#prev').click(function(){
  	$('#quest').slick('slickPrev');
  	postSlide(noq);
  });
  
	
 
});

	 </script>

</body>
</html>
