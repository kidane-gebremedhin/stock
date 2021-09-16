<?php

class Model_global_var extends CI_Model
{
    protected $table="language_strings";

    public static $customer_prefix="TOFF/C";
    public static $vendor_prefix="TOFF/V";
    public static $toffegna_prefix="TOFF/T";
    public static $employee_prefix="TOFF/E";
    public static $meadi_prefix="O";
    public static $message_prefix="M";
    public static $selectedLang="tig";
    public static $cookieLifeTime=60*60*24*365;
    //public static $view_count_interval_in_hours=1;//24*60;
    public static $reportIntervalInMonths=6;
    public static $newYear_dayMonth="1/1";

public static function readSMS(){

  $PAGENUMBER=1; 
  $PAGINATIONSIZE=50;
  $messages=array();

  $old_path = getcwd();
  $shell_path = storage_path().'/shell_scripts';
  chdir($shell_path);

  /*
  $number="0919054098";
  $message="Dear Developer KG Again! Now everything is ready to go:";
  */
   for($PAGENUMBER=1; true; $PAGENUMBER++){
  
  $output = shell_exec('./read_sms.sh "'.$PAGENUMBER.'" "'.$PAGINATIONSIZE.'"');
  
  // Load the XML
  $xmlResponse = simplexml_load_string($output);
  // JSON encode the XML, and then JSON decode to an array.
  $responseArray = json_decode(json_encode($xmlResponse), true);
  $currentPageMessagesCount=$responseArray['Count'];
  if($currentPageMessagesCount==0)
    break;
  $currentPageMessages=$responseArray['Messages']['Message'];
  foreach($currentPageMessages as $message){
    array_push($messages, $message);
  }
  //echo $PAGENUMBER.': '.$responseArray['Count']."<br>";
  //dd($output);    
    }
//dd($messages[count($messages)-1]['Phone']);
//dd("End");
  #$output = shell_exec('./send_sms.sh "'.$number.'" "'.$message.'"');
  chdir($old_path);

  /*
  foreach($responseArray['Messages']['Message'] as $message){
  echo 'PhoneNumber: '.$message["Phone"].' Date: '.$message["Date"]."<br>Message: ".$message['Content']."<br>____________________________________________<br>";
  }
  */
  //dd($messages);
return $messages;
}

 public static function getEthipicDateObject($ethDate){
  if($ethDate==null)
    return null;
  $dateArr=explode(" ", $ethDate);
  $year=0; $month=0; $day=0; $hour=0; $minute=0; $second=0;
  if(count($dateArr)==2){
    $timePartArr=explode(":", $dateArr[1]);
    if(count($timePartArr)==3){
      $hour=$timePartArr[0];
      $minute=$timePartArr[1];
      $second=$timePartArr[2];
    }
  }

    $datePartArr=explode("/", $dateArr[0]);
    if(count($datePartArr)==3){
      $day=$datePartArr[0];
      $month=$datePartArr[1];
      $year=$datePartArr[2];
    }

//dd($year.' '.$month.' '.$day.' '.$hour.' '.$minute.' '.$second);

  $ethipicDate=\Andegna\DateTimeFactory::of($year, $month, $day, $hour, $minute, $second);
  return $ethipicDate;
 }
 public static function idCardExpiredToffegas(){
    $toffegnas=toffegna::where('isDeleted', 'false')->get();
    $currentDate=(new \App\Date_class)->getCurrentDate();
    $idCardExpriredToffegnaIds=array();
        foreach ($toffegnas as $toffegna) {
            if($toffegna->idCardExpired()){
                //deactivate from system
                $toffegna->status="inactive";
                $toffegna->save();
                array_push($idCardExpriredToffegnaIds, $toffegna->id); 
            }
        }

        $toffegnas=toffegna::whereIn('id', $idCardExpriredToffegnaIds)->where('isDeleted', 'false')->orderBy("id", "desc")->get();

    return $toffegnas;
  }
   public static function driverLicenseExpiredToffegas(){
    $toffegnas=toffegna::where('isDeleted', 'false')->get();
    $currentDate=(new \App\Date_class)->getCurrentDate();
    $driverLicenseExpiredToffegasIds=array();
        foreach ($toffegnas as $toffegna) {
            if($toffegna->driverLicenseExpired()){
              //deactivate from system
                $toffegna->status="inactive";
                $toffegna->save();
                array_push($driverLicenseExpiredToffegasIds, $toffegna->id); 
            }
        }

        $toffegnas=toffegna::whereIn('id', $driverLicenseExpiredToffegasIds)->where('isDeleted', 'false')->orderBy("id", "desc")->get();

    return $toffegnas;
  }

