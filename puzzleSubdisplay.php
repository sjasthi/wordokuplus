<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN''http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>

<head>
	
</head>

<body>

<?php
							for($x = 0; $x < $numPuzzles; $x++){
								
								$solution = $puzzleArray[$x]->getSolution();
								$puzzle = $puzzleArray[$x]->getPuzzle();
		
								$_SESSION["solution"] = $solution;
								$_SESSION["puzzle"] = $puzzle;
							
						?>
						<div class="panel-body">
							</br></br>
							<!-- NORMAL -->
							<div class="panel-heading">
								<div class="row">
									<div class="col-sm-12">
										<div align="center"><h2>Puzzle <?php print_r($x+1) ?></h2></div>
									</div>
								</div>
							</div>
							<div class="row" style="padding-bottom:20px">
								<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
									<div class="text-center">
										<h3>Character Key</h3>
									</div>
								</div>
								<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
									<div class="text-center">
										<h3>
										
										<?php
											// Displays input word characters seperated out
											foreach ($characters as $key => $letter){
												echo($letter." ");
											}
										?>
										</h3>
									</div>
								</div>
							</div>
							<?php if($hasImages){ ?>
							<div class="col-sm-4" id="imageBlock">
								<?php
									$imagesDir = 'uploads/';
									$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
									$randomImage = $images[array_rand($images)];
								?>
								<img id="userImg" src="<?php echo $randomImage; ?>">
							</div>
							<div class="col-sm-8"  id="puzzleNormal">
							<?php }
							else{?>
							<div class="col-sm-12"  id="puzzleNormal"> <?php } ?>
								<div class="puzzle">
									<table id="grid">
										<?php 
											// Display a normal/default puzzle
											$i = 0;
											foreach ($puzzle as $key => $value) 
											{
												echo'<tr>';
												foreach ($value as $k => $val){
													if($val != " "){
														echo'<td id="cell'.$size.'-'.$i.'" bgcolor="#EEEEEE"> '.$val.' </td>
														';
													}
													else{
														echo'<td id="cell'.$size.'-'.$i.'"> '.$val.' </td>
														';
													}
													$i++;
												}
												echo'</tr>';
											}
										?>
									</table>
								</div>
							</div>

							</div>					
					</div>
					<div class="solutionSection"> 
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-sm-12">
										<div align="center"><h2>Solution <?php print_r($x+1) ?></h2></div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
										<div class="text-center">
											<h3>Character Key</h3>
										</div>
									</div>
									<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
										<div class="text-center">
											<h3>
												<?php
													// Displays input word characters seperated out
													foreach ($characters as $key => $letter){
														echo($letter." ");
													}
												?>
											</h3>
										</div>
									</div>
								</div>
															
								</br></br>
								
								<!-- Normal Solution -->
								<div class="col-sm-12" id="solutionNormal">
									<div class="col-sm-12">
										<div class="puzzle">
											<table id="grid">
												<?php 
													// Displays the normal/default solution style
													$i = 0;
													foreach ($solution as $key => $value) 
													{
														echo'<tr>';
														foreach ($value as $k => $val){
															if($puzzle[$key][$k] != " "){
																echo'<td id="cell'.$size.'-'.$i.'" bgcolor="#EEEEEE"> '.$val.' </td>
																';
															}
															else{
																echo'<td id="cell'.$size.'-'.$i.'" bgcolor="#EEEEEE" style="color: red;"> '.$val.' </td>
																';
															}
															$i++;
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
								</div>
								</div>
						</div>
					</div>
							<?php } ?>
					
					

							<?php
							for($x = 0; $x < $numPuzzles; $x++){
								
								$solution = $puzzleArray[$x]->getSolution();
								$puzzle = $puzzleArray[$x]->getPuzzle();
		
								$_SESSION["solution"] = $solution;
								$_SESSION["puzzle"] = $puzzle;
							
						?>



					<div class="solutionSection2"> 
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-sm-12">
										<div align="center"><h2>Solution <?php print_r($x+1) ?></h2></div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
										<div class="text-center">
											<h3>Character Key</h3>
										</div>
									</div>
									<div class="form-group" style='width:40%;margin:auto;border:2px solid black;padding-top:10px;padding-bottom:10px;text-align:center'>
										<div class="text-center">
											<h3>
												<?php
													// Displays input word characters seperated out
													foreach ($characters as $key => $letter){
														echo($letter." ");
													}
												?>
											</h3>
										</div>
									</div>
								</div>
															
								</br></br>
								
								<!-- Normal Solution -->
								<div class="col-sm-12" id="solutionNormal">
									<div class="col-sm-12">
										<div class="puzzle">
											<table id="grid">
												<?php 
													// Displays the normal/default solution style
													$i = 0;
													foreach ($solution as $key => $value) 
													{
														echo'<tr>';
														foreach ($value as $k => $val){
															if($puzzle[$key][$k] != " "){
																echo'<td id="cell'.$size.'-'.$i.'" bgcolor="#EEEEEE"> '.$val.' </td>
																';
															}
															else{
																echo'<td id="cell'.$size.'-'.$i.'" bgcolor="#EEEEEE" style="color: red;"> '.$val.' </td>
																';
															}
															$i++;
														}
														echo'</tr>';
													}
												?>
											</table>
										</div>
									</div>
								</div>
								</div>
						</div>
					</div>	


					
				<?php } ?>
</div>
</body>