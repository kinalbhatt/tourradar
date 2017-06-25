<?php
class Anagram
{
    public static function isAnagram($string1, $string2)
    {
        if (trim($string1)=="" || trim($string2)==""){
            return false;
        }
        $string1Chars = count_chars(strtolower($string1), 1);
        $string2Chars = count_chars(strtolower($string2), 1);

        foreach ($string1Chars as $char => $occurance) {
            if (chr($char)!=" ") {
                if ($occurance!=$string2Chars[$char]) {
                    return false;
                }
            }
        }
        return true;
    }
}

$string1 = "AstroNomers";
$string2 = "no more stars";

var_dump(Anagram::isAnagram($string1, $string2));