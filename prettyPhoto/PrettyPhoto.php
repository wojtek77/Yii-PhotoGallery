<?php
/**
* PrettyPhoto widget class file
*
* @package pbm.widgets
* @author Chris Yates
* @version 1.0
* @copyright Copyright &copy; 2011 PBM Web Development - All Rights Reserved
* @license BSD 3-Clause License
*
*/
/**
* PrettyPhoto encapsulates the {@link http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/ prettyPhoto} jQuery lightbox clone.
*
* To use the widget put the following code in a view:
* <pre>
* $this->beginWidget('path.to.PrettyPhoto', array
*   id'=>'pretty_photo',
*   // prettyPhoto options
*   'options'=>array(
*     'opacity''=>0.60,
*     'modal'=>true,
*   ),
* ));
*
* echo links to content here;
*
* $this->endWidget('path.to.PrettyPhoto');
* </pre>
*
* Content links do not require the rel="prettyPhoto" attribute; the widget adds this.
*
* By configuring the {@link options} property, you may specify the options that
* need to be passed to prettyPhoto. Please refer to the {@link http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/ prettyPhoto}
* documentation for possible options (name=>value pairs).
*/

Yii::import('ext.prettyPhoto.SimpleImage');

class PrettyPhoto extends CWidget {
    const THEME_PP_DEFAULT    = 'pp_default';
    const THEME_DARK_ROUNDED    = 'dark_rounded';
    const THEME_DARK_SQUARE     = 'dark_square';
    const THEME_FACEBOOK        = 'facebook';
    const THEME_LIGHT_ROUNDED   = 'light_rounded';
    const THEME_LIGHT_SQUARE    = 'light_square';

	/**
	* @property string URL to PrettyPhoto assets. If empty the assets under the
	widget's directory will be published
	*/
	public $assetsUrl;
	/**
	* @property string Name of the CSS file to be published. This must be in the
	* css directory under the assets URL
	*/
	public $cssFile='prettyPhoto.css';
	/**
	* @property array HTML options for the enclosing tag
	*/
	public $htmlOptions;
	/**
	* @property boolean Whether PrettyPhoto is in gallery (many items) mode
	*/
	public $gallery=true;
	/**
	* @property array Additional options for PrettyPhoto
	*/
	public $options=array();
	/**
	* @property string Name of the javaScript file to be published. This must be
	* under the assets URL
	*/
	public $scriptFile='js/jquery.prettyPhoto.js';
	/**
	* @property string The enclosing tag
	*/
	public $tag='div';
	/**
	* @property string The PrettyPhoto theme to use
	*/
	public $theme=self::THEME_DARK_ROUNDED;
    /**
	* @property string Catalog with photos to automatically create links
	*/
    public $photosDir;

	public function init() {
		if (empty($this->assetsUrl))
			$this->assetsUrl = Yii::app()->getAssetManager()->publish(
				dirname(__FILE__). DIRECTORY_SEPARATOR.'jqueryPrettyPhoto'
			);

		$this->registerClientScript();
		parent::init();

		$id=$this->getId();
		if (isset($this->htmlOptions['id']))
			$id = $this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		echo CHtml::openTag($this->tag,$this->htmlOptions)."\n";

		if (empty($this->options['theme']))
			$this->options['theme']=$this->theme;
		$options=empty($this->options) ? '' : CJavaScript::encode($this->options);

		Yii::app()->clientScript->registerScript(__CLASS__,"
			jQuery('#$id a').attr('rel','prettyPhoto".($this->gallery?'[]':'')."');
			jQuery('a[rel^=\"prettyPhoto\"]').prettyPhoto(".$options.');
		',CClientScript::POS_END);
        
        if (isset($this->photosDir))
            $this->createLinks($this->photosDir);
	}

	public function run() {
		echo CHtml::closeTag($this->tag);
	}

	protected function registerClientScript(){
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
        //$cs->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
		$cs->registerScriptFile($this->assetsUrl.'/'.$this->scriptFile);
		$cs->registerCssFile($this->assetsUrl.'/css/'.$this->cssFile);
	}
    
    protected function createLinks($dir)
    {
        $timeEnd = time()+ini_get('max_execution_time')-10;

        $dir = rtrim($dir,'/').'/';
        $dirMiniatures = $dir.'miniatures/';
        if ( ! is_dir($dirMiniatures)) mkdir($dirMiniatures, 0777, true);
        
        $filesPhoto = glob($dir.'*.{jpg,JPG,png,PNG,gif,GIF}', GLOB_BRACE);
        $filesPhoto = ($filesPhoto !== false) ? array_map('basename',$filesPhoto) : array();
        $filesMiniatur = glob($dirMiniatures.'*.{jpg,JPG,png,PNG,gif,GIF}', GLOB_BRACE);
        $filesMiniatur = ($filesMiniatur !== false) ? array_map('basename',$filesMiniatur) : array();

        /** eventual to create miniatures */
        $filesMiniaturCreate = array();
        foreach (array_diff($filesPhoto, $filesMiniatur) as $file)
        {
            if (time() > $timeEnd) break;

            $filesMiniaturCreate[] = $file;
            SimpleImage::createImageToHeight($dir.$file, $dirMiniatures.$file, 100);
        }

        /** eventual to delete miniatures */
        $filesMiniaturDelete = array();
        foreach (array_diff($filesMiniatur, $filesPhoto) as $file)
        {
            if (time() > $timeEnd) break;

            $filesMiniaturDelete[] = $file;
            unlink($dirMiniatures.$file);
        }

        /** create links */
        $filesMiniatur = array_diff(
            array_merge($filesMiniatur, $filesMiniaturCreate),
            $filesMiniaturDelete
        );
        foreach ($filesMiniatur as $file)
        {
            echo
                '<a title="" href="'.Yii::app()->request->baseUrl.'/'.$dir.$file.'">'
                .'<img src="'.Yii::app()->request->baseUrl.'/'.$dirMiniatures.$file.'" alt="'.$file.'" />'
                .'</a>';
        }
    }
}