 public static function isExpired($date){
    $CI =& get_instance();
    $CI->load->model('model_date');
    $currentDate=$CI->model_date->getCurrentDate();
    $currentDate=explode(" ", $currentDate)[0];

    $CI->load->model('model_global_var');
    return $CI->model_global_var->isFirstDateEarlier($date, $currentDate);

    /*$currentDate=(new \App\Date_class)->getCurrentDate();
    $currentDate=explode(" ", $currentDate)[0];
    return \App\Model_global_var::isFirstDateEarlier($date, $currentDate);*/
 }

 public static function oilNeededVehicles(){
    $vehicles=vehicle::where('isDeleted', 'false')->get();
    $currentDate=(new \App\Date_class)->getCurrentDate();
    $oilNeededVehicleIds=array();
        foreach ($vehicles as $vehicle) {
            if($vehicle->oilNeeded()){
                array_push($oilNeededVehicleIds, $vehicle->id); 
            }
        }

        $vehicles=vehicle::whereIn('id', $oilNeededVehicleIds)->where('isDeleted', 'false')->orderBy("id", "desc")->get();

    return $vehicles;
  }
 public static function serviceNeededVehicles(){
    $vehicles=vehicle::where('isDeleted', 'false')->get();
    $currentDate=(new \App\Date_class)->getCurrentDate();
    $serviceNeededVehicleIds=array();
        foreach ($vehicles as $vehicle) {
            if($vehicle->serviceNeeded()){
                array_push($serviceNeededVehicleIds, $vehicle->id); 
            }
        }

        $vehicles=vehicle::whereIn('id', $serviceNeededVehicleIds)->where('isDeleted', 'false')->orderBy("id", "desc")->get();

    return $vehicles;
  }

 public static function differeceDays_Count($startDate, $endDate){
      if($startDate==null || $endDate==null)
        return null;
      $dateDiff=Date_class::getDateDiffernece($startDate, $endDate);
      $hour=$dateDiff->i>=30? ($dateDiff->h+1): $dateDiff->h;
      if($dateDiff->days==0)
        return Model_global_var::round($hour/24, 2);
      return $dateDiff->days+1;
    }

public function getNext_CustomerID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$customer_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $last_customer=customer::orderBy("id", "desc")->first();
  $lastNumber=$last_customer!=null? $last_customer->toffID: $resetedCounter;
  /*$leftHalf=substr($lastNumber, 0, strpos($lastNumber, '-')+1);//to include z '-'

  if(!strpos($leftHalf, ''.$currentYear)){
    //reset new year counter
    $lastNumber=$resetedCounter;
    $leftHalf=substr($lastNumber, 0, strpos($lastNumber, '-')+1);//to include z '-'
  }

  $rightHalf=substr($lastNumber, strpos($lastNumber, '-')+1);
  $rightHalf+=1;

  $rightHalfLength=strlen($rightHalf);
    while(strlen($rightHalf)<7){
      $rightHalf="0".$rightHalf;
    }
return $leftHalf.''.$rightHalf;*/
return $this->generateNextID($lastNumber, $resetedCounter);
}

public function getNext_VendorID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$vendor_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $last_vendor=vendors_merchant::orderBy("id", "desc")->first();
  $lastNumber=$last_vendor!=null? $last_vendor->toffID: $resetedCounter;

return $this->generateNextID($lastNumber, $resetedCounter);
}
public function getNext_ToffegnaID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$toffegna_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $last_toffegna=toffegna::orderBy("id", "desc")->first();
  $lastNumber=$last_toffegna!=null? $last_toffegna->toffID: $resetedCounter;

return $this->generateNextID($lastNumber, $resetedCounter);
}
public function getNext_EmployeeID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$toffegna_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $employee_prefix=employee::orderBy("id", "desc")->first();
  $lastNumber=$employee_prefix!=null? $employee_prefix->toffID: $resetedCounter;

return $this->generateNextID($lastNumber, $resetedCounter);
}
public function getNext_MeadiID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$meadi_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $meadi_prefix=toff_meadi::orderBy("id", "desc")->first();
  $lastNumber=$meadi_prefix!=null? $meadi_prefix->toffID: $resetedCounter;

return $this->generateNextID($lastNumber, $resetedCounter);
}

public function getNext_MessageID(){
  $currentYear=Date_class::getCurrentYear();
  $resetedCounter=Model_global_var::$message_prefix.'/'.$currentYear.'-0';
    //toffId is string and can't be used as number
  $message_prefix=toff_message::orderBy("id", "desc")->first();
  $lastNumber=$message_prefix!=null? $message_prefix->toffID: $resetedCounter;

return $this->generateNextID($lastNumber, $resetedCounter);
}


