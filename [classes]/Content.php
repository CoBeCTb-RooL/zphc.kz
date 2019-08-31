<?php
class Content{
	
	
	public $title;
	public $content;
	
	public $metaKeywords;
	public $metaDescription;
	
	public $sectionHeader;
	
	public $titlePostfix;
	public $titleSeparator;
	public $isTitleStartsWithPostfix;


	private $_sections;
	
	
	function __construct()
	{
		global $_CONFIG;
			
	}
	
	
	function setTitle($str)
	{
		global $_GLOBALS, $_CONFIG;

		if(!$this->isTitleStartsWithPostfix)
			$title = $str.''.$this->titleSeparator.''.$this->titlePostfix;
		else
			$title = $this->titlePostfix.''.$this->titleSeparator.''.$str;
		
		$this->title = $title;
	}




	function section($name, $str=null)
    {
        if($str !== null)
            $this->_sections[$name][] = $str;

        else
        {
            foreach ($this->_sections[$name] as $val)
                $ret .= $val;
            return $ret;
        }
//        vd($name);
    }
	
	
}