<?PHP
/*
=====================================================
 Number to Words
-----------------------------------------------------
 php Class --- by Borhan.h (me@bor691.ir)
-----------------------------------------------------
 https://github.com/zumoshi/horuf
-----------------------------------------------------
 http://Bor691.ir/
-----------------------------------------------------
 Copyright (c) 2014 Bor691
=====================================================
*/
/*
	Usage:
		$converter=new horuf();
		echo $converter->convert(568);
*/

class horuf{
	const Cardinal = 0;
	const Ordinal = 1;
	const Ordinal2 = 2;
	function __construct($lang='farsi',$tartibi=0){
		$this->pasvand='';
		$this->replace=array();
		switch($lang){
			case 'fa':
			case 'farsi':
			case 'persian':
				$this->exception=array(0=>'صفر');
				$this->yekan  = explode('،','،یک،دو،سه،چهار،پنج،شش،هفت،هشت،نه');
				$this->dahgan = explode('،','،،بیست،سی،چهل،پنجاه،شصت،هفتاد،هشتاد،نود');
				$this->dahha  = explode('،','ده،یازده،دوازده،سیزده،چهارده،پانزده،شانزده،هفده،هجده،نوزده');
				$this->sadgan = explode('،','،صد،دویست،سیصد،چهارصد،پانصد،ششصد،هفتصد،هشتصد،نهصد');
				$this->part   = explode('،','،، هزار، میلیون، میلیارد');
				$this->separator=' و ';
				$this->lang='fa';
				break;
			case 'en':
			case 'eng':
			case 'englist':
				$this->exception=array(0=>'zero');
				$this->yekan  = array('','one','two','three','four','five','six','seven','eight','nine');
				$this->dahgan = array('','','twenty','thirty','forty','fifty','sixty','seventy','eighty','ninety');
				$this->dahha  = array('ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen');
				$this->sadgan = $this->yekan;
				$this->part   = array('',' hundred',' thousand',' million',' billion'); 
				$this->separator=' ';
				$this->lang='en';
				break;
			default:
				return false;
		}	
		if($tartibi>0){
			switch($lang){
				case 'fa':
				case 'farsi':
				case 'persian':
					$this->exception = ($tartibi==2)?
					array(
						0=>'صفرمین',
						1=>'اولین',
						3=>'سومین')
					:array(
						0=>'صفرم',
						1=>'اول',
						3=>'سوم');
					$this->pasvand=($tartibi==2)?'مین':'م';
					$this->replace=array('سه','یک');
					break;
				case 'en':
				case 'eng':
				case 'englist':
					$this->exception=array(0=>'zero',1=>'first',2=>'second',3=>'third');
					$this->pasvand='th';
					$this->replace=array('one','two','three');
					break;
				default:
					return false;
			}	
		}
	}
	function convert($num){
		//$this->ifnull($this->exception[$num],$this->yekan[$num]);
		if(isset($this->exception[$num]))return $this->exception[$num];
		$res=$this->remove_separator($this->convert_millions($num),$this->separator);
		foreach ($this->replace as $rep) {
			//bugfix for En [first,second,third]
			if($this->has_str($res, $rep))return substr($res,0,-strlen($rep)).$this->exception[$num%10];
		}
		//bugfix for eightth
		if(substr($res, -1)==substr($this->pasvand,0,1))return substr($res, 0, -1).$this->pasvand;
		return $res.$this->pasvand;
	}
	function convert_millions($num){
		if($num>=1000000)return $this->convert_millions(floor($num/1000000))."{$this->part[3]}{$this->separator}".$this->convert_thousands($num%1000000);
		return $this->convert_thousands($num);
	}
	function convert_thousands($num){
		if($num>=1000)return $this->convert_thousands(floor($num/1000))."{$this->part[2]}{$this->separator}".$this->convert_hundreds($num%1000);
		return $this->convert_hundreds($num);
	}
	function convert_hundreds($num){
		if($num>99)return $this->sadgan[floor($num/100)]."{$this->part[1]}{$this->separator}".$this->convert_tens($num%100);
		return $this->convert_tens($num);
	}
	function convert_tens($num){
		if($num<10)return $this->yekan[$num];
		if($num>=10 && $num<20)return $this->dahha[$num-10];
		if($num%10==0)return $this->dahgan[floor($num/10)];
		return $this->dahgan[floor($num/10)].$this->separator.$this->yekan[$num%10];
	}
	function has_str($res,$sep){
		return (substr($res,-strlen($sep))==$sep);
	}
	function remove_separator($res){
		return ($this->has_str($res, $this->separator))?substr($res,0,-strlen($this->separator)):$res;
	}
	function ifnull($var, $default=null) {return is_null($var) ? $default : $var;}
}