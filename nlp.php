<?php

$perfectkw = array('non-government','non government','nongovernment','nonprofit','orphanage',
    'big international ngo','business organized ngo','community based','civil society','environmental ngo',
    'government organized ngo','grassroots','global social change',
    'social work','social help','charity','charitable organization');

$helperkw = array('NPO','NGO','BINGO','BONGO','CBO','CSO','ENGO','GONGO','MONGO','IPO','GRO','GSCO','NPO','organization',
    'society','needy','equality','help','awareness','under-privilege','education','poor','poverty','slum','support',
    'farmer','literacy','donation','donate','malnutrition','indigenous','adopt','care','campaign','community','counsel',
	'crisis','depression','develop','disabled','disability','disorder','life','foster','fund','group','hospice','clinic',
	'mental','military','problem','resolution','resolve','harm','share','sharing','treatment','abuse','movement','freedom','right',
	'rural','women','uplift','encourage','medical camp','free','girl','health','child','charitable');

/*
$text = 'Freedom Again Foundation is a organization working towards the upliftment of society while advocating financial freedom. In our everyday life, we come across lots of sectors of the society where people are needy, deprived of or unable to meet their basic necessities. It makes us wonder what could be done to give back to the society and to remove financial inequality from our daily lives. Thus, Freedom Again Foundation was born out of a small idea or gesture of wanting to help the needy in every way possible. At Freedom Again Foundation, our primary goal is to identify the reasons for financial inequality from a grassroots level and work towards creating awareness and resolving it. Our secondary goal is to work towards helping the needy and under-privileged to the best of our abilities and with help from the society. Our Vision:
"To drive the importance of education in young adults and its correlation to financial freedom through moral, cultural and intellectual stimulation towards building a sustainable livelihood."';

nlp($text);
*/

//nlp("non government-organization");

function nlp($text)
{
    $text = preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    $text = str_replace('"', '', $text);
    $text = "*".$text;
    //echo "text = ".$text;
    global $perfectkw, $helperkw;
    $words = mb_split(' +', $text);
    for ($i = 0; $i < sizeof($words); $i++) {
        if (strlen($words[$i]) < 3)
            unset($words[$i]);
    }
    $uwords = array_unique($words);
    $uwtext = implode(" ", $uwords);
    //var_dump($uwtext);
    $flag = 0;
    $skw = "";
    foreach ($perfectkw as $pkw) {
        //echo gettype($pos)."jfhvbd";
        //echo stripos($uwtext, $pkw)==0;
        if (stripos($uwtext, $pkw)) {
            $flag = 1;
            $skw = $pkw;
            break;
        }
    }

//$sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $text);
//var_dump($sentences);

    $fkw = array('*');
    if ($flag == 0) {
        $f = 0;
        foreach ($helperkw as $hkw) {
            if (stripos($uwtext, $hkw)) {
                if (!array_search($hkw, $fkw)) {
                    array_push($fkw, $hkw);
                    $f++;
                }
            }
            if ($f > 3) {
                $flag = 1;
                break;
            }
        }
    }

    if($flag==1) {
        //echo "got nlp";
        return true;
    }
    return false;
}



/*
$str = "Our secondary goal is to work towards helping the needy and under-privileged to the best of our abilities and with help from the society";

similar_text($str,"help needy",$p);
echo "<br>".$p;
*/
?>