<?php
	// util function to debug
	require_once('util/util.php');	
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Flyer Creator 5.8</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link rel="stylesheet" type="text/css" href="js/jquery/jquery-ui-1.11.4/jquery-ui.css">
  <script src="js/jquery/jquery-1.11.2.min.js"></script>
  <script src="js/jquery/jquery-ui-1.11.4/jquery-ui.min.js"></script>
  <script src="js/jqueryFunctions.js"></script>
</head>

<body>
<h1>Flyer Creator</h1>

<div id="container">
	<div id="editor">
		<form id="flyerForm" enctype="multipart/form-data" action="pdf_generator.php" method="post" target="_blank">
			<fieldset>
				<legend>Flyer Properties</legend>
					<div id="formatContainer">
						<div id="formatLabel">Format:</div>
						<div id="formatInput">
							<select name="formatSelect" id="formatSelect">
								<option value="A4">A4</option>
								<option value="A5">A5</option>
							</select>
						</div>
					</div>
					<div id="orientationContainer">
						<div id="orientationLabel">Orientation:</div>
						<div id="orientationInput">
							<select name="orientationSelect" id="orientationSelect">
								<option value="P">Portrait</option>
								<option value="L">Landscape</option>
							</select>
						</div>
					</div>
					<div id="backgroundColorContainer">
						<div id="backgroundColorLabel">Background-Color:</div>
						<div id="backgroundColorInput">
							<input type="color" name="backgroundColor" id="backgroundColor" value="#ffffff">
						</div>
						<div id="backgroundSpace">or</div>
						<div id="backgroundImageLabel">Background-Image:</div>
						<div id="backgroundImage">
							<select name="backgroundSelect" id="backgroundSelect">
								<option> </option>
								<?php
									$replace = array('.jpeg' => '', '.jpg' => '', '.gif' => '', '.png' => '', '_' => ' ');
									
									$files = scandir('images');
									
									foreach($files as $file){
										
										if(is_file('images/' . $file)){
											$filePath = 'images/' . $file;
											$text = strtr($file, $replace);
											$text = ucwords($text);
										
											echo "<option value=\"$filePath\">$text</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
			</fieldset>
		
			<fieldset>
				<legend>Image Properties</legend>
					<div class="imageContainer">
						<div id="imageLabel">Image:</div>
						<div id="imageInput">
							<input type="file" name="upload1" id="upload1" accept="image/*">
						</div>
						<div class="removeImageButton">
							<input type="button" name="removeImageButton1" id="removeImageButton1" value="Remove">
						</div>
					</div>
					<div class="imageContainer">	
						<div id="imageInput">
							<input type="file" name="upload2" id="upload2" accept="image/*">
						</div>
						<div class="removeImageButton">
							<input type="button" name="removeImageButton2" id="removeImageButton2" value="Remove">
						</div>
					</div>
			</fieldset>

			<fieldset>
				<legend>Text Properties</legend>
					<div id="textContainer">
						<div id="textLabel">Text:</div>
						<div id="textInput">
							<input type="text" name="textField" id="textField">
						</div>
						<div id="addTextButton">
							<input type="button" name="addTextButton" id="addTextButton" value="Add Text">
						</div>
						<div id="removeTextButton">
							<input type="button" name="removeTextButton" id="removeTextButton" value="Remove Text">
						</div>
						<div class="clearBoth"></div>
					</div>
					
					<div id="fontFamilyContainer">
						<div id="fontFamilyLabel">Font-Family:</div>
						<div id="fontFamilyInput">
							<select name="fontFamilySelect" id="fontFamilySelect">
								<option value="Arial">Arial</option>
								<option value="Courier">Courier</option>
								<option value="Helvetica">Helvetica</option>
								<option value="Times">Times New Roman</option>
							</select>
						</div>
						<div class="clearBoth"></div>
					</div>
					
					<div id="fontSizeContainer">
						<div id="fontSizeLabel">Font-Size:</div>
						<div id="fontSizeInput">
							<select name="fontSizeSelect" id="fontSizeSelect">
								<option value="10">10</option>
								<option value="10">11</option>
								<option value="12">12</option>
								<option value="10">13</option>
								<option value="14">14</option>
								<option value="10">15</option>
								<option value="16">16</option>
								<option value="10">17</option>
								<option value="18">18</option>
								<option value="10">19</option>
								<option value="20">20</option>
								<option value="20">21</option>
								<option value="22">22</option>
								<option value="20">23</option>
								<option value="24">24</option>
								<option value="20">25</option>
								<option value="26">26</option>
								<option value="20">27</option>
								<option value="28">28</option>
								<option value="20">29</option>
								<option value="30">30</option>
							</select>
						</div>
						<div class="clearBoth"></div>
					</div>
					
					<div id="fontStyleContainer">
						<div id="fontStyleLabel">Font-Style:</div>
						<div id="fontBoldInput">
							<input type="checkbox" name="boldCheckbox" id="boldCheckbox" value="bold">Bold
						</div>	
						<div id="fontItalicInput">	
							<input type="checkbox" name="italicCheckbox" id="italicCheckbox" value="italic">Italic
						</div>	
						<div id="fontUnderlineInput">	
							<input type="checkbox" name="underlineCheckbox" id="underlineCheckbox" value="underline">Underline
						</div>
					<div class="clearBoth"></div>
					</div>
					
					<div id="textColorContainer">
						<div id="textColorLabel">Text-Color:</div>
						<div id="textColorInput">
							<input type="color" name="textColor" id="textColor">
						</div>
					</div>
			</fieldset>
		
			<fieldset>
				<legend>Flyer Operations</legend>
					<div id="resetFlyer">
						<input type="button" name="resetFlyer" id="resetFlyer" value="Reset Flyer">
					</div>
					<div id="generatePDF">
						<input type="button" name="generatePDF" id="generatePDF" value="Generate PDF">
					</div>
			</fieldset>
		</form>
	</div>
	
	<div id="view">
		<div id="image1">
			<img id="img1" src="#">
		</div>
		<div id="image2">
			<img id="img2" src="#">
		</div>
	</div>
	<div class="clearBoth"></div>
	<div id="response"></div>
</div>
</body>
</html>