public function generateNextID($lastNumber, $resetedCounter){
$currentYear=Date_class::getCurrentYear();
$leftHalf=substr($lastNumber, 0, strpos($lastNumber, '-')+1);//to include z '-'

if(!strpos($leftHalf, ''.$currentYear)){
  //reset new year counter
  $lastNumber=$resetedCounter;
  $leftHalf=substr($lastNumber, 0, strpos($lastNumber, '-')+1);//to include z '-'
}

$rightHalf=substr($lastNumber, strpos($lastNumber, '-')+1);
$rightHalf+=1;

$rightHalfLength=strlen($rightHalf);
  while(strlen($rightHalf)<3){
    $rightHalf="0".$rightHalf;
  }
return $leftHalf.''.$rightHalf;
}


//generates a random password of length minimum 8 
//contains at least one lower case letter, one upper case letter,
// one number and one special character, 
//not including ambiguous characters like iIl|1 0oO 
public static function generatePassword($len = 8) {

    //enforce min length 8
    if($len < 8)
        $len = 8;

    //define character libraries - remove ambiguous characters like iIl|1 0oO
    $sets = array();
    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    $sets[] = '23456789';
    $sets[]  = '!$#%';
//    $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';

    $password = '';
    
    //append a character from each set - gets first 4 characters
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
    }

    //use all characters to fill up to $len
    while(strlen($password) < $len) {
        //get a random set
        $randomSet = $sets[array_rand($sets)];
        
        //add a random char from the random set
        $password .= $randomSet[array_rand(str_split($randomSet))]; 
    }
    
    //shuffle the password string before returning!
    return str_shuffle($password);
}


public static function countViewsAllowedIntervalInHours(){
  return DB::table('settings')->first()!=null? DB::table('settings')->first()->countViewsAllowedIntervalInHours: 24;        
}
public static function currentUser(){
    $currentUser=Auth::guard('web')->user();
    return $currentUser;
}

public static function days(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['ሰኞ'=>Model_global_var::getLangString('Monday', $language_strings), 'ማክሰኞ'=>Model_global_var::getLangString('Tuesday', $language_strings), 'ረቡዕ'=>Model_global_var::getLangString('Wednesday', $language_strings), 'ሓሙስ'=>Model_global_var::getLangString('Tursday', $language_strings), 'ዓርብ'=>Model_global_var::getLangString('Friday', $language_strings), 'ቅዳሜ'=>Model_global_var::getLangString('Saturday', $language_strings), 'እሑድ'=>Model_global_var::getLangString('Sunday', $language_strings)];
}

public static function reportPeriods(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Year'=>Model_global_var::getLangString('Year', $language_strings), 'Quartile'=>Model_global_var::getLangString('Quartile', $language_strings), 'Month'=>Model_global_var::getLangString('Month', $language_strings), 'Week'=>Model_global_var::getLangString('Week', $language_strings), 'Day'=>Model_global_var::getLangString('Day', $language_strings)];
}
public static function languages(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['tig'=>Model_global_var::getLangString('Tigrigna', $language_strings), 'amh'=>Model_global_var::getLangString('Amharic', $language_strings), 'eng'=>Model_global_var::getLangString('English', $language_strings)];
}
public static function roles(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();
    $roles=DB::table('roles')->where("isDeleted", "=", "false")->pluck("roleName", "id")->toArray();
   /*$translated_roles=array();
    foreach ($roles as $role) {
      $translated_roles[$role->roleName]=Model_global_var::getLangString($role->roleName, $language_strings);
    }
*/
  return $roles;//$translated_roles;
}

public static function documentCategories(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Video'=>Model_global_var::getLangString('Video', $language_strings), 'Audio'=>Model_global_var::getLangString('Audio', $language_strings), 'Image'=>Model_global_var::getLangString('Image', $language_strings), 'News_Paper'=>Model_global_var::getLangString('News_Paper', $language_strings), 'Magazine'=>Model_global_var::getLangString('Magazine', $language_strings), 'Book'=>Model_global_var::getLangString('Book', $language_strings), 'Text_Document'=>Model_global_var::getLangString('Text_Document', $language_strings)];
}

public static function documentSub_categories(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Entertainment'=>Model_global_var::getLangString('Entertainment', $language_strings), 'Documentary'=>Model_global_var::getLangString('Documentary', $language_strings), 'Economic'=>Model_global_var::getLangString('Economic', $language_strings), 'Sport'=>Model_global_var::getLangString('Sport', $language_strings), 'War'=>Model_global_var::getLangString('War', $language_strings), 'Other'=>Model_global_var::getLangString('Other', $language_strings)];
}

