<?php
include_once 'htmlFunctions.php';

/**
 * NAME REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return string
 */
function nameTagReplace($data,$replace)
{
    try {
        if ($data != '') {
            $replace = str_replace('<div class="mainHeading">
            </div>', getNameTag($data), $replace);

            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}


/**
 * SUBNAME REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed|string
 */
function subNameTagReplace($data,$replace)
{
    try {
        if ($data != '') {
            $replace = str_replace('<div class="mainSubHeading">
            </div>', getSubNameTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}


/**
 * OBJECTIVE REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return string
 */
function objectiveTagReplace($data,$replace)
{
    try {
        if ($data != '') {
            $replace = str_replace('<div class="Objective" >
                            </div>', getObjectiveTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}


/**
 * EXPERIENCE REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function experienceTagReplace($data,$replace){
    try {
        if ($data != '') {
            $replace = str_replace('<div class="Experience">
                            </div>', getExperienceTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}

/**
 * EDUCATION REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function educationTagReplace($data,$replace)
{
    try {
        if ($data != '') {
            $replace = str_replace('<div class="education">
                            </div>', getEducationTag($data) . '<b>', $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}

/**
 * EMAIL REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function emailTagReplace($data,$replace){
    try {
        if ($data != "N/A" && $data != "NA") {
            $replace = str_replace('<div class="email">
                            </div>', getEmailTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}

/**
 * PHONE REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function phoneTagReplace($data,$replace){
    try {
        if ($data != "NA" && $data != "N/A") {
            $replace = str_replace('<div class="phone">
                            </div>', getPhoneTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}


/**
 * ADDRESS REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function addressTagReplace($data,$replace)
{
    try {
        if ($data != '') {
            $replace = str_replace('<div class="address">
                            </div>', getAddressTag($data), $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}

/**
 * SKILLS REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function skillTagReplace($data,$replace)
{
    try {
        $skill_data = '';
        if ($data[0] != "NA" &&
            $data[0] != "N/A" ||
            $data[1] != "NA" &&
            $data[1] != "N/A" ||
            $data[2] != "NA" &&
            $data[2] != "N/A") {
            $skill_data .= '
        <div class="skills" style="line-height: 135%;">
            <p style="font-weight: bold;letter-spacing: -0.9px;">SKILLS</p>';
            if ($data[0] != "NA" &&
                $data[0] != "N/A") {
                $skill_data .= '<p id="side">' . $data[0] . '</p>';
            }
            if ($data[1] != "NA" &&
                $data[1] != "N/A") {
                $skill_data .= '<p id="side">' . $data[1] . '</p>';
            }
            if ($data[2] != "NA" &&
                $data[2] != "N/A") {
                $skill_data .= '<p id="side">' . $data[2] . '</p>';
            }
            $skill_data .= '</div><br>';
            $replace = str_replace('<div class="skills">
                            </div>', $skill_data, $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}


/**
 * LANGUAGE REPLACEMENT
 *
 * @param $data
 * @param $replace
 * @return mixed
 */
function languageTagReplace($data,$replace)
{
    try {
        $lan_data = '';
        if ($data[0] != "NA" &&
            $data[0] != "N/A" ||
            $data[1] != "NA" &&
            $data[1] != "N/A" ||
            $data[2] != "NA" &&
            $data[2] != "N/A") {
            $lan_data .= '
        <div class="languages" style="line-height: 135%" >
            <p style="font-weight: bold; letter-spacing: -0.9px;">LANGUAGES</p>';
            if ($data[0] != "NA" &&
                $data[0] != "N/A") {
                $lan_data .= '<p id="side">' . $data[0] . '</p>';
            }
            if ($data[1] != "NA" &&
                $data[1] != "N/A") {
                $lan_data .= '<p id="side">' . $data[1] . '</p>';
            }
            if ($data[2] != "NA" &&
                $data[2] != "N/A") {
                $lan_data .= '<p id="side">' . $data[2] . '</p>';
            }
            $lan_data .= '</div>';
            $replace = str_replace('<div class="language">
                            </div>', $lan_data . '<br>', $replace);
            return $replace;
        } else {
            return $replace;
        }
    } catch (Throwable $e) {
    }
}