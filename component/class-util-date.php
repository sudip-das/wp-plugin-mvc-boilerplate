<?php
namespace pluginname\component;

class Util_Date {

    public static function getToday($showDateWithTime = false){

        if($showDateWithTime){

                return date('Y-m-d H:i:s');


        }
        else{
                return date('Y-m-d');
        }
    }

    public static function substractDaysToDate($dt, $noOfDays)
    {
        $dtTimeStamp = self::_timestamp($dt,'substract');
        $finalDtTimeStamp = $dtTimeStamp - ($noOfDays) * 60 * 60 * 24;
        $finalDt = date('Y-m-d',$finalDtTimeStamp);
        return($finalDt);
    }

    public static function dateDifference($endDt, $startDt)
    {

        $startDtTimeStamp = self::_timestamp($startDt);
        $endDtTimeStamp = self::_timestamp($endDt);

        $leftDays = floor(($endDtTimeStamp - $startDtTimeStamp) /(60*60*24));

        $leftDays = (int)$leftDays;
        return($leftDays);
    }


    public static function dateDifferenceInMinutes($endDt, $startDt)
    {

        $startDtTimeStamp = self::_timestamp($startDt);
        $endDtTimeStamp = self::_timestamp($endDt);

        $leftMinutes = floor(($endDtTimeStamp - $startDtTimeStamp) /60);
        return($leftMinutes);
    }


    public static function dateDifferenceInHourMinSecond($endDtTime,$startDtTime,$return = false,$format = true,$formatSeconds = false){



        $diff=strtotime($endDtTime)-strtotime($startDtTime);


// immediately convert to days
        $temp=$diff/86400; // 60 sec/min*60 min/hr*24 hr/day=86400 sec/day

// days
        $days=floor($temp);
        //echo "days: $days<br/>\n";


        $temp=24*($temp-$days);
// hours
        $hours=floor($temp);
        //echo "hours: $hours<br/>\n";


        $temp=60*($temp-$hours);
// minutes
        $minutes=floor($temp);
        //echo "minutes: $minutes<br/>\n";
        $temp=60*($temp-$minutes);
// seconds
        $seconds=floor($temp);
        //echo "seconds: $seconds<br/>\n<br/>\n";

        //echo "Result: {$days}d {$hours}h {$minutes}m {$seconds}s<br/>\n";
        //echo "Expected: 7d 0h 0m 0s<br/>\n";

        $arr = array(
            'days'=>$days,
            'hours'=>$hours,
            'minutes'=>$minutes,
            'seconds'=>$seconds
        );

        if($format){
            $fStr = "$days days, $hours hours, $minutes minutes";
            if($formatSeconds)
                $fStr .= ", $seconds seconds";
        }


        if($return){

            if($format)
                return $fStr;
            else
                return $arr;
        }
        else{
            echo $fStr;
        }


    }


    public static function addDaysToDate($dt, $noOfDays)
    {

        $finalDt = date('Y-m-d', strtotime($dt. " + $noOfDays days"));


        return($finalDt);
    }

    public static function addHoursToDate($dt, $noOfHours,$returnDtTime = false)
    {

        $new_date = strtotime("+$noOfHours hours", strtotime($dt));

        //var_dump(date('Y-m-d', $new_date));die('sew');

        //var_dump($new_date);die('sew');

        if($returnDtTime)
            $new_date = date('Y-m-d H:i:s', $new_date);
        else
            $new_date = date('Y-m-d', $new_date);

        return $new_date;


    }



    //difference in hours
    public static function getTimeDifferenceInHours($endTime,$startTime) {

        $startTime = strtotime("1/1/1980 $startTime");
        $endTime = strtotime("1/1/1980 $endTime");
        /*
       if ($endTime < $startTime) {

           $endTime = $endTime + 86400;
       }
      */
        //echo "endTime = $endTime, startTime = $startTime";

        return ($endTime - $startTime) / 3600;

    }


    static function getDatesInRange($end,$start, $includeEndDtInRange = false,$includeStartDtInRange = false){

        $modStartDt = self::addDaysToDate($start,1);

        $aDays = array();
        if($includeStartDtInRange){
            // Start the variable off with the start date
            $aDays[] = $start;
        }


        while($modStartDt < $end){

            $aDays[] = $modStartDt;

            $modStartDt = self::addDaysToDate($modStartDt,1);

        }

        if($includeEndDtInRange){
            $aDays[] = $end;
        }

        return $aDays;
    }


    public static function formatDateTimeInDayMonthYearHourMinAmPm($dtOrdtWithTime,$formatTime = false,$echo = true){

        $ts = strtotime($dtOrdtWithTime);

        if($formatTime){

            $f = date("F j, Y, g:i a",$ts);
        }
        else{
            $f = date("F j, Y",$ts);

        }

        if($echo)
            echo $f;
        else
            return $f;



    }

    static function formatMinutesToHoursMins($time, $format = '%d:%d') {
        settype($time, 'integer');
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);

        //var_dump($hours);
        //var_dump($minutes);
        //die('s');

        return sprintf($format, $hours, $minutes);
        //echo convertToHoursMins(250, '%02d hours %02d minutes'); // should output 4 hours 17 minutes

    }



    /*
     * @param  operationType string operationType can be either 'add' or 'substract'
     */
    public static function _timestamp($dtTime, $operationType = 'add')
    {

        $endDtPos = strpos($dtTime,':');
        if($endDtPos === false)
        {
            if($operationType == 'add')
                $dtTime .= ' 24:00:00';
            else //i.e. sustract
                $dtTime .= ' 00:00:00';
            //$endDtArr = explode("-",$dtTime);
            // $endDtTimeStamp = mktime(0,0,0,$endDtArr[1],$endDtArr[2],$endDtArr[0]);

        }

        $tmp = explode(' ', $dtTime);
        $endDtArr = explode("-",$tmp[0]);
        $endTimeArr = explode(":", $tmp[1]);

        $endDtTimeStamp = mktime($endTimeArr[0],$endTimeArr[1],$endTimeArr[2],$endDtArr[1],$endDtArr[2],$endDtArr[0]);


        if(isset($endDtTimeStamp))
            return($endDtTimeStamp);
    }


}