public static function numbers(){
  $numbers = array();
  for($i=1; $i<=1000; $i++) {
    $numbers[$i]=$i;
  }
  return $numbers;
}

public static function years(){
  $years = array();
  $currentYear=(new Date_class)->getCurrentYear();
  for($i=($currentYear-100); $i<=($currentYear+100); $i++) {
    $years[$i]=$i;
  }
  return $years;
}

public static function isChrome(){
    return Browser::isChrome();
}
public static function getBrowserData(){
  // You can always get the result object from the facade if you wish to operate with it.
BrowserDetect::detect(); // Will resolve and return with the 'browser.result' container.

// Calls are mirrored to the result object for easier use.
$browserVersion=BrowserDetect::browserVersion(); // return '3.6' string.

// Supporting human readable formats.
$browserName=BrowserDetect::browserName(); // return 'Firefox 3.6' string.

// Or can be objective.
$browserFamily=BrowserDetect::browserFamily(); // return 'Firefox' string.
return Browser::browserName();//$browserName;//.' - '.$browserVersion.'- '.$browserFamily;
}
public static function logAction($request, $subject){
        $log=new log;
        $log->userId=Auth::guard('web')->user()!=null? Auth::guard('web')->user()->id:0;
        $log->subject=$subject;
        $log->url=$request->fullUrl();//url()
        $log->method=$request->method();
        $log->ip=$request->ip();
        $log->agent=$request->header('User-Agent');
        $log->created_at=\Carbon\Carbon::now()->toDateTimeString();
        $log->updated_at=\Carbon\Carbon::now()->toDateTimeString();
        //$log->save();
  
  $data=json_encode(['userId'=>$log->userId, 'subject'=>$log->subject, 'url'=>$log->url, 'method'=>$log->method, 'ip'=>$log->ip, 'agent'=>$log->agent, 'created_at'=>$log->created_at, 'updated_at'=>$log->updated_at]);

  // Log::useFiles(storage_path('/logs/app_log.log'));
  // Log::info($data);

    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);

    file_put_contents(storage_path('/logs/app_log.log'), $data.',|', FILE_APPEND);


} 

public static function makeLog($obj){
        $log=new log;
        $log->userId=$obj['userId'];
        $log->user=User::find($log->userId);
        $log->subject=$obj['subject'];
        $log->url=$obj['url'];
        $log->method=$obj['method'];
        $log->ip=$obj['ip'];
        $log->agent=$obj['agent'];
        $log->created_at=$obj['created_at'];
        $log->updated_at=$obj['updated_at'];
  
  return $log;
} 

public static function isWithIn_One_YearInterval($ksi_mezgeb_brki){
  $dateDiff=Date_class::getDateDiffernece(\App\Model_global_var::getReport_One_YearInterval()[0], $ksi_mezgeb_brki->entryDate);
  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

  $dateDiff=Date_class::getDateDiffernece($ksi_mezgeb_brki->entryDate, \App\Model_global_var::getReport_One_YearInterval()[1]);

  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

//dd($dateDiff->days." ".$dateDiff->invert);
  return true;
}
public static function is_Date_WithIn_One_YearInterval($date){
  $dateDiff=Date_class::getDateDiffernece(\App\Model_global_var::getReport_One_YearInterval()[0], $date);
  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

  $dateDiff=Date_class::getDateDiffernece($date, \App\Model_global_var::getReport_One_YearInterval()[1]);

  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

//dd($dateDiff->days." ".$dateDiff->invert);
  return true;
}


public static function isWithIn_DateInterval($date){
  $dateDiff=Date_class::getDateDiffernece(\App\Model_global_var::getReport_DateInterval()[0], $date);
  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

  $dateDiff=Date_class::getDateDiffernece($date, \App\Model_global_var::getReport_DateInterval()[1]);

  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

//dd($dateDiff->days." ".$dateDiff->invert);
  return true;
}

public static function isWithIn_GivenInterval($givenDate, $initialDate, $finalDate){
  $dateDiff=Date_class::getDateDiffernece($initialDate, $givenDate);
  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

  $dateDiff=Date_class::getDateDiffernece($givenDate, $finalDate);

  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

//dd($dateDiff->days." ".$dateDiff->invert);
  return true;
}


