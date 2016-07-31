<?php
namespace company_plugin\component;

class Util_String {

	public static function generateGlueSeperatedString($arr,$field, $glue = ', '){

		$s = '';
		foreach($arr as $a){

			if(is_array($a)){
				if(isset($a[$field])){
					$s .= $a[$field] . $glue;
				}
			}
			else{
				$s .= $a . $glue;

			}
		}
		$s =preg_replace("/$glue$/",'',$s);

		return $s;
	}

	/**
	 * @param $data
	 * @param array $params
	 * 					$defaults = array(
	'convertToLowerCase'=>true,
	'replaceSplCharWith'=>'',
	'replaceSpace'=>true,
	'replaceSpaceCharWith'=>'-',
	'replaceMultipleDash'=>true,
	'allowedCharList'=>array('A-Z','a-z','0-9','-','\s')

	);
	 *
	 * @return mixed
	 */
	static function removeAllSplCharacters($data, $params = array()){

		$defaults = array(
			'convertToLowerCase'=>true,
			'replaceSplCharWith'=>'',
			'replaceSpace'=>true,
			'replaceSpaceCharWith'=>'-',
			'replaceMultipleDash'=>true,
			'allowedCharList'=>array('A-Z','a-z','0-9','-','\s')

		);

		//var_dump($defaults);die('sedw');

		$params = array_merge($defaults,$params);

		//var_dump($params);die('sew');

		if($params['convertToLowerCase'])
			$data = strtolower($data);

		//var_dump($data);die('sew');

		$allowedCharListStr = '';
		foreach($params['allowedCharList'] as $a)
			$allowedCharListStr .= $a;

		//echo $allowedCharListStr;die('sew');

		//$lc = preg_replace('/[^A-Za-z0-9-\s]/', $params['replaceSplCharWith'], $data);

		$lc = preg_replace("/[^$allowedCharListStr]/", $params['replaceSplCharWith'], $data);


		if($params['replaceSpace']){
			$lc = preg_replace('/\s+/', $params['replaceSpaceCharWith'], $lc);
		}

		if($params['replaceMultipleDash'])
			$lc = preg_replace('/-+/', '-', $lc);

		return $lc;
	}

	/*
	 * Calls removeAllSplCharacter. Use that instead.
	 */
	static function clean($data,$params = array()){
		return self::removeAllSplCharacters($data,$params);
	}


    static public function generateSlug($text)
    {


        return self::removeAllSplCharacters($text);

    }



    static function getJsonDecodedData($string){

        $arr = json_decode($string,true);
        if(json_last_error() == JSON_ERROR_NONE){
            return $arr;
        }
        return false;

    }
} 