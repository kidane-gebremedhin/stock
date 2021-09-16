<?php
class Model_date extends CI_Model
{

public function getEthiopianDate(){
	 $CI =& get_instance();
	 $CI->load->model('model_global_var');
	 return $CI->model_global_var->isExpired('11/11/2012 16:10:13');
	 
	    $sheger = new \DateTimeZone('Africa/Addis_Ababa');
	    $now = \Andegna\DateTimeFactory::now($sheger);

		return 'Here-'.$now->getYear().'/'.$now->getMonth().'/'.$now->getDay();
	}

public static function getTimestamp($date1){
	if($date1==null)
		return 0;
	$day1=substr($date1, 0, strpos($date1, '/'));
	$month1=substr($date1, strpos($date1, '/')+1, (strrpos($date1, '/')-strpos($date1, '/'))-1);
	$year1=substr($date1, strrpos($date1, '/')+1); 
	$ethDate1=\Andegna\DateTimeFactory::of($year1, $month1, $day1);

	return $ethDate1->getTimestamp();
 }

 public static function getDateDiffernece($date1, $date2){
 	if($date1==null || $date2==0 || $date1=='' || $date2=='')
 		return null;

$hour1=0; $minute1=0; $second1=0; 
$hour2=0; $minute2=0; $second2=0;

 	$date1_time_HMS=null;
 	$date2_time_HMS=null;
 	if(strpos($date1, ' ')>0){
 		$date1_time_HMS=substr($date1, strpos($date1, ' ')+1);
 		$date1=substr($date1, 0, strpos($date1, ' '));
 	}
 	if(strpos($date2, ' ')>0){
 		$date2_time_HMS=substr($date2, strpos($date2, ' ')+1);
 		$date2=substr($date2, 0, strpos($date2, ' '));
 	}

	$day1=substr($date1, 0, strpos($date1, '/'));
	$month1=substr($date1, strpos($date1, '/')+1, (strrpos($date1, '/')-strpos($date1, '/'))-1);
	$year1=substr($date1, strrpos($date1, '/')+1); 

	if($date1_time_HMS!=null){
		$date1_time_HMS = str_replace(' ', '', $date1_time_HMS); 
		$hour1=substr($date1_time_HMS, 0, strpos($date1_time_HMS, ':'));
		$minute1=substr($date1_time_HMS, strpos($date1_time_HMS, ':')+1, (strrpos($date1_time_HMS, ':')-strpos($date1_time_HMS, ':'))-1);
		$second1=substr($date1_time_HMS, strrpos($date1_time_HMS, ':')+1); 
	}
	$ethDate1=\Andegna\DateTimeFactory::of($year1, $month1, $day1, $hour1, $minute1, $second1);

	$day2=substr($date2, 0, strpos($date2, '/'));
	$month2=substr($date2, strpos($date2, '/')+1, (strrpos($date2, '/')-strpos($date2, '/'))-1);
	$year2=substr($date2, strrpos($date2, '/')+1); 

	if($date2_time_HMS!=null){
		$date2_time_HMS = str_replace(' ', '', $date2_time_HMS); 
		$hour2=substr($date2_time_HMS, 0, strpos($date2_time_HMS, ':'));
		$minute2=substr($date2_time_HMS, strpos($date2_time_HMS, ':')+1, (strrpos($date2_time_HMS, ':')-strpos($date2_time_HMS, ':'))-1);
		$second2=substr($date2_time_HMS, strrpos($date2_time_HMS, ':')+1); 
	}

	$ethDate2=\Andegna\DateTimeFactory::of($year2, $month2, $day2, $hour2, $minute2, $second2);

	return $ethDate2->diff($ethDate1);
 }