public static function getNextMonthDate($date){
  if($date==null)
    return null;
  $dateArr=explode(" ", $date);
  $year=0; $month=0; $day=0; $hour=0; $minute=0; $second=0;
  if(count($dateArr)==2){
    $timePartArr=explode(":", $dateArr[1]);
    if(count($timePartArr)==3){
      $hour=$timePartArr[0];
      $minute=$timePartArr[1];
      $second=$timePartArr[2];
    }
  }

    $datePartArr=explode("/", $dateArr[0]);
    if(count($datePartArr)==3){
      $day=$datePartArr[0];
      $month=$datePartArr[1];
      $year=$datePartArr[2];
    }

//dd($year.' '.$month.' '.$day.' '.$hour.' '.$minute.' '.$second);

  $ethDate=\Andegna\DateTimeFactory::of($year, $month, $day, $hour, $minute, $second);
 $ethDate->add(new \DateInterval('P1M'));

$nextMonthDate=$ethDate->getDay().'/'.$ethDate->getMonth().'/'.$ethDate->getYear().' '.$ethDate->getHour().':'.$ethDate->getMinute().':'.$ethDate->getSecond();
//dd($nextMonthDate);
return $nextMonthDate;
}


public static function isWithInYearsRange($date, $year1, $year2){
    // let's start from today
$now = new \Andegna\DateTime();
$year1D = $now->sub(new \DateInterval('P'.$year1.'Y'));
$year1DDate=$year1D->getDay().'/'.$year1D->getMonth().'/'.$year1D->getYear();
$now2 = new \Andegna\DateTime();
$year2D = $now2->sub(new \DateInterval('P'.($year2+1).'Y'));
$year2DDate=$year2D->getDay().'/'.$year2D->getMonth().'/'.$year2D->getYear();
/*if($year1==19 && $date=='16/10/1969')
  dd($year2DDate.'  '.$date.'  '.$year1DDate.'  ');*/
return \App\Model_global_var::isFirstDateEarlier($year2DDate, $date) && \App\Model_global_var::isFirstDateEarlier($date, $year1DDate);
}


public static function isWithInAYear($date, $yearsNo=0){ 
    // let's start from today
$now = new \Andegna\DateTime();
$now = $now->sub(new \DateInterval('P'.$yearsNo.'Y'));

$currentYear = $now->sub(new \DateInterval('P'.($now->getMonth()-1).'M'))->sub(new \DateInterval('P'.($now->getDay()-1).'D'));
$currentYearDate=$currentYear->getDay().'/'.$currentYear->getMonth().'/'.$currentYear->getYear();
return \App\Model_global_var::isFirstDateEarlier($currentYearDate, $date);
}

public static function isWithInAQuartile($date){ 
    // let's start from today
$now = new \Andegna\DateTime();
$offsetMonth=$now->getMonth() % 3 > 0? ($now->getMonth()<13? $now->getMonth() % 3: 4): 3;
$currentQuartile = $now->sub(new \DateInterval('P'.($offsetMonth-1).'M'))->sub(new \DateInterval('P'.($now->getDay()-($now->getDay()>=2? 2:1)).'D'));
$currentQuartileDate=$currentQuartile->getDay().'/'.$currentQuartile->getMonth().'/'.$currentQuartile->getYear();
return \App\Model_global_var::isFirstDateEarlier($currentQuartileDate, $date);
}

public static function isWithInAMonth($date){
// $gregorian = new \DateTime('this july');
// // just pass it to Andegna\DateTime constractor and you will get $ethiopian date
// $ethipic = new \Andegna\DateTime($gregorian);
// dd($ethipic->getDay().'/'.$ethipic->getMonth().'/'.$ethipic->getYear());  
    // let's start from today
$now = new \Andegna\DateTime();
$currentMonth = $now->sub(new \DateInterval('P'.($now->getDay()-1).'D'));
$currentMonthDate=$currentMonth->getDay().'/'.$currentMonth->getMonth().'/'.$currentMonth->getYear();
return \App\Model_global_var::isFirstDateEarlier($currentMonthDate, $date);
}

public static function isWithInAWeek($date){
$gregorian = new \DateTime('last sunday');
$gregorian = $gregorian->add(new \DateInterval('P1D'));;
// just pass it to Andegna\DateTime constractor and you will get $ethiopian date
$ethipic = new \Andegna\DateTime($gregorian);
    // let's start from today
$currentWeekDate=($ethipic->getDay()+1).'/'.$ethipic->getMonth().'/'.$ethipic->getYear();
//echo $date;
//dd($currentWeekDate);
//dd($currentWeekDate);
return \App\Model_global_var::isFirstDateEarlier($currentWeekDate, $date) || \App\Model_global_var::areDatesEqual($currentWeekDate, $date);
}

public static function isWithInADay($date){
    // let's start from today
$now = new \Andegna\DateTime();
$toDay = $now;//->sub(new \DateInterval('P1D'));
$toDayDate=$toDay->getDay().'/'.$toDay->getMonth().'/'.$toDay->getYear();
return \App\Model_global_var::isFirstDateEarlier($toDayDate, $date);
}

