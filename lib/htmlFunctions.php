<?php

/**
 * HTML STRUCTURES
 */

/**
 * Second Page HTML Structure Return
 * @return string
 */

function returnString () {
    $html2 = '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width"/>
        <link type="text/css" rel="stylesheet" href="lib/main.css">
        <link type="text/css" rel="stylesheet" href="lib/bootstrap.min.css">
    </head>        
    <body>
        <div class="container-fluid">
            <div class="row">   
                <div class="col-xs-8" style="width: 69.99%;!important;">
                    <div class="infoLeftSection">
                        <div class="mainDetails" >
                            <div class="Objective" >
                            </div>
                            <div class="Experience">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 " >
                    <div class="infoRightSection">
                        <div class="extraDetails">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>';
    return $html2;
}


/**
 * First Page HTML SKELETON
 * @return String
 */

function fPageHtml(){
    $html1 = '
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width"/>
        <link type="text/css" rel="stylesheet" href="lib/main.css">
        <link type="text/css" rel="stylesheet" href="lib/bootstrap.min.css">
    </head>        
    <body>
        <div class="cvName container-fluid text-center">
            <div class="mainHeading">
            </div>
            <div class="mainSubHeading">
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">   
                <div class="col-xs-8" style="width: 69.99%;!important;">
                    <div class="infoLeftSection">
                        <div class="mainDetails" >
                            <div class="Objective" >
                            </div>
                            <div class="Experience">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-3 " >
                    <div class="infoRightSection">
                        <div class="extraDetails">
                            <div class="email">
                            </div>
                            <div class="phone">
                            </div>
                            <div class="address">
                            </div>
                            <div class="skills">
                            </div>
                            <div class="language">
                            </div>
                            <div class="education">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>';

    return $html1;
}



/**
 * COLUMN 8 TAGS
 */

/**
 * @param $data
 * @return string
 */
function getNameTag($data){
    $name_tag = '
    <div class="mainHeading">
        <h1 id="yay">' . $data . ' </h1>
    </div>';

    return $name_tag;
}

/**
 * @param $data
 * @return string
 */
function getSubNameTag($data){
    $subName_tag = '
    <div class="mainSubHeading">
        <h4 id="yay">' . $data . ' </h4>
    </div>';

    return $subName_tag;
}

/**
 * @param $data
 * @return string
 */
function getObjectiveTag($data){
    $objective_tag = '
    <div class="Objective" >
        <p style="font-weight: bold;font-size: 18px;letter-spacing: -0.5px;">RESUME OBJECTIVES:</p>
        <p class ="final" style="font-size: 13.3px; color: black;font-weight: lighter">' . $data . '</p>
    </div>';

    return $objective_tag;
}

/**
 * @param $data
 * @return string
 */
function getExperienceTag($data)
{
    $experience_tag = '
    <div class="Experience">
        <p id="PE" style="font-weight: bold;font-style: italic;font-size: 16px;">EXPERIENCE:</p>
        <p class ="final" style="font-size: 13.3px; color: black;font-weight: lighter">' . $data . '</p>
    </div>';

    return $experience_tag;
}



/**
 * COLUMN 3 TAGS
 */

/**
 * @param $data
 * @return string
 */
function getEducationTag($data){
    $education_tag = '
    <div class="education">
        <p style="font-weight: bold;font-style: italic;font-size: 16px;">EDUCATION:</p>
        <p class ="final" style="font-size: 12px; color: black;font-weight: lighter">' . $data . '</p>
    </div>';

    return $education_tag;
}


/**
 * @param $data
 * @return string
 */
function getEmailTag($data){
    $email_tag = '
    <div class="email" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/circle.png" width="21px" height="21px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">   
                ' . $data . '
                </p>
            </div>
        </div>
    </div>';

    return $email_tag;
}


/**
 * @param $data
 * @return string
 */
function getPhoneTag($data){
    $phone_tag = '
    <div class="phone" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/phone.png" width="19px" height="19px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">
                ' . $data . '
                </p>
            </div>
        </div>
    </div>';

    return $phone_tag;
}

/**
 * @param $data
 * @return string
 */
function getAddressTag($data){
    $address_tag = '
    <div class="address" style="line-height: 85%;">
        <div class="row" style="padding: 0; margin: 0">
            <div class="col-xs-2" style="margin: 0;padding: 0">
                <img src="lib/location.png" width="19px" height="19px">
            </div>
            <div class="col-xs-9" style="padding: 0; margin: 0; width: 50%!important;">
                <p style="font-size: 11px; font-weight: bold; padding-right: 0;margin-top: 4px;">
                ' . $data . '
                </p>
            </div>
        </div>
    </div><br>';

    return $address_tag;
}


