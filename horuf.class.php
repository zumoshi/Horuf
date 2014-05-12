<?PHP
/*
=====================================================
 Number to Words
-----------------------------------------------------
 php Class --- by Borhan.h (me@bor691.ir)
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
		switch($lang){
			case 'farsi':
			case 'persian':
				$this->exception=array();
				$this->yekan  = explode('،','،یک،دو،سه،چهار،پنج،شش،هفت،هشت،نه');
				$this->dahgan = explode('،','،،بیست،سی،چهل،پنجاه،شصت،هفتاد،هشتاد،نود');
				$this->dahha  = explode('،','ده،یازده،دوازده،سیزده،چهارده،پانزده،شانزده،هفده،هجده،نوزده');
				$this->sadgan = explode('،','،صد،دویست،سیصد،چهارصد،پانصد،ششصد،هفتصد،هشتصد،نهصد');
				$this->part   = explode('،','صفر،، هزار، میلیون');
				$this->pasvand='';
				$this->separator=' و ';
				break;
			default:
				return false;
		}	
		if($tartibi>0){
			switch($lang){
				case 'farsi':
				case 'persian':
					$this->exception = ($tartibi==2)?
					array(
						1=>'اولین',
						3=>'سومین')
					:array(
						1=>'اول',
						3=>'سوم');
					$this->yekan[3]='سو';
					$this->pasvand=($tartibi==2)?'مین':'م';
					break;
				default:
					return false;
			}	
		}
	}
	function convert($num){
		if($num==0)return $this->part[0];
		if(isset($this->exception[$num]))return $this->exception[$num];
		return $this->remove_separator($this->convert_millions($num),$this->separator).$this->pasvand;
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
	function remove_separator($res){
		return (substr($res,-strlen($this->separator))==$this->separator)?substr($res,0,-strlen($this->separator)):$res;
	}
}