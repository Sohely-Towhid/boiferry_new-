<?php
namespace App;

class Phonetic
{
    /**
     * Tokenize Unicode strings
     *
     * @param  string   $str
     * @link http://php.net/str_split
     *
     * @return array
     */
    public function str_split_unicode($str)
    {
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Convert any Bangla sentance to phonetic english
     *
     * @param  string  $string the Bangla string
     *
     * @return string  translated string
     */
    public function str_bn_to_en($string)
    {
        static $mapping = array(
            'ক' => 'k',
            'খ' => 'kh',
            'গ' => 'g',
            'ঘ' => 'gh',
            'ঙ' => 'ng',
            'চ' => 'c',
            'ছ' => 'ch',
            'জ' => 'j',
            'ঝ' => 'jh',
            'ঞ' => 'nch',
            'ট' => 't',
            'ঠ' => 'th',
            'ড' => 'd',
            'ঢ' => 'dh',
            'ণ' => 'n',
            'ত' => 't',
            'থ' => 'th',
            'দ' => 'd',
            'ধ' => 'dh',
            'ন' => 'n',
            'প' => 'p',
            'ফ' => 'ph',
            'ব' => 'b',
            'ভ' => 'v',
            'ম' => 'm',
            'য' => 'j',
            'র' => 'r',
            'ল' => 'l',
            'শ' => 'sh',
            'ষ' => 'sh',
            'স' => 's',
            'হ' => 'h',
            'ড়' => 'r',
            'ঢ়' => 'r',
            'য়' => 'oy',
            'ৎ' => 't',
            'ং' => 'ng',
            'ঁ' => '',
            'ঃ' => ':',
            'অ' => 'o',
            'আ' => 'a',
            'ই' => 'i',
            'ঈ' => 'i',
            'উ' => 'u',
            'ঊ' => 'u',
            'ৃ' => 'rr',
            'এ' => 'e',
            'ঐ' => 'oi',
            'ও' => 'o',
            'ঔ' => 'ou',
            'ৌ' => 'ou',
            'া' => 'a',
            'ো' => 'o',
            'ে' => 'e',
            'ি' => 'i',
            'ু' => 'u',
            '্' => '',
            'ী' => 'i',
            'ূ' => 'u',
            '।' => '.',
            '০' => '0',
            '১' => '1',
            '২' => '2',
            '৩' => '3',
            '৪' => '4',
            '৫' => '5',
            '৬' => '6',
            '৭' => '7',
            '৮' => '8',
            '৯' => '9',
        );

        $token     = $this->str_split_unicode($string);
        $converted = '';

        foreach ($token as $bn) {
            if (array_key_exists($bn, $mapping)) {
                $converted .= $mapping[$bn];
            } else {
                $converted .= $bn;
            }
        }

        return $converted;
    }
}