 public function setLeapYearDate($month, $day)
		{
		$total_days=0;
		if($month==1)
		{
		$total_days=$day;
		}else if($month==2)
		{
		$total_days=31+$day;
		}else if($month==3)
		{
		$total_days=60+$day;
		}else if($month==4)
		{
		$total_days=91+$day;
		}else if($month==5)
		{
		$total_days=121+$day;
		}else if($month==6)
		{
		$total_days=152+$day;
		}else if($month==7)
		{
		$total_days=182+$day;
		}else if($month==8)
		{
		$total_days=213+$day;
		}else if($month==9)
		{
		$total_days=244+$day;
		}else if($month==10)
		{
		$total_days=274+$day;
		}else if($month==11)
		{
		$total_days=305+$day;
		}else if($month==12)
		{
		$total_days=335+$day;
		}else
		{
		return 0;
		}
		return $total_days;
		}


public function setYearDate($month, $day)
		{
		$total_days=0;
		if($month==1)
		{
		$total_days=$day;
		}else if($month==2)
		{
		$total_days=31+$day;
		}else if($month==3)
		{
		$total_days=59+$day;
		}else if($month==4)
		{
		$total_days=90+$day;
		}else if($month==5)
		{
		$total_days=120+$day;
		}else if($month==6)
		{
		$total_days=151+$day;
		}else if($month==7)
		{
		$total_days=181+$day;
		}else if($month==8)
		{
		$total_days=212+$day;
		}else if($month==9)
		{
		$total_days=243+$day;
		}else if($month==10)
		{
		$total_days=273+$day;
		}else if($month==11)
		{
		$total_days=304+$day;
		}else if($month==12)
		{
		$total_days=334+$day;
		}else
		{
		return 0;
		}
		return $total_days;
		}
public function isLeapYear($year){
	return ($year%4)==3;
}

public function converToEthiopianDate($year, $month, $day, $hour, $minute, $second){
	$gregorian = new \DateTime($year.'/'.$month.'/'.$day.' '.$hour.':'.$minute.':'.$second);
	$ethipic = new \Andegna\DateTime($gregorian);

	return $ethipic->getDay().'/'.$ethipic->getMonth().'/'.$ethipic->getYear().' '.$ethipic->getHour().':'.$ethipic->getMinute().':'.$ethipic->getSecond();
}

public function getCurrentDate(){

	$sheger = new \DateTimeZone('Africa/Addis_Ababa');
	$now = \Andegna\DateTimeFactory::now($sheger);

	$hour=$now->getHour()-6>0? $now->getHour()-6: $now->getHour()+18; 
	
	return $now->getDay().'/'.$now->getMonth().'/'.$now->getYear().' '.$now->getHour().':'.$now->getMinute().':'.$now->getSecond();
}

public static function getCurrentYear(){
	$sheger = new \DateTimeZone('Africa/Addis_Ababa');
	$now = \Andegna\DateTimeFactory::now($sheger);

	return $now->getYear();
}

public static function getCurrent_Rounded_Hour(){
	$sheger = new \DateTimeZone('Africa/Addis_Ababa');
	$now = \Andegna\DateTimeFactory::now($sheger);

	$hour=$now->getHour()-6>=0? $now->getHour()-6: $now->getHour()+18; 
	return $now->getMinute()>=30? ($hour+1) : $hour;
	}

	public static function getCurrent_DMY(){
	$sheger = new \DateTimeZone('Africa/Addis_Ababa');
	$now = \Andegna\DateTimeFactory::now($sheger);

	return $now->getDay().'/'.$now->getMonth().'/'.$now->getYear();
	}

	public static function getCurrent_HMS(){
	$sheger = new \DateTimeZone('Africa/Addis_Ababa');
	$now = \Andegna\DateTimeFactory::now($sheger);

	$hour=$now->getHour()-6>=0? $now->getHour()-6: $now->getHour()+18; 
	return $hour.':'.$now->getMinute().':'.$now->getSecond();
}

public static function getDate_Hour_Minute($entryDate){
 	$date1=$entryDate;
 	if($date1==null || $date1=='')
 		return null;
	
	$hour1=0; $minute1=0; $second1=0; 

 	$date1_time_HMS=null;
 	if(strpos($date1, ' ')>0){
 		$date1_time_HMS=substr($date1, strpos($date1, ' ')+1);
 		$date1=substr($date1, 0, strpos($date1, ' '));
 	}

	$day1=substr($date1, 0, strpos($date1, '/'));
	$month1=substr($date1, strpos($date1, '/')+1, (strrpos($date1, '/')-strpos($date1, '/'))-1);
	$year1=substr($date1, strrpos($date1, '/')+1); 

	if($date1_time_HMS!=null){
		$date1_time_HMS = str_replace(' ', '', $date1_time_HMS); 
		$hour1=substr($date1_time_HMS, 0, strpos($date1_time_HMS, ':'));
		$minute1=substr($date1_time_HMS, strpos($date1_time_HMS, ':')+1, (strrpos($date1_time_HMS, ':')-strpos($date1_time_HMS, ':'))-1);
		$second1=substr($date1_time_HMS, strrpos($date1_time_HMS, ':')+1); 
	}
	$ethDate1=\Andegna\DateTimeFactory::of($year1, $month1, $day1, $hour1, $minute1, $second1);

	return [$date1, $hour1, $minute1];
 }

}



