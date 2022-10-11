<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<?php
		/*  Created by Stephen Schneider
		 *	Creates a web page that shows a Wordoku puzzle and solution.
		 *	Page is accessed by a get request and does not require to be created from the index page.
		 *	Index page is designed to send the user to this page by URL after inputing information with no errors.
		 *	A blank puzzle will be generated if the wrong information is provided and fails validation and/or default values.
		 */
		//session_start();
		 


$nav_selected = "LIST";
$left_buttons = "NO";
$left_selected = "";

//require("word_processor.php");
include("./nav.php");
require("Wordoku.php");
require("word_processor.php");
$ini = parse_ini_file('config.ini');
$pageNum = 0;
$hasImages = $_GET['hasImages'];

  $sleep = true;
  $touched = isset($_POST['ident']);
  if (!$touched) {
	  if(isset($_GET["size"])){
			
		$size = $_GET["size"];}
 //else{$size =""}
		if(isset($_GET["word"])){
			$word = $_GET["word"];
			
		}
		else{
			$word = "ABCD";
		}
//$size ="";
		if(isset($_GET["difficulty"])){
			
		$difficulty = $_GET["difficulty"];}
		if(isset($_GET["hidecount"])){
			
		$hide =$hiddenCount = $_GET["hidecount"];}
		else{
//$hide ="";
		echo "You need to select an entry.";
	
		die();
		}


  } else {
	 
    $id = $_POST['ident'];
  }
  if ($touched) {
	  
    //end if

    if ($result->num_rows > 0) {
      // output data of each row


      while ($row = $result->fetch_assoc()) {

       $word =$row['word'];
	   $wordProcessor = new wordProcessor($word, "telugu");
			$wordsize = $wordProcessor->getLength();
			echo "the word size is : $wordsize";
      // $wordsize = strlen($word);
	   if($wordsize == 4) {$size = "2x2";}
	   if($wordsize == 6) {$size = "2x3";}
	   if($wordsize == 8) {$size = "2x4";}
	   if($wordsize == 9) {$size = "3x3";}
	   if($wordsize == 16) {$size = "4x4";}
	   if($wordsize == 81) {$size = "9x9";}
	   
	   $hide = ($wordsize*$wordsize)/2;
	   
	   
	  
 function formatMoney($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0.00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
      } // integer or decimal
    } // value
    return $money;
  } // numeric
} // formatMoney

		 echo $word;
		 echo $size;
        $hide= formatMoney($hide, 0);
	   echo $hide;
      } //end while

			
    } //end if
  }
