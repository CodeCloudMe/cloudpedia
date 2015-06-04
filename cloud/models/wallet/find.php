<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	
	header('Content-Type: text/html;charset=utf-8');
	extract($_REQUEST);
	session_start();

	if(!isset($url)){
		$url = 'https://gosnapshop.com/products/steve-madden-women-s-troopa-boot-b003wusut8-brown-leather';
	}


	$resp = getUrlInfo($url);

	echo(returnJSON($resp));


	function getUrlInfo($url){


		//$html = file_get_contents($url);
		$ch =  curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$html = curl_exec($ch);

		$biggestImg = "";
		//$biggestImg = get_biggest_img($url);
		
		$url = str_replace("https", "http", $url );

		$parse = parse_url($url);
		$theHost =domain($url);
		$title = pageTitle($html, $theHost);
		$dom = new domDocument; 
 
// load the html into the object
		//$dom->loadHTML($html); 

		//$screen  = file_get_contents('http://api.page2images.com/restfullink?p2i_url='.$url.'&p2i_key=850330c207908e20');

		//$screen1 = json_decode($screen, true);
		//$screen2 = $screen1['image_url'];
		$screen2 = 'http://images.shrinktheweb.com/xino.php?stwembed=1&stwaccesskeyid=a96215c5543ac81&stwsize=sm&stwurl='.$url;

		if(!isset($screen2)){
			$screen  = file_get_contents('http://api.page2images.com/restfullink?p2i_url='.$url.'&p2i_key=850330c207908e20');

			$screen1 = json_decode($screen, true);
			$screen2 = $screen1['image_url'];

		}

		$image = $screen2;

		//echo($html);

		$currencyKnown= true;

		preg_match('/(â�©|₩|\$|€|¥)\d+.*?</', $html, $match);

		if(!isset($match[0])){
			$currencyKnown=true;

			preg_match('/\d+.*?\d원/', $html, $match);
		}


		if(!isset($match[0])){
			$currencyKnown=false;

			preg_match('/>\d+.*?\d</', $html, $match);
		}



		
		//print_r($match);

		$price = $match[0];

		$cur="$";

		if(strpos($price, "$")!==false){
			$cur = "$";
		
		}
		
		if(strpos($price, "원")!==false){
			
			$cur = "₩";
			
		}
		if(strpos($price, "₩")!==false){
			$cur = "₩";
		}
		if(strpos($price, "€")!==false){
			$cur = "€";
		}



		$price = str_replace("<", "", $price);
		$price = str_replace(">", "", $price);

/*
		$titles = $dom->getElementsByTagName('title');
		//$title = $titles->item(0);

		foreach ($titles as $ti) 
			{ 
			    $title = DOMinnerHTML($ti); 
			} 

		$match =array();
*/
/*
		preg_match('/\$([0-9]+[\.]*[0-9]*)/', $html, $match);

		*/
		/*
		if(!isset($match([1]))){
			
			preg_match('/₩([0-9]+[\.]*[0-9]*)/', $str, $match);
			$price = $match[1];
		}

		else{

			$price = $match[1];
		}
		*/
	//	$price="$1";


		


		return(array("status"=>"success", "title"=>$title, "image"=>$image, "price"=>$price, "currencyKnown"=>$currencyKnown, "currency"=>$cur));


		//
	}


	function pageTitle($content, $page_url)
{
     $read_page=$content;
     preg_match("/<title.*?>[\n\r\s]*(.*)[\n\r\s]*<\/title>/", $read_page, $page_title);
      if (isset($page_title[1]))
      {
            if ($page_title[1] == '')
            {
                  return $page_url;
            }
            $page_title = $page_title[1];
            return trim($page_title);
      }
      else
      {
            return $page_url;
      }
}

	function get_biggest_img($url){

require_once('simple_html_dom.php'); // PHP Simple HTML DOM Parser.
require_once('url_to_absolute.php'); // get image absolute url.
// options
$biggestImage = 'path to "no image found" image'; // Is returned when no images are found.
// process
$maxSize = -1;
$visited = array();
$html = file_get_html($url);
// loop images
foreach($html->find('img') as $element) {
    $src = $element->src;
    if($src=='')continue;// it happens on your test url
    $imageurl = url_to_absolute($url, $src);//get image absolute url
    // ignore already seen images, add new images
    if(in_array($imageurl, $visited))continue;
    $visited[]=$imageurl;
    // get image
    $image=@getimagesize($imageurl);// get the rest images width and height
    if (($image[0] * $image[1]) > $maxSize) {   
        $maxSize = $image[0] * $image[1];  //compare sizes
        $biggest_img = $imageurl;
    }
}
return $biggest_img; //return the biggest found image
}



function domain($url)
{
    global $subtlds;
    $slds = "";
    $url = strtolower($url);

   $host = parse_url('http://'.$url,PHP_URL_HOST);

    preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
    foreach($subtlds as $sub){
        if (preg_match('/\.'.preg_quote($sub).'$/', $host, $xyz)){
            preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        }
    }

    return @$matches[0];
}


	


?>