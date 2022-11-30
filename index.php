<?php
		/*  Created by Stephen Schneider
		 *	Additional code provided by Glenn Shanahan, Emmanuel Odidi, and Khalid Adam
		 *	Index page for the Wordoku puzzle.
		 *	Allows user to set information for puzzle generation.
		 *  On first submission page is done as a POST request to validate user input.
		 *  After validation of input, the page is redirected to the Wordoku Puzzle page as a GET request with passed in parameters.
		 */
		 
	session_start();
	$_SESSION['ID'] = session_id();
	$_SESSION["images"] = array();
	ob_start();
	require("word_processor.php");
	$ini = parse_ini_file('config.ini');
	
	// Set default hidden count values from the config.ini file
	// These will be passed on to JavaScript values to update the hidden count text box to default values when parameters change
	$beginner2x2 = getHiddenCount("beginner", "2x2", $ini);
	$advanced2x2 = getHiddenCount("advanced", "2x2", $ini);
	$expert2x2 =   getHiddenCount("expert", "2x2", $ini);
	            
	$beginner3x3 = getHiddenCount("beginner", "3x3", $ini);
	$advanced3x3 = getHiddenCount("advanced", "3x3", $ini);
	$expert3x3 = getHiddenCount("expert", "3x3", $ini);
	            
	$beginner4x4 = getHiddenCount("beginner", "4x4", $ini);
	$advanced4x4 = getHiddenCount("advanced", "4x4", $ini);
	$expert4x4 = getHiddenCount("expert", "4x4", $ini);
	
	// Check to see if first time visiting or if a POST request.
	// If POST request, then validate the user input for the Word and Puzzle Size.
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		/* 	Debugging
			print_r($_POST["size"]);
			print_r($_POST["difficulty"]);
			print_r($_POST["word"]);
		*/
		
		// Check that Size, Difficulty, and Word parameters have passed through
		if(isset($_POST["size"]) &&
				isset($_POST["difficulty"]) &&
				isset($_POST["word"]) &&
				isset($_POST["puzzleNum"]) &&
				isset($_POST["hiddenChar"])){
			
			$size = $_POST["size"];
			
			$hiddenCount = filter_input(INPUT_POST, "hiddenChar", FILTER_VALIDATE_INT);
			$numPuzzles = filter_input(INPUT_POST, "puzzleNum", FILTER_VALIDATE_INT);
			
			$word = $_POST["word"];
						
			// Check for show solution parameter
			if(isset($_POST["showSolution"])){
				$showSolution = "true";
			}
			else{
				$showSolution = "false";
			}
			
			if(isset($_POST["hasImages"])){
				$hasImages = true;
			}
			else{
				$hasImages = false;
			}
			
			// Create warning message string. If there is a message at the end, keep the user on index page and display warning message.
			$warningMessage = "";
			
			$wordProcessor = new wordProcessor($word, "telugu");
			$wordLength = $wordProcessor->getLength();
			
			/*  Debugging
				print_r("</br>");
				print_r($wordProcessor->getLength());
				print_r("</br>");
				print_r(strlen($word));
			*/
			
			// Validate word size based on size of puzzle
			if($size == "2x2"){
				if($wordLength != 4){
					$warningMessage = "Error: Input word is not the right size for a 2x2 puzzle";
				}
			}
			else if($size == "3x3"){
				if($wordLength != 9){
					$warningMessage = "Error: Word not the right length for a 3x3 puzzle";
				}
			}
			else if($size == "4x4"){
				if($wordLength != 16){
					$warningMessage = "Error: Word not the right length for a 4x4 puzzle.";
				}
			}
			
			
			// Validate the puzzle hidden count is a valid number
			$puzzleSize = getSizeNum($size);
			$puzzleSize = $puzzleSize * $puzzleSize * $puzzleSize * $puzzleSize;
			
			if(is_int($hiddenCount)){
				if($hiddenCount >= $puzzleSize){
					if($warningMessage != ""){
						$warningMessage = $warningMessage."</br></br>Hidden Puzzle Characters ".
											$hiddenCount." greater than or equal to puzle size ".$puzzleSize.". Please use a smaller number.";
					}
					else{
						$warningMessage = "Error: Hidden Puzzle Characters ".$hiddenCount.
											" greater than puzle size ".$puzzleSize.". Please use a smaller number.";
					}
				}
			}
			else{
				if($warningMessage != ""){
					$warningMessage = $warningMessage."</br></br>Hidden Puzzle Characters must be a whole number.";
				}
				else{
					$warningMessage = "Error: Hidden Puzzle Characters must be a whole number.";
				}
			}
			
			// Set the difficulty so it can be sent to next page, but is not required to be sent.
			// Will be included in the Puzzle Header UI when it is
			$difficulty = $_POST["difficulty"];

			// If no warning message, direct user to the puzzle
			// Otherwise keep user on Index page and display the warning message
			
			if($warningMessage == ""){
				// Address should be in format: http://localhost/wordoku/wordokupuzzle.php?size=2x2&difficulty=beginner&word=ABCD
				$url = "wordokuPuzzle.php?size=".$size."&hidecount=".$hiddenCount."&difficulty=".$difficulty."&word=".$word."&showsolution=".$showSolution."&numPuzzles=".$numPuzzles."&hasImages=" . $_SESSION["hasImages"];
				//$url = "wordokuPuzzle.php?size=".$size."&hidecount=".$hiddenCount."&difficulty=".$difficulty."&word=".$word."&showsolution=".$showSolution.$extra;
				//print_r("</br>");
				print_r($url);
				
				header("Location:".$url);
				die();
			}
		}
	}else{
		$dir = "uploads/" . session_id();
		$_SESSION["hasImages"] = false;
		if(is_dir($dir)){
			$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it,RecursiveIteratorIterator::CHILD_FIRST);
			foreach($files as $file) {
				if ($file->isDir()){
					rmdir($file->getRealPath());
				} else {
					unlink($file->getRealPath());
				}
			}
			rmdir($dir);
		}
	}
	
	// Returns hidden count for the puzzle based off the passed in difficulty.
	// Obtains values from the config.ini file.
	function getHiddenCount($difficulty, $size, $ini){
		$option = $difficulty.$size;
		
		return $ini[$option];
	}
	
	// Returns int value based off passed in size input parameter
	function getSizeNum($size){
		switch($size){
			case "2x2":
				return 2;
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



<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    <title>Wordoku Puzzle Generator</title>
</head>
<body>
    <form action="index.php" method="post">
        <div class="container-fluid">
            <div class="jumbotron" id="jumbos">
            </div>
            <div class="panel">
                <div class="panel-group">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12">
									<div align="center">
										<h2>Please enter your Wordoku puzzle details</h2>
									</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label>Puzzle Size</label>
                                        <select class="form-control" id="size" name="size" onchange="sizeChange(this.value);">
                                            <option value="2x2">2x2</option>
                                            <option value="3x3"  selected="selected">3x3</option>
                                            <option value="4x4">4x4</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Puzzle Difficulty</label>
                                        <select class="form-control" id="difficulty" name="difficulty"  onchange="updateHiddenTextbox();">
                                            <option value="Beginner">Beginner</option>
                                            <option value="Advanced">Advanced</option>
                                            <option value="Expert" selected="selected">Expert</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Enter Characters</label>
                                        <textarea class="form-control" style="resize: none;" rows="1"id="characterBank" name="word" >మానవుడేమహనీయుడు</textarea>
                                        <label class="charLabel" name="charName" value="">9 characters for a 3x3 puzzle</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Hidden Puzzle Characters: </label>
									<textarea class="form-control" style="resize: none;" rows="1" id="hiddenChar" name="hiddenChar" ></textarea>
									<label class="charLabel" name="charName" value="">Updates on size or difficulty change</label>
                                </div>
								<div class="col-sm-3">
									<label>Number of Puzzles: </label>
									<textarea class="form-control" style="resize: none;" rows="1" id="puzzleNum" name="puzzleNum">1</textarea>
								</div>

							<button type="button" class="collapsible" onclick="displayAccordian()">Add Images?</button>
							<div class="content" id = "content">
									<div class="col-sm-6">
										<input type="file" name="files" id="files" class="btn btn-primary btn-lg" multiple>
									</div>
									<div class="col-sm-3">
										<input type="button" id="btn_uploadfile" class="btn btn-primary btn-lg" value="Upload" onclick="uploadImgs();">
									</div>
									<!--div class="col-sm-3">
										<input type="button" name="flickr" id="flickr" class="btn btn-primary btn-lg" value = 'Access Flickr' onclick="accessFlickr();">
									</div>
									<div class="col-sm-3">
										<input type="button" name="google photos" id="google photos" class="btn btn-primary btn-lg" value = 'Access Google Photos' onclick=showPickerDialog()>
									</div-->
	
							</div>
                            <div class="row">
                                 <div class="col-sm-3">
                                        <input type="checkbox" name="showSolution" checked> Show solution on creation?
                                 </div>  
								 <div class="col-sm-3">
                                        <input type="checkbox" name="hasImages" checked> Show Images?
                                 </div>   								 
                            </div>
                            </br>
							<div class="row"></div>
							<div class="row">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label class="charLabel" style="color:red;font-size:14px" name="charName" value="">
										<?php
											// If there is a warning message after input validation display message to user
											if(isset($warningMessage)){
												echo($warningMessage);
											}
										?>
										</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6"><input type="submit" name="submit" class="btn btn-primary btn-lg" value="Generate"></div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>



<script>
	<?php
		//Set JavaScript values on initial load based off default values in the config.ini file
		echo('var beginner2x2 = '.$beginner2x2.';
		');
		echo('var advanced2x2 = '.$advanced2x2.';
		');
		echo('var expert2x2 = '.$expert2x2.';'
		);
		
		echo('var beginner3x3 = '.$beginner3x3.';
		');
		echo('var advanced3x3 = '.$advanced3x3.';
		');
		echo('var expert3x3 = '.$expert3x3.';
		');
		
		echo('var beginner4x4 = '.$beginner4x4.';
		');
		echo('var advanced4x4 = '.$advanced4x4.';
		');
		echo('var expert4x4 = '.$expert4x4.';
		');
	?>
	
	var lbl = document.getElementsByName('hiddenChar')[0];
	//lbl.value = beginner2x2;
	lbl.value = 40;
	
	// Update label information about the length of the input word based on selected puzzle size
	function sizeChange(sizeValue){
		var lbl = document.getElementsByName('charName')[0];
			
		if(sizeValue === '2x2'){
			lbl.innerHTML='4 characters for a 2x2 puzzle';
		}
		else if(sizeValue === '3x3'){
			lbl.innerHTML='9 characters for a 3x3 puzzle';
		}
		else if(sizeValue === "4x4"){
			lbl.innerHTML='16 characters for a 4x4 puzzle';
		}
		
		updateHiddenTextbox();
	}
	
	// Function for updating the hidden count text box number to default values when updated
	function updateHiddenTextbox(){
		var lbl = document.getElementsByName('hiddenChar')[0];
		var sizeValue = document.getElementsByName('size')[0].value;
		var difficulty = document.getElementsByName('difficulty')[0].value;
		
		if(sizeValue == "2x2"){
			if(difficulty == "Beginner"){
				lbl.value = beginner2x2;
			}
			else if(difficulty == "Advanced"){
				lbl.value = advanced2x2;
			}
			else if(difficulty == "Expert"){
				lbl.value = expert2x2;
			}
		}
		else if(sizeValue == "3x3"){
			if(difficulty == "Beginner"){
				lbl.value = beginner3x3;
			}
			else if(difficulty == "Advanced"){
				lbl.value = advanced3x3;
			}
			else if(difficulty == "Expert"){
				lbl.value = expert3x3;
			}
		}
		else if(sizeValue == "4x4"){
			if(difficulty == "Beginner"){
				lbl.value = beginner4x4;
			}
			else if(difficulty == "Advanced"){
				lbl.value = advanced4x4;
			}
			else if(difficulty == "Expert"){
				lbl.value = expert4x4;
			}
		}
	}
	
	function displayAccordian(){
		if(document.getElementById("content").style.display == "block")
			document.getElementById("content").style.display = "none";
		else
			document.getElementById("content").style.display = "block";
	}
	
	function uploadImgs(){
		var totalfiles = document.getElementById('files').files.length;

		if(totalfiles > 0 ){

			var formData = new FormData();
			for (var index = 0; index < totalfiles; index++) {
				formData.append("files[]", document.getElementById('files').files[index]);
			}

			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "ajaxfile.php", true);
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {

				var response = this.responseText;

				alert(response + " File uploaded.");
				}
			};
			xhttp.send(formData);
		}
		else{
			alert("Please select a file");
		}
	}
		
</script>

<!-- The Google API Loader script for Google Drive button. -->
<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>
<script>
	function showPickerDialog(){
    	loadPicker()
    }
</script>


<!-- The Flickr upload script 
Application Key:
35eec536773b5a78381170dfb88a1f18

Secret Key:
2f0faa565d31ab66

limited to 3600 queries per hour

flic.kr/p/[short-photo-id] form
-->
<script>
var applicationKey = '35eec536773b5a78381170dfb88a1f18'
var secretKey = '2f0faa565d31ab66'
var flickrAuthUrl = 'https://www.flickr.com/services/oauth/authorize'
function accessFlickr(){
	var oauth_nonce;
	var oauth_signature_method = "HMAC-SHA1";
	var oauth_signature;
	var oauth_timestamp;
	var oauth_callback;
	var oauth_version = "1.0";//optional
}
</script>




<!-- The Google Picker API script. -->
<script>
var clientId = '789960945798-6cu10t645cum5jq3qp0l6sdhdgppt8ec.apps.googleusercontent.com'
var APIKey = 'AIzaSyDrtLOIDlmxHVoilBAyZ82z8hzV_ClTSYQ'
var projectId = 'cryptic-wonder-365009'
var oauthToken
var pickerAPILoaded = false

// Scope to use to access user's Drive items.
var scope = 'https://www.googleapis.com/auth/drive.file'

// client library of Google

function pickerDialog() {
	loadPicker()
}

// Use the Google API Loader script to load the google.picker script.
function loadPicker() {
	gapi.load('auth', {'callback': onAuthApiLoad})
	gapi.load('picker', {'callback': onPickerApiLoad})
}

function onAuthApiLoad() {
	window.gapi.auth.authorize({
		'client_id': clientId,
		'scope': scope,
		'immediate': false
	},
	handleAuthResult)
}

function onPickerApiLoad() {
	pickerAPILoaded = true
	createPicker()
}

function handleAuthResult(authResult) {
	// access token
	if (authResult && !authResult.error) {
		oauthToken = authResult.access_token
		createPicker()
	}
}

// Create and render a Picker object for searching images.
function createPicker() {
	if (pickerAPILoaded && oauthToken) {
		var view = new google.picker.View(google.picker.ViewId.DOCS)
		view.setMimeTypes("image/png,image/jpeg,image/jpg,image/jif,image/pdf")
		var picker = new google.picker.PickerBuilder()
		.enableFeature(google.picker.Feature.NAV_HIDDEN)
		.enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
		.setAppId(projectId)
		.setOAuthToken(oauthToken)
		.addView(view)
		.addView(new google.picker.DocsUploadView())
		.setDeveloperKey(key)
		.setCallback(pickerCallback)
		.build()

		picker.setVisible(true)
	}
}

// A simple callback implementation.
function pickerCallback(data) {
	if (data.action == google.picker.Action.PICKED) {
		var fileId = data.docs[0].id;
		alert('The user selected: ' + fileId);
	}
}

</script>



<style>
	.collapsible {
		background-color: #eee;
		color: #444;
		cursor: pointer;
		padding: 18px;
		width: 100%;
		border: none;
		text-align: left;
		outline: none;
		font-size: 15px;
	}

	.active, .collapsible:hover {
		background-color: #ccc;
	}

	.content {
		padding: 0 18px;
		display: none;
		overflow: hidden;
		background-color: #f1f1f1;
	}
	
</style>
</html>