public static function isBeforeAYear($date){
    // let's start from today
$now = new \Andegna\DateTime();
$prevYear = $now->sub(new \DateInterval('P1Y'));
$prevYearDate=$prevYear->getDay().'/'.$prevYear->getMonth().'/'.$prevYear->getYear();
return \App\Model_global_var::isFirstDateEarlier($date, $prevYearDate);
}
public static function isBeforeAQuartile($date){
    // let's start from today
$now = new \Andegna\DateTime();
$prevQuartile = $now->sub(new \DateInterval('P3M'));
$prevQuartileDate=$prevQuartile->getDay().'/'.$prevQuartile->getMonth().'/'.$prevQuartile->getYear();

return \App\Model_global_var::isFirstDateEarlier($date, $prevQuartileDate);
}
public static function isBeforeAMonth($date){
    // let's start from today
$now = new \Andegna\DateTime();
$prevMonth = $now->sub(new \DateInterval('P1M'));
$prevMonthDate=$prevMonth->getDay().'/'.$prevMonth->getMonth().'/'.$prevMonth->getYear();

return \App\Model_global_var::isFirstDateEarlier($date, $prevMonthDate);
}
public static function isBeforeAWeek($date){
    // let's start from today
$now = new \Andegna\DateTime();
$prevWeek = $now->sub(new \DateInterval('P1W'));
$prevWeekDate=$prevWeek->getDay().'/'.$prevWeek->getMonth().'/'.$prevWeek->getYear();

return \App\Model_global_var::isFirstDateEarlier($date, $prevWeekDate);
}
public static function isBeforeADay($date){
    // let's start from today
$now = new \Andegna\DateTime();
$prevDay = $now->sub(new \DateInterval('P1D'));
$prevDayDate=$prevDay->getDay().'/'.$prevDay->getMonth().'/'.$prevDay->getYear();

return \App\Model_global_var::isFirstDateEarlier($date, $prevDayDate);
}


public static function toGregorian($ethDate){
  $ethipic = \App\Model_global_var::getEthipicDateObject($ethDate);
  $gregorianDateObj=$ethipic->toGregorian();
  return $gregorianDateObj;
}

public static function deliveryDateTimeActivated($deliveryDate, $offsetHour){
  $now = new \Andegna\DateTime();

  $dateObj = \App\Model_global_var::getEthipicDateObject($deliveryDate);
  $subtructed_deliveryDateObj=$dateObj->modify('-'.$offsetHour.' hours');
$currentDate=(new Date_class)->getCurrentDate();

  $deliveryDateObj = \App\Model_global_var::getEthipicDateObject($deliveryDate);
  return ($now->getTimestamp() >= $subtructed_deliveryDateObj->getTimestamp() && $now->getTimestamp() <= $deliveryDateObj->getTimestamp()) || $now->getTimestamp() >= $deliveryDateObj->getTimestamp();
}

public static function isFirstDateEarlier($initialDate, $finalDate){
    $CI =& get_instance();
    $CI->load->model('model_date');
    $dateDiff=$CI->model_date->getDateDiffernece($initialDate, $finalDate);
  //$dateDiff=Date_class::getDateDiffernece($initialDate, $finalDate);

  if($dateDiff->days>0 && $dateDiff->invert==0)//inverted=0 means negative diff
      return false;

//dd($dateDiff->days." ".$dateDiff->invert);
  return true;
}


public static function areDatesEqual($date1, $date2){
  $date1=Date_class::getDate_Hour_Minute($date1)[0];
  $date2=Date_class::getDate_Hour_Minute($date2)[0];
  $date1Arr=explode('/', $date1);
  $date2=$date2.' 0:0:0';
  $date2=Date_class::getDate_Hour_Minute($date2)[0];
  $date2Arr=explode('/', $date2);

  $date1Arr[0]=$date1Arr[0]<10? '0'.$date1Arr[0]: $date1Arr[0];
  $date1Arr[1]=$date1Arr[1]<10? '0'.$date1Arr[1]: $date1Arr[1];
  $date1Arr[2]=$date1Arr[2]<10? '0'.$date1Arr[2]: $date1Arr[2];

$date1=intval($date1Arr[0]).'/'.intval($date1Arr[1]).'/'.intval($date1Arr[2]);
$date2=intval($date2Arr[0]).'/'.intval($date2Arr[1]).'/'.intval($date2Arr[2]);
  /*$date2Arr[0]=$date2Arr[0]<10? '0'.$date2Arr[0]: $date2Arr[0];
  $date2Arr[1]=$date2Arr[1]<10? '0'.$date2Arr[1]: $date2Arr[1];
  $date2Arr[2]=$date2Arr[2]<10? '0'.$date2Arr[2]: $date2Arr[2];*/
//dd($date1Arr[0].'/'.$date1Arr[1].'/'.$date1Arr[2].' == '.$date2Arr[0].'/'.$date2Arr[1].'/'.$date2Arr[2]);
return $date1 == $date2;
}

