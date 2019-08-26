<?php
class FieldType{
	
	static $items;
	
	public   $type
		   , $label
		   , $htmlName
		   , $isRequired
		   , $isShownInList
		   , $isHighlighted
		   , $fileType
		   , $param1
	;
	 
		
	const VARCHAR 		= 'varchar';
	const TEXT 			= 'text';
	const WYSIWYG 		= 'wysiwyg';
	const VIDEO_HTML	= 'video_html';
	const INT 			= 'int';
	const FLOAT 		= 'float';
	const DATETIME 		= 'datetime';
	const SELECT 		= 'select';
	const CHECKBOX 		= 'checkbox';
	const IMG 			= 'img';
	const FILE 			= 'file';
	
	
	static $fileTypes = array(
				self::IMG => array('jpg', 'jpeg', 'gif', 'png'),
				self::FILE => array('pdf', 'djvu', 'zip', 'gz', 'gzip', 'rar', '7z', 'doc', 'docx', 'xls', 'xlsx'),
			);
	
	
	
	
	
	function  __construct($type, $label, $htmlName, $isRequired, $isShownInList, $isHighlighted, $param1)
	{
		$this->type = $type;
		$this->label = $label;
		$this->htmlName = $htmlName;
		$this->isRequired = $isRequired;
		$this->isShownInList = $isShownInList;
		$this->isHighlighted = $isHighlighted;
		$this->param1= $param1;
		
	}
	
	
	
