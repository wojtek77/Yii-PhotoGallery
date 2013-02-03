## Overview

It is based on the extension **prettyphoto** <http://www.yiiframework.com/extension/prettyphoto>  
and a jQuery based lightbox clone <https://github.com/scaron/prettyphoto>

## Usage

1. Copy the directory **prettyPhoto** to directory **"/protected/extensions"** in project Yii

2. In View:
	
		$this->beginWidget('ext.prettyPhoto.PrettyPhoto', array(
			'id'=>'pretty_photo',   		// not change any of this, is set in css
			'photosDir'=>'./photos',		// dir with photos (should be given to relative paths)
			//'gallery'=>false,
			
			/* settings for jquery */
			'options'=>array(
				//'opacity'=>0.90,            // transparency
				//'overlay_gallery'=>false,   // for false is not a tiny thumbnail navigation
				//'slideshow'=>3600,          // duration of a slide during automatic playback (in seconds)
				//'show_title'=>false,        // at the top shows the title (this is in img in alt)
				//'allow_resize'=>false,      // for false size of the image is not changed
			),
		));
		$this->endWidget();

3. Copy your photos to the directory **"/photos"**