public static function getReport_DateInterval(){
  $currentYear=Date_class::getCurrentYear();
  $reportDate_Start=\Session::get('startDate')!=null? \Session::get('startDate'): Model_global_var::$newYear_dayMonth.'/'.$currentYear;
  $reportDate_End=\Session::get('endDate')!=null? \Session::get('endDate'): Model_global_var::$newYear_dayMonth.'/'.($currentYear+1);

  return [$reportDate_Start, $reportDate_End];
}
public static function getReport_One_YearInterval(){
  $currentYear=Date_class::getCurrentYear();
  $reportDate_Start=Model_global_var::$newYear_dayMonth.'/'.$currentYear;
  $reportDate_End=Model_global_var::$newYear_dayMonth.'/'.($currentYear+1);

  return [$reportDate_Start, $reportDate_End];
}

public static function generateRank_category($array, $categoryIndex){
    $collection=new Collection($array/*[
    ['name'=>'sue', 'age'=>23],
    ['name'=>'simon', 'age'=>38],
    ['name'=>'jane', 'age'=>25],
    ['name'=>'dave', 'age'=>59],
    ]*/);

$sorted_collection=$collection->sortByDesc('percent_'.$categoryIndex);
//dd($categoryIndex);
//dd($sorted_collection);
/*print_r($sorted_collection);
echo "<br><br>";
  return;*/
  $new_rank_array=array();

   $RANK = 1;
    $rank_offset = 0;

    $arrayIndex=0;
    if(!isset($sorted_collection[$arrayIndex]['percent_'.$categoryIndex]))
      $arrayIndex=1;
     
    $current_maximam = $sorted_collection/*->first()*/[$arrayIndex]['percent_'.$categoryIndex];
    //echo '<br>'.$categoryIndex.' hh '.($current_maximam);
    foreach($sorted_collection as $collection)
    {
      if(!isset($collection['brki_'.$categoryIndex]) || !isset($collection['percent_'.$categoryIndex]))
        continue;

        $key=$collection['brki_'.$categoryIndex];
        $value=$collection['percent_'.$categoryIndex];
//dd($key);
//dd($value);
        if ($value < $current_maximam)
        {
            $RANK = $RANK + $rank_offset;
            $rank_offset = 1;
            $current_maximam = $value;
        }
        else if($value == $current_maximam)
        {
            $rank_offset ++;
        }

    $new_rank_array[$key]=$RANK;
}
return $new_rank_array;
}

public static function generateRank($array){
  
  $collection=new Collection($array/*[
    ['name'=>'sue', 'age'=>23],
    ['name'=>'simon', 'age'=>38],
    ['name'=>'jane', 'age'=>25],
    ['name'=>'dave', 'age'=>59],
    ]*/);

$sorted_collection=$collection->sortByDesc('percent');
/*print_r($sorted_collection);
echo "<br><br>";
  return;*/
  $new_rank_array=array();

   $RANK = 1;
    $rank_offset = 0;
    $current_maximam = $sorted_collection->first()['percent'];
    foreach($sorted_collection as $collection)
    {
        $key=$collection['brki'];
        $value=$collection['percent'];

        if ($value < $current_maximam)
        {
            $RANK = $RANK + $rank_offset;
            $rank_offset = 1;
            $current_maximam = $value;
        }
        else if($value == $current_maximam)
        {
            $rank_offset ++;
        }

    $new_rank_array[$key]=$RANK;
}
//dd($new_rank_array);
return $new_rank_array;
}

public static function round($number, $decimalPoint){
  return round($number*pow(10, $decimalPoint))/pow(10, $decimalPoint);
}