	/*
	function code($code)
	{
		return self::$items[$code];
	}*/
	
	
	
	
	function listHTML($value)
	{
		ob_start();
		
		
		switch($this->type)
		{
			case self::CHECKBOX:
				if($value)
					echo '<div style="text-align: center;">ДА</div>';
				else echo '<div style="text-align: center;">нет</div>';
			break;
			
			
			case self::IMG:?>
				<div style="text-align: center; ">
					<a class="highslide" onclick="return hs.expand(this)" href="<?= $value ? '/'.UPLOAD_IMAGES_REL_DIR.$value : Funx::noPhotoSrc()?>" title="нажмите чтобы увеличить" >
						<img src="<?=$value ? Media::img($value) : Funx::noPhotoSrc()?>&width=90&height=60" alt="нажмите, чтобы увеличить">
					</a>
				</div>
			<?php 	
			break;
			
			
			case self::FILE:?>
				<div style="text-align: center; ">
					<a  href="<?= $value ? '/'.UPLOAD_IMAGES_REL_DIR.$value : Funx::noPhotoSrc()?>"  ><?=$value?></a>
				</div>
			<?php 	
			break;
			
			
			case self::VIDEO_HTML:?>
					<div style="">
						<?=str_replace('<iframe ', '<iframe style="width: 170px; height: 120px; " ', $value)?>
					</div>
				<?php 	
				break;
				
			case self::DATETIME:
				echo mb_strtolower(Funx::mkDate($value), 'utf-8');
			break;
				
			default:
				echo $value;
			break;
		}
		
		
		$str = ob_get_clean();
		return $str;
	}
	
	
	
	
	
	
	function editHTML($value)
	{
		/*vd($value);
		return;*/
		ob_start();
	
	
		switch($this->type)
		{
			case self::VARCHAR:
			case self::INT:
			case self::FLOAT:?>
				<input type="text" value="<?=htmlspecialchars($value)?>" name="<?=$this->htmlName?>" id="<?=$this->htmlName?>" style="width: <?=($this->type == self::INT || $this->type == self::FLOAT ? '50' : '500' )?>px; " >
			<?php 
			break;
			
			
			
			case self::TEXT:
			case self::VIDEO_HTML:?>
				<textarea name="<?=$this->htmlName?>" id="<?=$this->htmlName?>" style="width: 500px; height: 67px; "><?=str_replace("<br />", "\n", htmlspecialchars($value))?></textarea>
			<?php 
			break;
			
			
			
			case self::DATETIME:?>
			<input type="text" name="<?=$this->htmlName?>" id="<?=$this->htmlName?>" value="<?=$value?>"  style="width:120px"> <img id="<?=$this->htmlName?>-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
					
					<script>
						Calendar.setup({
						    inputField     :    "<?=$this->htmlName?>",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "<?=$this->htmlName?>-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>
			<?php 
			break;
			
			
			
			case self::WYSIWYG:?>
				<textarea name="<?=$this->htmlName?>" id="<?=$this->htmlName?>"><?=stripslashes($value)?></textarea>
				<script>CKEDITOR.replace( "<?=$this->htmlName?>", {height: "300px"} );</script>
			<?php 
			break;
			
			
			case self::CHECKBOX:?>
				<input type="checkbox"  name="<?=$this->htmlName?>" id="<?=$this->htmlName?>" <?=$value ? ' checked="checked" ' : ''?>>
			<?php
			break;
			
			
			
			case self::IMG:
			case self::FILE:?>
				<?php 
				if($this->type == self::IMG)
				{?>
					<?php 
					if($value)
					{?>
					<div class="pic-wrap single" id="pic-<?=$this->htmlName?>-div" >
						<a href="/<?=UPLOAD_IMAGES_REL_DIR?><?=$value?>" onclick="return hs.expand(this)" class="highslide ">
							<img src="<?=Media::img($value.'&height=120')?>">
						</a>
					</div>
					<div class="clear"></div>
					<?php 
					}?>
				<?php 	
				}?>
				
				<div class="clear"></div>
				<input type="file" name="<?=$this->htmlName?>[]" id="<?=$this->htmlName?>" >
			<?php
			break;
			
			
			
			default:?>
				<span style=" color: #B34949; font-size: 13px; font-weight: normal; text-align: right; text-shadow: 0 1px 2px #CCCCCC;">Oops! o_O Problems here! field: <b><?=$this->type?></b>(<?=$this->label?>)
			<?php 
			break;
		}
		
		
		$str = ob_get_clean();
		return $str;
	}
	
	
		
		
	
	
	function validateValueFromRequest()
	{
		$error = null;
		
		$value = $_REQUEST[$this->htmlName];
		
		switch($this->type)
		{
			case self::VARCHAR:
			case self::TEXT:
			case self::WYSIWYG:
				if($this->isRequired && !trim($value))
					$error = new Error('Заполните поле <b>"'.$this->label.'"</b>', $this->htmlName);
				break;
				
			case self::INT:
			case self::FLOAT:
				if(!$value)
				{
					if($this->isRequired)
						$error = new Error('Заполните поле <b>"'.$this->label.'"</b>', $this->htmlName);
				}
				elseif(!is_numeric($value))
					$error = new Error('Заполните корректно поле <b>"'.$this->label.'"</b>', $this->htmlName);
				break;
				
			
			case self::FILE:
			case self::IMG:
				if($this->isRequired && $_FILES[$this->htmlName])
				{
					$file = $_FILES[$this->htmlName][0];
					
					$uf = new UploadedFile($_FILES[$this->htmlName][0]);
					#	картинка не пришла
					if(!$uf->size)
					{
						if($this->type==self::IMG)
							$error = new Error('Картинка не загружена!');
						elseif($this->type==self::FILE)
							$error = new Error('Файл не загружен!');
					}
					
					$allowedExts = self::$fileTypes[$this->type == self::IMG ? self::IMG : self::FILE];
					#	недопустимый тип
					if( !in_array($uf->ext, $allowedExts) )
						$error = new Error('Для поля <b>"'.$this->label.'"</b> выбран файл недопустимого формата <b>'.$uf->ext.'</b>!  Допустимые: <b>'.join('</b>, <b>', $allowedExts).'</b>.');
					
					
				}
				break;
				
		}

		return $error;
	}
		
	
	
	
	function getValueFromRequest()
	{
		$value = $_REQUEST[$this->htmlName];
			
		switch($this->type)
		{
			case self::VARCHAR:
			case self::TEXT:
			case self::WYSIWYG:
			case self::DATETIME:
			default: 
				//$value = strPrepare($value);
				break;
				
			case self::INT: 
				$value = intval($value);
				break;
				
			case self::FLOAT:
				$value = floatval($value);
				break;
			
			case self::CHECKBOX:
				$value = $value ? true: false;
		}
		
		return $value;
	}
	
	
	
	
	
	function saveMedia($destDir, $preserveFilename = false)
	{
		$error = null; 
		
		$uploadedFile = new UploadedFile($_FILES[$this->htmlName][0]);
		$uploadedFile->newFileName = $preserveFilename ? Funx::correctFileName($uploadedFile->sourceFileName) : Funx::generateName();
		if($f->type == FieldType::FILE)
			$uploadedFile->newFileName = Funx::correctFileName($uploadedFile->name);
	
		$uploadedFile->destDir = $destDir;
		//$uploadedFile->destDir .= Funx::getSubdirsByFile($uploadedFile->newFileName);
	
		$error = $uploadedFile->save();
		if(!$error)
		{
			$uploadedFile->resizeImageToMaxWidthAndHeight(MAX_PIC_WIDTH, MAX_PIC_HEIGHT);
			$value = $uploadedFile->newFileName.'.'.$uploadedFile->ext;
		}
		//vd($uploadedFile);
		//vd($this);
		
		return $error ? $error : $value;
	}
	
	
		
	
}