else {
    //echo "0 results";
  } //end else

		
		
		$hiddenValues = [];
		
		/**** Start input validation and default values ****/
		
		// Validate size is either 2x2, 3x3, or 4x4
		
		// Get difficulty name
				
			$difficulty = '';
			
		$sizeNum = getSizeNum($size);
		
		// Validate hidden cell count is valid
		
			$hiddenCount = $hide;
		
		
		// Validate there is an input word
		// If the input word is the wrong size the Wordoku class will fail at generation
		
			
		
		
		// Check if the show solution option was passed.  If not, then don't show solution.
		if(isset($_GET["showsolution"])){
			$showSolution = $_GET["showsolution"];
			
			if(!($showSolution == "true")){
				$showSolution == "false";
			}
		}
		else{
			$showSolution = "false";
		}
		
		/**** End input validation and default values ****/
		
		
		// Debug message to show in set params
		// echo($size.$difficulty.$word.$sizeNum.$hiddenCount.$showSolution);
		
		// Create new Wordoku which automatically generates a solution and puzzle
		$numPuzzles = $_GET["numPuzzles"];
		//if(sizeof($_SESSION['puzzleArray']) > 0){
			//$wordoku = new Wordoku($sizeNum, $word, $hiddenCount);
		//}
		//else{
			$puzzleArray = array();
			for($i = 0; $i < $numPuzzles; $i++){
				$wordoku = new Wordoku($sizeNum, $word, $hiddenCount);
				array_push($puzzleArray, $wordoku);
				$solution = $puzzleArray[0]->getSolution();
				$puzzle = $puzzleArray[0]->getPuzzle();
				$characters = $puzzleArray[0]->getCharacters();
				$word = $wordoku->getWord();
				$_SESSION["solution"] = $solution;
				$_SESSION["puzzle"] = $puzzle;
				$_SESSION["word"] = $word;
				$_SESSION["size"] = $size;
				$_SESSION["difficulty"] = $difficulty;
			}
			
			
			
			
			
			
			
			
			
		//}
		
				// Address should be in format: http://localhost/wordoku/wordokupuzzle.php?size=2x2&difficulty=beginner&word=ABCD
				//$url = "wordokuPuzzle.php?size=".$size."&hidecount=".$hiddenCount."&difficulty=".$difficulty."&word=".$word."&showsolution=".$showSolution."&numPuzzles=".$numPuzzles;
				//print_r("</br>");
				//print_r($url);
				
				//header("Location:".$url);
				
		
		// Returns int value based off passed in size input parameter
		function getSizeNum($size){
		switch($size){
			case "2x2":
				return 2;
				break;
			case "2x3":
				return 6;
				break;
			case "2x4":
					return 8;
					break;
				case "3x3":
					return 3;
				break;
			case "4x4":
				return 4;
				break;
				default:
				return 2;
					break;
			}
		}
		
	?>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="wordokustyle.css">
	
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    <title>Wordoku Puzzle Generator</title>
</head>
<body>
		<div class="container-fluid">
			<div class="jumbotron" id="jumbos">
			</div>
			<br>
			<div class="panel" name ="displayArea" id ="displayArea">
				<div class="panel-group">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-sm-12">
									<div align="center">
										<h2>Wordoku Plus
											<?php
												echo ucwords($difficulty);
											?>
										</h2>										
									</div>
									<div class="col-sm-2">
									</div>
									<!--div class="col-sm-4" align="center">
										<h4>Display Mode:</h4>
									</div>
									
									<div class="col-sm-4">
										<select class="form-control" id="displayMode" name="displayMode" onchange="updatePuzzleDisplay();">
											<option value="NoNumbers" selected="selected">No Numbers (Default)</option>
											<option value="NumbersAndLetter">Numbers & Letter</option>
											<option value="JustNumbers">Just Numbers</option>
										</select>
									</div -->
								</div>
							</div>
						</div>
								<div>
									<div class="row">
										<div class="form-group">
											<center>
												<input type="checkbox" class="showSolution" name="showSolutionTextBox" onchange="valueChanged()" 
													<?php
														// Sets the checkbox as checked or unchecked based on input parameters
														if($showSolution == "true"){
															echo('checked');
																				}
												?>> Show solutions?
											</center>
										</div>
									</div>
									
								</div>
						<br>
						<?php include("puzzleSubdisplay.php"); ?>
			</div>
		</div>
</body>
<script>
	var num = 0;
	// Shows the solution on initialization based on whether the box is checked
	if($('.showSolution').is(":checked")){
		$(".solutionSection").show();
	}
	else{
		$(".solutionSection").hide();
	}
	
	// Set default hidden/shown puzzles
	$('#puzzleNormal').show();
	$('#puzzleNumberAndLetter').hide();
	$('#puzzleNumbers').hide();
	$('#solutionNormal').show();
	$('#solutionNumberAndLetter').hide();

	// Updates the solution section to hidden/visable on check box update
	function valueChanged(){
		if($('.showSolution').is(":checked")){  
			$(".solutionSection").show();
		}
		else{
			$(".solutionSection").hide();
		}
	}
	
	// Function for showing/hiding puzzles when the puzzle display option is changed
	function updatePuzzleDisplay(){
		if($('#displayMode').val() == "NoNumbers"){
			$('#puzzleNormal').show();
			$('#puzzleNumberAndLetter').hide();
			$('#puzzleNumbers').hide();
			$('#solutionNormal').show();
			$('#solutionNumberAndLetter').hide();
		}
		else if($('#displayMode').val() == "NumbersAndLetter"){
			$('#puzzleNormal').hide();
			$('#puzzleNumberAndLetter').show();
			$('#puzzleNumbers').hide();
			$('#solutionNormal').hide();
			$('#solutionNumberAndLetter').show();
		}
		else if($('#displayMode').val() == "JustNumbers"){
			$('#puzzleNormal').hide();
			$('#puzzleNumberAndLetter').hide();
			$('#puzzleNumbers').show();
			$('#solutionNormal').show();
			$('#solutionNumberAndLetter').hide();
		}
	}
</script>

<style>
	#userImg{
		max-height: 100%;
		max-width: 100%;
	}
</style>
</html>
