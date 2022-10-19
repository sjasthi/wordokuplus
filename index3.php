<?php
/*  Created by Stephen Schneider
		 *	Index page for the Wordoku puzzle.
		 *	Allows user to set information for puzzle generation.
		 *  On first submission page is done as a POST request to validate user input.
		 *  After validation of input, the page is redirected to the Wordoku Puzzle page as a GET request with passed in parameters.
		 */


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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	/* 	Debugging
			print_r($_POST["size"]);
			print_r($_POST["difficulty"]);
			print_r($_POST["word"]);
		*/

	// Check that Size, Difficulty, and Word parameters have passed through


	$ptype = $_POST['type'];

	if ($ptype == 'letter') {

		if (
			isset($_POST["size"]) &&
			isset($_POST["difficulty"]) &&
			isset($_POST["word"]) &&
			isset($_POST["hiddenChar"])
		) {

			$size = $_POST["size"];

			$hiddenCount = filter_input(INPUT_POST, "hiddenChar", FILTER_VALIDATE_INT);

			$word = $_POST["word"];

			// Check for show solution parameter
			if (isset($_POST["showSolution"])) {
				$showSolution = "true";
			} else {
				$showSolution = "false";
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
			if ($size == "2x2") {
				if ($wordLength != 4) {
					$warningMessage = "Error: Input word is not the right size for a 2x2 puzzle";
				}
			} else if ($size == "3x3") {
				if ($wordLength != 9) {
					$warningMessage = "Error: Word not the right length for a 3x3 puzzle";
				}
			} else if ($size == "4x4") {
				if ($wordLength != 16) {
					$warningMessage = "Error: Word not the right length for a 4x4 puzzle.";
				}
			}


			// Validate the puzzle hidden count is a valid number
			$puzzleSize = getSizeNum($size);
			$puzzleSize = $puzzleSize * $puzzleSize * $puzzleSize * $puzzleSize;

			if (is_int($hiddenCount)) {
				if ($hiddenCount >= $puzzleSize) {
					if ($warningMessage != "") {
						$warningMessage = $warningMessage . "</br></br>Hidden Puzzle Characters " .
							$hiddenCount . " greater than or equal to puzle size " . $puzzleSize . ". Please use a smaller number.";
					} else {
						$warningMessage = "Error: Hidden Puzzle Characters " . $hiddenCount .
							" greater than puzle size " . $puzzleSize . ". Please use a smaller number.";
					}
				}
			} else {
				if ($warningMessage != "") {
					$warningMessage = $warningMessage . "</br></br>Hidden Puzzle Characters must be a whole number.";
				} else {
					$warningMessage = "Error: Hidden Puzzle Characters must be a whole number.";
				}
			}

			// Set the difficulty so it can be sent to next page, but is not required to be sent.
			// Will be included in the Puzzle Header UI when it is
			$difficulty = $_POST["difficulty"];

			// If no warning message, direct user to the puzzle
			// Otherwise keep user on Index page and display the warning message
			if ($warningMessage == "") {
				// Address should be in format: http://localhost/wordoku/wordokupuzzle.php?size=2x2&difficulty=beginner&word=ABCD
				$url = "wordokuPuzzle.php?size=" . $size . "&hidecount=" . $hiddenCount . "&difficulty=" . $difficulty . "&word=" . $word . "&showsolution=" . $showSolution;
				//print_r("</br>");
				//print_r($url);

				header("Location:" . $url);
				die();
			}
		}
	} else {

		//Image part will be handle by jquery


		if (
			isset($_POST["size"]) &&
			isset($_POST["difficulty"]) &&
			isset($_POST["hiddenChar"])
		) {

			$size = $_POST["size"];

			$hiddenCount = filter_input(INPUT_POST, "hiddenChar", FILTER_VALIDATE_INT);


			// Check for show solution parameter
			if (isset($_POST["showSolution"])) {
				$showSolution = "true";
			} else {
				$showSolution = "false";
			}

			// Create warning message string. If there is a message at the end, keep the user on index page and display warning message.
			$warningMessage = "";

			$imageCount = 0;
			foreach ($_FILES as $key => $img) {
				$imageCount++;
			}
			// Validate word size based on size of puzzle
			if ($size == "2x2") {
				if ($imageCount != 4) {
					$warningMessage = "Error: Input image is not the right size for a 2x2 puzzle";
				}
			} else if ($size == "3x3") {
				if ($imageCount != 9) {
					$warningMessage = "Error: Image not the right length for a 3x3 puzzle";
				}
			} else if ($size == "4x4") {
				if ($imageCount != 16) {
					$warningMessage = "Error: Image not the right length for a 4x4 puzzle.";
				}
			}


			// Validate the puzzle hidden count is a valid number
			$puzzleSize = getSizeNum($size);
			$puzzleSize = $puzzleSize * $puzzleSize * $puzzleSize * $puzzleSize;

			if (is_int($hiddenCount)) {
				if ($hiddenCount >= $puzzleSize) {
					if ($warningMessage != "") {
						$warningMessage = $warningMessage . "</br></br>Hidden Puzzle Characters " .
							$hiddenCount . " greater than or equal to puzle size " . $puzzleSize . ". Please use a smaller number.";
					} else {
						$warningMessage = "Error: Hidden Puzzle Characters " . $hiddenCount .
							" greater than puzle size " . $puzzleSize . ". Please use a smaller number.";
					}
				}
			} else {
				if ($warningMessage != "") {
					$warningMessage = $warningMessage . "</br></br>Hidden Puzzle Characters must be a whole number.";
				} else {
					$warningMessage = "Error: Hidden Puzzle Characters must be a whole number.";
				}
			}

			// Set the difficulty so it can be sent to next page, but is not required to be sent.
			// Will be included in the Puzzle Header UI when it is
			$difficulty = $_POST["difficulty"];

			// If no warning message, direct user to the puzzle
			// Otherwise keep user on Index page and display the warning message
			if ($warningMessage == "") {

				// We will upload the images in a unique folder
				$image_folder = md5(time()) . '_' . random_int(0, 100);
				// Create the folder
				mkdir("uploads/".$image_folder, 0775, true);
				$i = 0;
				foreach ($_FILES as $img) {
					move_uploaded_file($img['tmp_name'], "uploads/".$image_folder."/".$i.".png");
					$i++;
				}

				// Address should be in format: http://localhost/wordoku/wordokupuzzle.php?size=2x2&difficulty=beginner&word=ABCD
				$url = "success_size=" . $size . "&hidecount=" . $hiddenCount . "&difficulty=" . $difficulty . "&imgcount=" . $imageCount . "&folder=" . $image_folder . "&showsolution=" . $showSolution;
				die($url);
			}

			die($warningMessage);
		}
	}
}

