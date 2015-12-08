<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 12/8/2015 9:58 AM
 */

include("../simple_html_dom.php");
// url get from config
$homepage = 'http://homefinder.vn';
$url = 'http://homefinder.vn/developer/';

// Load html from url
$html = file_get_html($url);

// Find div contain links
$groups = $html->find('.img-project-inside');
if(!empty($groups)){
//    $i=1;
    foreach($groups as $group){
        $link = $homepage . $group->find('a')[0]->href;
        $group_website = file_get_html($link);
//        echo $group_website;
        $listProject = $group_website->find('ul.list_project li a');
        if(!empty($listProject)){
            foreach($listProject as $project){
                $linkProject = $homepage.$project->href;
                if(strpos($linkProject, 'sunrise-city-1')) {
//                $project->href = $linkProject;
//                echo $linkProject.'<br>';
                    $project_website = file_get_html($linkProject);
                    echo $project_website;
                    break;
                }
            }
        }
//        $i++;
        break;
    }

//    $a = $homepage.$groups[0]->find('a')[0]->href;
//    echo $i.' '.$a.'<br>';

}

//// Defining the basic cURL function
//function curl($url) {
//    // Assigning cURL options to an array
//    $options = Array(
//        CURLOPT_HEADER => FALSE,
//        CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
//        CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
//        CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
//        CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
//        CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
//        CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
//        CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
//        CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
//    );
//
//    $ch = curl_init();  // Initialising cURL
//    curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
//    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
//    curl_close($ch);    // Closing cURL
//    return $data;   // Returning the data from the function
//}

//function curl($url) {
//    $ch = curl_init();  // Initialising cURL
//    curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
//    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
//    curl_close($ch);    // Closing cURL
//    return empty($data) == false ? $data : null;   // Returning the data from the function
//}