public static function paymentTypes(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Cash'=>Model_global_var::getLangString('Cash', $language_strings), 'Deposit'=>Model_global_var::getLangString('Deposit', $language_strings)];
}
public static function propertyOwnerTypes(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Toffegna'=>Model_global_var::getLangString('Toffegna', $language_strings), 'Employee'=>Model_global_var::getLangString('Employee', $language_strings), 'Employee'=>Model_global_var::getLangString('Employee', $language_strings), 'Other'=>Model_global_var::getLangString('Other', $language_strings)];
}
public static function getGenders(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Male'=>Model_global_var::getLangString('Male', $language_strings), 'Female'=>Model_global_var::getLangString('Female', $language_strings)];
}
public static function customers($customer){
    $customerId=$customer!=null? $customer->id: 0;
    $customers=customer::where('isDeleted', 'false')->where('id', '!=', $customerId)->get();
    $customersArr=array();
    foreach ($customers as $customer) {
     $customersArr[$customer->id]=$customer->firstName.' '.$customer->lastName.' '.$customer->middleName.' | '.$customer->toffID.' | '.$customer->phoneNumber_1;
    }
  return $customersArr;
}
public static function toffegnas($typeOfTruckId){
  if($typeOfTruckId!=null)
      $toffegnas=toffegna::where('isDeleted', 'false')->where('status', 'active')->where('typeOfTruckId', $typeOfTruckId)->get();
  else
    $toffegnas=toffegna::where('isDeleted', 'false')->where('status', 'active')->get();
  
    $toffegnasArr=array();
    foreach ($toffegnas as $toffegna) {
     $toffegnasArr[$toffegna->id]=$toffegna->firstName.' '.$toffegna->lastName.' '.$toffegna->middleName.' | '.$toffegna->toffID.' | '.$toffegna->phoneNumber_1;
    }
  return $toffegnasArr;
}
public static function employees(){
    $employees=employee::where('isDeleted', 'false')->get();
  
    $employeesArr=array();
    foreach ($employees as $employee) {
     $employeesArr[$employee->id]=$employee->firstName.' '.$employee->lastName.' '.$employee->middleName.' | '.$employee->toffID.' | '.$employee->phoneNumber_1;
    }
  return $employeesArr;
}
public static function vendors($vendorTypeId){
  if($vendorTypeId!=null)
      $vendors_merchants=vendors_merchant::where('isDeleted', 'false')->where('vendorTypeId', $vendorTypeId)->get();
  else
    $vendors_merchants=vendors_merchant::where('isDeleted', 'false')->get();
  
    $vendors_merchantsArr=array();
    foreach ($vendors_merchants as $vendors_merchant) {
     $vendors_merchantsArr[$vendors_merchant->id]=$vendors_merchant->companyName.' | '.$vendors_merchant->toffID.' | '.$vendors_merchant->phoneNumber_1;
    }
  return $vendors_merchantsArr;
}
public static function items($vendorId){
  if($vendorId!=null)
      $vendor_items=vendor_item::where('isDeleted', 'false')->where('vendorId', $vendorId)->get();
  else
    $vendor_items=vendor_item::where('isDeleted', 'false')->get();
  
    $vendor_itemsArr=array();
    foreach ($vendor_items as $vendor_item) {
     $vendor_itemsArr[$vendor_item->id]=$vendor_item->name.' | '.(($vendor=$vendor_item->vendor)!=null? $vendor->companyName: 'Unknown Vendor');
    }
  return $vendor_itemsArr;
}


public static function registerdByTheNameOf_types(){
    $language_strings=\App\language_string::orderBy('id', 'desc')->get();

  return ['Toffegna'=>Model_global_var::getLangString('Toffegna', $language_strings), 'Employee'=>Model_global_var::getLangString('Employee', $language_strings), 'Other'=>Model_global_var::getLangString('Other', $language_strings)];
}
    
public static function getLangString($keyWord, $language_strings){
$selectedLang=Session::get('selectedLang');
$language_string=$language_strings->where('keyWord', '=', $keyWord)->first();
    return $language_strings!=null && $selectedLang!='' && $language_string!=null && $language_string->$selectedLang!=null? $language_string->$selectedLang: str_replace('_', ' ', $keyWord); //split with _
}

 

 public static function existsInArray($array, $item){
  
    if($array==null)
      return false;
    foreach ($array as $elem) {
     if($elem==$item)
        return true;
    }
    return false;
 }
 
public static function checkDir($dirPath) {
        if(!File::exists($dirPath))
          File::makeDirectory($dirPath);    

      return true;    
     }
public static function base64ToImage($request) {
        $png_url = "profile-".time().".jpg";
        $dirPath='images/img_dir';
        if(!File::exists($dirPath))
          File::makeDirectory($dirPath);
        $path = $dirPath.'/' . $png_url;
        $image=\Image::make(file_get_contents($request->image))->save($path); 
        
        return true;
     }
public static function base64ToImageWithPath($request, $dirPath) {
        $png_url = "profile-".time().".jpg";
        if(!File::exists($dirPath))
          File::makeDirectory($dirPath);
        $path = $dirPath.'/' . $png_url;
        $image=\Image::make(file_get_contents($request->base64image))->save($path); 
        
        return $path;
     }

}
