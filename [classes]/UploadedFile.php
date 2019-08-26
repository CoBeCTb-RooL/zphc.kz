<?php
class UploadedFile{
	
	public    $name
			, $type
			, $tmpName
			, $error
			, $size
			
			, $sourceFileName
			, $ext
			
			, $destDir
			, $newFileName
			, $destFileFullPath
			;
	
			
	
			
	function __construct($arr)
	{
		$this->name = $arr['name'];
		$this->type = $arr['type'];
		$this->tmpName = $arr['tmp_name'];
		$this->error = $arr['error'];
		$this->size = $arr['size'];
		
		
		$dot=strrpos($this->name, '.');
		$this->sourceFileName = substr($this->name, 0, $dot);
		$this->ext = strtolower(substr($this->name,  $dot+1));
	}
	
	
	
	
	function save()
	{
		$error = null;
		
		if(!trim($this->newFileName) )
			$error =  new Error('Не задано имя нового файла! (для "'.$this->name.'")');
		else
		{
			if(is_uploaded_file($this->tmpName))
			{
				mkdir($this->destDir, 0777, $recursive=true);
				
				$destFile = $this->destDir.'/'.$this->newFileName.'.'.$this->ext;
				$this->destFileFullPath = $destFile;
				
				//vd($this);
				
				if( move_uploaded_file($this->tmpName, $destFile))
				{
					/*
					#	всё ок, проблем не возвращаем
					echo '<hr><hr><hr>';
					//vd($destFile);
					# 	проверка , не огромен ли файл, и его ужимка
					$tmp = getimagesize($destFile);
					//vd($tmp);
					//$weight = filesize($destFile);
					
					# 	если это картинка
					if($tmp)
					{
						$w = $tmp[0];
						$h=$tmp[1];
						if($w>$h)
						{
							if($w > MAX_PIC_WIDTH)
							{
								$image = new ImageResize($destFile);
								$image->resizeToWidth(MAX_PIC_WIDTH);
								$image->quality_jpg = 100;
								$image->quality_png = 9;
								$image->save($destFile);
							}
						}
						else
						{
					
							if($h > MAX_PIC_HEIGHT)
							{
								$image = new ImageResize($destFile);
								$image->resizeToHeight(MAX_PIC_HEIGHT);
								$image->quality_jpg = 100;
								$image->quality_png = 9;
								$image->save($destFile);
								//vd($destFile);
							}
						}
					}*/
				}
				else
					$error = new Error('Не удалось загрузить файл <b>'.$this->sourceFileName.'</b>');
			}
			else 
				$error = new Error('Файл <b>'.$this->sourceFileName.'</b> не загружен..');
				
		}
			
		
	}
	
	
	
	
	function resizeImageToMaxWidthAndHeight($maxW=MAX_PIC_WIDTH, $maxH=MAX_PIC_HEIGHT)
	{
		$destFile = $this->destFileFullPath;
		
		$tmp = getimagesize($destFile);
		//vd($tmp);
		//$weight = filesize($destFile);
			
		# 	если это картинка
		if($tmp)
		{
			$w = $tmp[0];
			$h=$tmp[1];
			if($w>$h)
			{
				if($w > $maxW)
				{
					$image = new ImageResize($destFile);
					$image->resizeToWidth($maxW);
					$image->quality_jpg = 100;
					$image->quality_png = 9;
					$image->save($destFile);
				}
			}
			else
			{
					
				if($h > $maxH)
				{
					$image = new ImageResize($destFile);
					$image->resizeToHeight($maxH);
					$image->quality_jpg = 100;
					$image->quality_png = 9;
					$image->save($destFile);
					//vd($destFile);
				}
			}
		}
	}
	
	
	
}