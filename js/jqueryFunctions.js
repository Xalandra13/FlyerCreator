/* 
	Flyer Creator
	27.04.15, db
	jQuery functions for view
*/

$(document).ready(function(){

	// global variables
	var selectedElement = null;
	var view = $('#view');
	var fontFamilies = ['Arial', 'Courier', 'Helvetica', 'Times'];
	
	// functions for flyer properties
	$('#formatSelect').on('change', setFormat);
	$('#orientationSelect').on('change', setOrientation);
	$('#backgroundColor').on('change', setBackgroundColor);
	$('#backgroundSelect').on('change', setBackgroundImage);
	
	// functions for image properties
	$('#removeImageButton1').on('click', function(){
		$('#img1').prop('src', '#');
		$('#image1').hide();
		$('#upload1').val('');
	});
	
	$('#removeImageButton2').on('click', function(){
		$('#img2').prop('src', '#');
		$('#image2').hide();
		$('#upload2').val('');
	});
	
	// functions for text properties
	$('#textField').on('change input paste', setTextToSelectedElement);
	$('#addTextButton').on('click', addTextElementToView);
	$('#removeTextButton').on('click', removeSelectedTextElementFromView);
	
	$('#fontFamilySelect').on('change', setFontFamilyToSelectedElement);
	$('#fontSizeSelect').on('change', setFontSizeToSelectedElement);
	$('#boldCheckbox').on('change', setFontStyleToSelectedElement);
	$('#italicCheckbox').on('change', setFontStyleToSelectedElement);
	$('#underlineCheckbox').on('change', setFontStyleToSelectedElement);
	$('#textColor').on('change', setColorToSelectedElement);
	
	// functions for flyer operations
	$('#resetFlyer').on('click', resetFlyer);
	$('#generatePDF').on('click', generatePDF);
	
	
	/*** TEXT FUNCTIONS ***/
	
	function resetFamilyTextDropDownMenu(family){
		$('#fontFamilySelect').children().remove();
		for(i = 0; i < fontFamilies.length; i++){
			var option = $('<option>').attr('value',fontFamilies[i]).text(fontFamilies[i]);
			if(fontFamilies[i] == family || family.indexOf(fontFamilies[i]) > -1){
				option.attr('selected', 'selected');
			}
			$('#fontFamilySelect').append(option);			
		}
	}
	
	function resetSizeTextDropDownMenu(size){
		$('#fontSizeSelect').children().remove();
		for(i = 10; i <= 30; i++){
			var option = $('<option>').attr('value', i).text(i);
			if(i == size){
				option.attr('selected', 'selected');
			}
			$('#fontSizeSelect').append(option);
		}
	}	
	
	// add text-element
	function addTextElementToView(){
		var textElement = $('<p>')
			.addClass('cursorStyle')
			.addClass('paragraph')
			.draggable()
			.selectable()
			.text('New text element')
			.css({
			'font-family': 'Arial',
			'font-size': '10px'
			})
			.click(
				function(){
					setSelectedElement($(this));
				}
			);
		view.append(textElement);
		setSelectedElement(textElement);
	}
	
	// remove text-element
	function removeSelectedTextElementFromView(){
		if(selectedElement){
			selectedElement.remove();
			selectedElement = null;
		}
	}
	
	// set all options to the selected element
	function setSelectedElement(element){
		
		selectedElement = element;
		
		$('#textField').val(element.text());
		
		var fontFamily = selectedElement.css('font-family');
		resetFamilyTextDropDownMenu(fontFamily);
		
		var fontSize = selectedElement.css('font-size').substring(0, 2);
		resetSizeTextDropDownMenu(fontSize);
		
		if(selectedElement.css('font-weight') == 'bold' || selectedElement.css('font-weight') == 700){
			$('#boldCheckbox').prop('checked', true);
			
		} else {
			$('#boldCheckbox').prop('checked', false);
		}
		
		if(selectedElement.css('font-style') == 'italic'){
			$('#italicCheckbox').prop('checked', true);
		} else {
			$('#italicCheckbox').prop('checked', false);
		}
		
		if(selectedElement.css('text-decoration') == 'underline'){
			$('#underlineCheckbox').prop('checked', true);
		} else {
			$('#underlineCheckbox').prop('checked', false);
		}
		
		var fontColor = element.css('color');
		var hexFontColor = rgb2hex(fontColor);
		//console.log(fontColor);
		//console.log(hexFontColor);
		$('#textColor').val(hexFontColor);
	}
	
	// change text
	function setTextToSelectedElement(){
		if(selectedElement){
			selectedElement.text($('#textField').val());
		}
	}
	
	// font-styles
	function setFontStyleToSelectedElement(){
		if(selectedElement){
			// bold
			if(selectedElement.css('font-weight') != 700){
				if($('#boldCheckbox').is(':checked')){
					selectedElement.css('font-weight', 700);
				}
			} else if(selectedElement.css('font-weight') == 700){
				if(!$('#boldCheckbox').is(':checked')){
					selectedElement.css('font-weight', 400);
				}
			}
			
			// italic
			if(selectedElement.css('font-style') != 'italic'){
				if($('#italicCheckbox').is(':checked')){
					selectedElement.css('font-style', 'italic');
				}
			} else if(selectedElement.css('font-style') == 'italic'){
				if(!$('#italicCheckbox').is(':checked')){
					selectedElement.css('font-style', 'normal');
				}
			}
			
			// underline
			if(selectedElement.css('text-decoration') == 'none'){
				if($('#underlineCheckbox').is(':checked')){
					selectedElement.css('text-decoration', 'underline');
				}
			} else if(selectedElement.css('text-decoration') == 'underline'){
				if(!$('#underlineCheckbox').is(':checked')){
					selectedElement.css('text-decoration', 'none');
				}
			}
		}
	}
	
	// font-size
	function setFontSizeToSelectedElement(){
		var fontSize = $('#fontSizeSelect option:selected').attr('value') + 'px';
		selectedElement.css('font-size', fontSize);
	}
	
	// font-family
	function setFontFamilyToSelectedElement(){
		var fontFamily = $('#fontFamilySelect option:selected').attr('value');
		selectedElement.css('font-family', fontFamily);
		
	}
	
	// text-color
	function setColorToSelectedElement(){
		var color = $('#textColor').val();
		selectedElement.css('color', color);
	}
	
	
	/*** FLYER FUNCTIONS ***/
	
	// format
	function setFormat(){
		var format = $('#formatSelect option:selected').attr('value');
		var orientation = $('#orientationSelect option:selected').attr('value');
		
		var width = 0;
		var height = 0;
		
		switch(format){
			case 'A4':
				// A4: 72 PPI, 595 x 842 Pixels (210 x 297 mm)
				width = 595;			
				height = 842;
				break;
				
			case 'A5':
				// A5: 72 PPI, 420 x 595 Pixels (148 x 210 mm)
				width = 420;
				height = 595;
				break;
					
			default:
				break;
		}

		switch(orientation){
			case 'P':
				view.css('width', width + 'px');
				view.css('height', height + 'px');
				break;
				
			case 'L':
				view.css('width', height + 'px');
				view.css('height', width + 'px');
				break;
				
			default:
				break;
		}
		
	}
	
	// orientation
	function setOrientation(){
		var width = view.css('width');
		var height = view.css('height');
		
		view.css('width', height);
		view.css('height', width);
	}
	
	// background-color
	function setBackgroundColor(){
		view.css('background-color', $('#backgroundColor').val());
	}
	
	// background-image	
	function setBackgroundImage(){
		var width = view.css('width');
		var height = view.css('height');
		var imageUrl = $('#backgroundSelect option:selected').attr('value');
		
		view.css('background-image', 'url(' + imageUrl + ')');
		view.css('background-size','cover');
		view.css('background-size', width + ' ' + height);
	}
	
	
	/*** OPERATIONS FUNCTIONS ***/
	
	// reset view
	function resetFlyer(){
		view.css('background-color', '#FFFFFF');
		view.css('width', '595px');
		view.css('height', '842px');
		view.empty();		
	}
	
	// generate pdf
	function generatePDF(){
	
		// text paragraphs
		var pCount = 0;
		$('.paragraph').each(function(){
			var paragraph = $(this);
			
			$('#flyerForm').append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][x]')
					.val( paragraph.offset().left - view.offset().left )
			);
			$('#flyerForm').append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][y]')
					.val( paragraph.offset().top - view.offset().top )
			);
			$('#flyerForm').append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][textField]')
					.val(paragraph.text())
			);
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][font-family]')
					.val(paragraph.css('font-family'))
			);
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][font-size]')
					.val(paragraph.css('font-size'))
			);
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][font-weight]')
					.val(paragraph.css('font-weight'))
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][font-style]')
					.val(paragraph.css('font-style'))
			);
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][text-decoration]')
					.val(paragraph.css('text-decoration'))
			);	
			$('#flyerForm').append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'paragraphs['+pCount+'][textColor]')
					.val(rgb2hex(paragraph.css('color')))
			);

			pCount++;
			
		});
		
		// images
		var iCount = 0;
		
		var image = $('#img1');
		if (image){
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][x]')
					.val( image.offset().left - view.offset().left )
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][y]')
					.val( image.offset().top - view.offset().top )
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][width]')
					.val(image.width())
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][height]')
					.val(image.height())
			);		
		}
		
		iCount++;
		
		image = $('#img2');
		if (image){
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][x]')
					.val( image.offset().left - view.offset().left )
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][y]')
					.val( image.offset().top - view.offset().top )
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][width]')
					.val(image.width())
			);	
			$("#flyerForm").append(
				$('<input>')
					.prop('type', 'text')
					.prop('name', 'images['+iCount+'][height]')
					.val(image.height())
			);		
		}
		
		iCount++;

		$('#flyerForm').submit();
	}

	
	/*** IMAGE FUNCTIONS ***/
	
	// hide image divs
	$('#image1').hide();
	$('#image2').hide();

	// get images
	function readURL(input){
		if(input.files && input.files[0]){
			var reader = new FileReader();
			
			reader.onload = function(e) {
				if(input.id == 'upload1'){
					$('#img1')
					.addClass('image')
					.attr('src', e.target.result)
					.show();
					$('#image1').show();
				}else{
					$('#img2')
					.addClass('image')
					.attr('src', e.target.result)
					.show();
					$('#image2').show();
				}
			}
			reader.readAsDataURL(input.files[0]);
		}	
	}
	
	$('#upload1').change(function(){
		readURL(this);
	});
	
	$('#upload2').change(function(){
		readURL(this);
	});
	
	// make div 1 draggable
	$('#image1').draggable(
		{cursor: 'move'}
	);
	// make image 1 resizable
	$('#img1').resizable();
	
	// make div 2 draggable
	$('#image2').draggable(
		{cursor: 'move'}
	);
	
	// make image 2 resizable
	$('#img2').resizable();
	
	
	//Function to convert hex format to a rgb color
	var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 
	
	function rgb2hex(rgb) {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}
	
	function hex(x) {
		return  hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
	}
	
});