// Returns hidden count for the puzzle based off the passed in difficulty.
// Obtains values from the config.ini file.
function getHiddenCount($difficulty, $size, $ini)
{
	$option = $difficulty . $size;

	return $ini[$option];
}

// Returns int value based off passed in size input parameter
function getSizeNum($size)
{
	switch ($size) {
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
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- Croper jS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" integrity="sha512-ooSWpxJsiXe6t4+PPjCgYmVfr1NS5QXJACcR/FPpsdm6kqG1FmQ2SVyg2RXeVuCRBLr0lWHnWJP6Zs1Efvxzww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.css" integrity="sha512-+VDbDxc9zesADd49pfvz7CgsOl2xREI/7gnzcdyA9XjuTxLXrdpuz21VVIqc5HPfZji2CypSbxx1lgD7BgBK5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale = 1">
	<title>Wordoku Puzzle Generator</title>
	<style>
		.img_area .fline,
		.img_area>a {
			display: inline-block;
			height: 70px;
			position: relative;
			margin: 7px;
			border: 1px solid #333;
			vertical-align: top;
		}

		.img_area a {
			height: 70px;
			width: 70px;
			background-color: #EEE;
			font-size: 14px;
			color: #222 !important;
			text-align: center;
			padding-top: 10px;
		}

		.img_area .fline img {
			width: auto;
			max-height: 100px;

		}

		.img_area .fline .fa-times {
			cursor: pointer;
			position: absolute;
			top: 5px;
			right: 5px;
			color: #FFF;
			font-size: 12px !important;
			background-color: #F00;
			padding: 2px;
			border-radius: 3px;
		}

		.types {
			margin-bottom: 20px;
		}

		.types input {
			display: none;
		}

		.types input+label {
			border: 1px solid #999;
			background-color: #EFEF;
			min-width: 45%;
			font-size: 18px;
		}

		.types input:checked+label {
			background-color: #06F;
			border: 1px solid #999;
			color: #FFF;
		}
	</style>

</head>

<body>
	<form id="form" action="index3.php" method="post" onsubmit="check_image_cnt(event)">
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
							<div class="types">

								<input value="letter" onchange="show_type()" id="rad1" type="radio" name="type" autocomplete="off" checked>
								<label class="btn" for="rad1">Letters Entry</label>
								<input value="image" id="rad2" type="radio" name="type" autocomplete="off" onchange="show_type()">
								<label class="btn" for="rad2">Image Entry (Image Puzzle)</label>
							</div>

							<div class="row">
								<div class="form-group">
									<div class="col-sm-3">
										<label>Puzzle Size</label>
										<select class="form-control" id="size" name="size" onchange="sizeChange(this.value);">
											<option value="2x2">2x2</option>
											<option value="3x3" selected="selected">3x3</option>
											<option value="4x4">4x4</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label>Puzzle Difficulty</label>
										<select class="form-control" id="difficulty" name="difficulty" onchange="updateHiddenTextbox();">
											<option value="Beginner">Beginner</option>
											<option value="Advanced">Advanced</option>
											<option value="Expert" selected="selected">Expert</option>
										</select>
									</div>

									<div class="col-sm-3">
										<label>Hidden Puzzle Characters: </label>
										<input class="form-control" style="resize: none;" id="hiddenChar" name="hiddenChar"></textarea>
										<label class="charLabel" name="charName" value="">Updates on size or difficulty change</label>
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-sm-4" id="letter_type">
									<label>Enter Characters</label>
									<textarea class="form-control" style="resize: none;" rows="1" id="characterBank" name="word">మానవుడేమహనీయుడు</textarea>
									<label class="charLabel" name="charName" value="">9 characters for a 3x3 puzzle</label>
								</div>
								<div class="col-sm-12" style="display: none;" id="image_type">
									<label>Upload Images </label><br>
									<div class="main_body img_area">
										<a style="cursor: pointer;" onclick="$('#file')[0].click()" id="file_line">IMG upload</a>
									</div>
									<label class="charLabel" name="charName2" value="">9 images for a 3x3 puzzle</label>
								</div>
							</div>
							<br><br>
							<div class="row">
								<div class="col-sm-12">
									<input type="checkbox" name="showSolution" checked> Show solution on creation?
								</div>
							</div>
							</br>
							<div class="row"></div>
							<div class="row">
								<div class="form-group">
									<div class="col-sm-12">
										<label class="charLabel" style="color:red;font-size:14px" name="charName" id="r_msg" value="">
											<?php
											// If there is a warning message after input validation display message to user
											if (isset($warningMessage)) {
												echo ($warningMessage);
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
					<div class="col-sm-12" id="sbutton"><input type="submit" name="submit" class="btn btn-primary btn-lg" value="Generate"></div>
				</div>
			</div>
		</div>
	</form>


	<div class="modal fade" tabindex="1" role="dialog" data-backdrop="static" id="image_modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Crop Photo</h5>
					<input style="display: none;" onchange="run_croper()" type="file" id="file" required accept="image/*" class="form-control">
				</div>
				<div class="modal-body" style="padding:0px;">
					<img style="max-width:100%; box-sizing:border-box; min-width:90% !important; display:block" id="sthumb_image" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="crop_uploader">Crop</button>
				</div>
			</div>
		</div>
	</div>



</body>

<script>
	let cropper = null;
	let ufiles = {}; // Store image files to be uploaded
	let this_puzzleSize = 9; /// puzzle size


	// Check if this is image puzzle and  the number of uploaded images is equal to puzzle size
	function check_image_cnt(event) {

		let type = $('input[name="type"]:checked').val();
		if (type != 'image') return;

		event.preventDefault();
		if (this_puzzleSize != $(".fline").length) {
			alert("Number of images must be equal to puzzle size");
			return
		}

		var fdata = new FormData($("#form")[0]);
	 	// Add the images
		for(key in ufiles){
			fdata.append(key, ufiles[key]);
		}

		$("#r_msg").html('');
		var sbnx = $("#sbutton").html();
		$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
		$.ajax({
			type: "POST",
			url: "",
			data: fdata,
			cache: false,
			processData: false,
			contentType: false,
			success: function(data) { //console.log(data);
				$("#sbutton").html(sbnx);
				if (data.substr(0, 8) == 'success_') {
					$("form")[0].reset();
					window.location.href = "wordokuPuzzle3.php?"+data.substr(8);
				} else {
					$("#r_msg").html(data);
				}

			}
		});
	}


	// Convert dataURL to file
	function dataUrlToBlob(dataURL) {
		var blobBin = atob(dataURL.split(',')[1]);
		var array = [];
		for (var i = 0; i < blobBin.length; i++) {
			array.push(blobBin.charCodeAt(i));
		}
		var file = new Blob([new Uint8Array(array)], {
			type: 'image/png'
		});

		return file;
	}


	function run_croper() {

		var ext = $("#file").val().split('.').pop().toLowerCase()
		if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {

			mytooltip("mfile", "File must be an image eg. a jpg, jpeg, png, gif");
			return false;
		}

		$("#image_modal .modal-body").html('<img style="max-width:100%; box-sizing:border-box; min-width:90% !important; display:block" id="sthumb_image" />');

		var imaz = document.getElementById('file');
		var vv = imaz.files[0];
		if (imaz.files != undefined) {

			var sss = new FileReader();
			sss.onload = function() {
				cropper = null;
				$("#crop_uploader").prop('disabled', true);
				$("#sthumb_image").prop('src', sss.result);
				setTimeout(function() {
					const image = document.getElementById('sthumb_image');
					cropper = new Cropper(image, {
						aspectRatio: 1,
						rotatable: false,
						minCropBoxWidth: 170,
					});
					$("#crop_uploader").prop('disabled', false);
				}, 500);

				$("#crop_uploader").off().on("click", function() {

					let cdata = cropper.getData();
					let cdata_str = JSON.stringify(cdata);

					let img = new Image();
					let crp_img = cropper.getCroppedCanvas().toDataURL("image/png");
					img.src = crp_img;
					img.onload = function() {
						let oc = document.createElement('canvas');
						let octx = oc.getContext('2d');
						oc.width = 75;
						oc.height = 75;
						octx.drawImage(img, 0, 0, oc.width, oc.height);

						let stamp = $.now();

						// Add this image line to the html
						$("#file_line").before('<span class="fline" id="' + stamp + '"><img src=""><i onclick="remove_img(event, \'\', \'\')" class="fa fa-times"> delete</i></span>');
						$("#modal_file").modal('hide');

						$("#" + stamp + " img")[0].src = oc.toDataURL();

						ufiles[stamp] = dataUrlToBlob(oc.toDataURL());

						//$("#file_line").css('display', 'none');

						$("#file").val('');
						$("#file").val(null);

						$("#image_modal .modal-body").html('<img style="max-width:100%; box-sizing:border-box; min-width:90% !important; display:block" id="sthumb_image" />');

						$("#image_modal").modal('hide');
					}

				});


			};
			sss.readAsDataURL(vv);

		}

		$("#image_modal").modal('show');

	}

	function show_type() {
		let type = $('input[name="type"]:checked').val();
		if (type == 'image') {
			$("#image_type").show();
			$("#letter_type").hide();
		} else {
			$("#image_type").hide();
			$("#letter_type").show();
		}

	}

	<?php
	//Set JavaScript values on initial load based off default values in the config.ini file
	echo ('var beginner2x2 = ' . $beginner2x2 . ';
		');
	echo ('var advanced2x2 = ' . $advanced2x2 . ';
		');
	echo ('var expert2x2 = ' . $expert2x2 . ';'
	);

	echo ('var beginner3x3 = ' . $beginner3x3 . ';
		');
	echo ('var advanced3x3 = ' . $advanced3x3 . ';
		');
	echo ('var expert3x3 = ' . $expert3x3 . ';
		');

	echo ('var beginner4x4 = ' . $beginner4x4 . ';
		');
	echo ('var advanced4x4 = ' . $advanced4x4 . ';
		');
	echo ('var expert4x4 = ' . $expert4x4 . ';
		');
	?>

	var lbl = document.getElementsByName('hiddenChar')[0];
	//lbl.value = beginner2x2;
	lbl.value = 40;

	// Update label information about the length of the input word based on selected puzzle size
	function sizeChange(sizeValue) {
		var lbl = document.getElementsByName('charName')[1];
		var lb2 = document.getElementsByName('charName2')[0];

		if (sizeValue === '2x2') {
			lbl.innerHTML = '4 characters for a 2x2 puzzle';
			lb2.innerHTML = '4 images for a 2x2 puzzle';
			this_puzzleSize = 4;
		} else if (sizeValue === '3x3') {
			lbl.innerHTML = '9 characters for a 3x3 puzzle';
			lb2.innerHTML = '9 images for a 3x3 puzzle';
			this_puzzleSize = 9;
		} else if (sizeValue === "4x4") {
			lbl.innerHTML = '16 characters for a 4x4 puzzle';
			lb2.innerHTML = '16 images for a 4x4 puzzle';
			this_puzzleSize = 16;
		}

		updateHiddenTextbox();
	}

	// Function for updating the hidden count text box number to default values when updated
	function updateHiddenTextbox() {
		var lbl = document.getElementsByName('hiddenChar')[0];
		var sizeValue = document.getElementsByName('size')[0].value;
		var difficulty = document.getElementsByName('difficulty')[0].value;

		if (sizeValue == "2x2") {
			if (difficulty == "Beginner") {
				lbl.value = beginner2x2;
			} else if (difficulty == "Advanced") {
				lbl.value = advanced2x2;
			} else if (difficulty == "Expert") {
				lbl.value = expert2x2;
			}
		} else if (sizeValue == "3x3") {
			if (difficulty == "Beginner") {
				lbl.value = beginner3x3;
			} else if (difficulty == "Advanced") {
				lbl.value = advanced3x3;
			} else if (difficulty == "Expert") {
				lbl.value = expert3x3;
			}
		} else if (sizeValue == "4x4") {
			if (difficulty == "Beginner") {
				lbl.value = beginner4x4;
			} else if (difficulty == "Advanced") {
				lbl.value = advanced4x4;
			} else if (difficulty == "Expert") {
				lbl.value = expert4x4;
			}
		}
	}
</script>

</html>