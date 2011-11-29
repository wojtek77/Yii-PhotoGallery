# Photo gallery based on PrettyPhoto

## Usage

1. Copy the directory "prettyPhoto" to directory "/protected/extensions" in project Yii

2. In View:
	
		$this->beginWidget('ext.prettyPhoto.PrettyPhoto', array(
			'id'=>'pretty_photo',   		// not change any of this, is set in css
			'photosDir'=>'./photos',		// dir with photos (should be given to relative paths)
			//'gallery'=>false,
			
			/** settings for jquery */
			'options'=>array(
				//'opacity'=>0.90,            // transparency
				//'overlay_gallery'=>false,   // for false is not a tiny thumbnail navigation
				//'slideshow'=>3600,          // duration of a slide during automatic playback (in seconds)
				//'show_title'=>false,        // at the top shows the title (this is in img in alt)
				//'allow_resize'=>false,      // for false size of the image is not changed
			),
		));
		
		/*
			here you can manually specify links to images for example:
			<a title="" href="./photos/*.jpg"><img src="./photos/*/miniatures/*.jpg" alt="any description" /></a>
			
			or just set the directory "photosDir" where previously been placed pictures, then this place is empty
		*/
		
		$this->endWidget();
	
## Changes in relation to PrettyPhoto

1. Automatic generation of links to photos

2. Automatic generation of miniature images in a subdirectory of "miniatures"

3. Acceleration of the script "jquery.prettyPhoto.js"
