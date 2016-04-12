<?php
	// DEBUG:
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	//echo '<pre>'; print_r($_POST['images']); echo '</pre>'; 
	//echo '<pre>'; print_r($_FILES); echo '</pre>'; 
	//die();

	// Constants
	define('FPDF_FONTPATH', 'libs/fpdf17/font');
	define('UPLOAD_FOLDER', 'uploads/');
	
	// Library and functions
	require('libs/fpdf17/fpdf.php');
	require('inc/additionalFunctions.php');
	
	//*********************************************************************************
	// Global variables for default document
	$orientation 			= 'P';
	$format 				= 'A4';
	$backgroundColor['r']	= 255;
	$backgroundColor['g']	= 255;
	$backgroundColor['b'] 	= 255;
	
	// Get orientation, format and background-color
	if(isset($_POST['formatSelect']) && $_POST['formatSelect'] != ''){
		$format = $_POST['formatSelect'];
	}
	
	if(isset($_POST['orientationSelect']) && $_POST['orientationSelect'] != ''){
		$orientation = $_POST['orientationSelect'];
	}
	
	if(isset($_POST['backgroundColor']) && $_POST['backgroundColor'] != ''){
		$backgroundColor = hex2rgb($_POST['backgroundColor']);
	}

	//*********************************************************************************
	// Create new FPDF with orientation and format
	$pdf = new FPDF($orientation, 'mm', $format);
	$pdf->AddPage();
	
	//*********************************************************************************	
	// Set background-color by checking format & orientation
	if ($format == 'A4' && $orientation == 'P') {
		$pdf->SetFillColor($backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$pdf->Rect(0, 0, 210, 297, 'F');	// A4 & Portrait
	} else if ($format == 'A4' && $orientation == 'L') {
		$pdf->SetFillColor($backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$pdf->Rect(0, 0, 297, 210, 'F');	// A4 & Landscape
	} else if ($format == 'A5' && $orientation == 'P') {
		$pdf->SetFillColor($backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$pdf->Rect(0, 0, 149, 210, 'F');	// A5 & Portrait
	} else if ($format == 'A5' && $orientation == 'L') {
		$pdf->SetFillColor($backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$pdf->Rect(0, 0, 210, 149, 'F');	// A5 & Landscape
	}
	
	//*********************************************************************************
	$x = 0;
	$y = 0;
	
	// Get background-image
	if(isset($_POST['backgroundSelect']) && $_POST['backgroundSelect'] != ''){
		$imagePath = $_POST['backgroundSelect'];
		
		// Set background-image
		if ($format == 'A4' && $orientation == 'P') {
			$pdf->Image($imagePath, $x, $y, 210, 297);
		} else if ($format == 'A4' && $orientation == 'L') {
			$pdf->Image($imagePath, $x, $y, 297, 210);
		} else if ($format == 'A5' && $orientation == 'P') {
			$pdf->Image($imagePath, $x, $y, 149, 210);
		} else if ($format == 'A5' && $orientation == 'L') {
			$pdf->Image($imagePath, $x, $y, 210, 149);
		}
	}
	
	
	//*********************************************************************************
	//Get image(s)
	
	$file_array = $_FILES;
	
	//DEBUG:
	//echo '<pre>'; print_r($file_array); echo '</pre>';
	//echo '<pre>'; print_r($_POST['images']); echo '</pre>';
	//echo '<pre>'; print_r($_POST['images']['0']['x']); echo '</pre>';
	//die();

		$file_index = 0;
	
		foreach($file_array as $i => $file){
			
			$target_path = UPLOAD_FOLDER;
			
			if(!$file['error']){
				
				// rename the file
				$file_name_tokens = explode(".", strtolower($file['name']));
				
				$new_file_name = $file_name_tokens[0] . md5(time().$i) . '.' . $file_name_tokens[1];
				
				$target_path .= $new_file_name;
				
				// save it inside the array for later
				$file_array[$i]['path'] = $target_path;
				
				move_uploaded_file($file_array[$i]['tmp_name'], $target_path);
				
				$file_array[$file_index] = $file_array[$i];
				unset($file_array[$i]);
				$file_index++;
				
			}
		}
	
		$image_array = array();
		
		//echo '<pre>'; print_r($file_array); echo '</pre>';
		//die();
		
		if(isset($_POST['images']) && is_array($_POST['images'])){
			
			foreach($_POST['images'] as $index => $image){
			
				if($image['x'] >= 0){
				
					$image_array[$index] = array_merge($image, $file_array[$index]);
					
					//DEBUG:
					//echo '<pre>'; print_r($file_array[$index]); echo '</pre>';
					//die();
			
					//DEBUG:
					//echo '<pre>'; print_r($index); echo '</pre>';
					//die();
					
					$pdf->Image(
						$image_array[$index]['path'],
						pixelsToMillimeters($image_array[$index]['x']),
						pixelsToMillimeters($image_array[$index]['y']),
						pixelsToMillimeters($image_array[$index]['width']),
						pixelsToMillimeters($image_array[$index]['height'])
					);
						
				}
			}
		}
//	}
	
	//*********************************************************************************
	//Get all text
	if(isset($_POST['paragraphs']) && is_array($_POST['paragraphs'])){
		
		foreach($_POST['paragraphs'] as $p){
		
			// Variables for text
			$textColor['r'] = 0;
			$textColor['g'] = 0;
			$textColor['b'] = 0;
			$fontFamily 	= 'Arial';
			$fontSize		= '';
			$fontWeight 	= '';
			$fontStyle 		= '';
			$textDecoration = '';
			$x				= 10;
			$y 				= 10;
			$string 		= '';
			
			// Get text-color
			if(isset($p['textColor']) && $p['textColor'] != ''){
				$textColor = hex2rgb($p['textColor']);
			}
			
			// Get font-family, font-size, font-styles
			if(isset($p['font-family']) && $p['font-family'] != ''){
				$fontFamily = $p['font-family'];
			}
			
			if(isset($p['font-size']) && $p['font-size'] != ''){
				$fontSize = pixelsToPoints(substr($p['font-size'], 0, strlen($p['font-size'])-2));
			}
			
			if(isset($p['font-weight']) && $p['font-weight'] != ''){
				if($p['font-weight'] > 400){
					$fontWeight = 'B';
				}
			}
			
			if(isset($p['font-style']) && $p['font-style'] != ''){
				if($p['font-style'] == 'italic'){
					$fontStyle = 'I';
				}
			}
			
			if(isset($p['text-decoration']) && $p['text-decoration'] != ''){
				if($p['text-decoration'] == 'underline'){
					$textDecoration = 'U';
				}
			}
			
			// Get text and its position x, y 
			if(isset($p['textField']) && $p['textField'] != ''){
				$string = $p['textField'];
			}
			
			if(isset($p['x']) && $p['x'] != ''){
				$x = pixelsToMillimeters($p['x']);
			}
			
			if(isset($p['y']) && $p['y'] != ''){
				$y = pixelsToMillimeters($p['y']);
			}
			
			//Set text-color
			$pdf->SetTextColor($textColor['r'], $textColor['g'], $textColor['b']);
			
			// Set font-family, font-styles, font-size
			$pdf->SetFont($fontFamily, $fontWeight.$fontStyle.$textDecoration, $fontSize);
			
			// Set text
			$pdf->Text($x, $y, $string);
		}
	}
	
	//*********************************************************************************
	
	$pdf->Output();
	
?>