<?php

namespace TODOS\LIB;

Trait Filter
{
    private $specialCharsRegex = "/[ !$%&'()*+,-./:;<=>?@[\]^_`{|}~" . '#"]/g';

    protected function filter_str($str)
    {
        # $str = strip_tags($str);
        return filter_var(htmlentities($str), FILTER_SANITIZE_STRING);
    }

    protected function filter_int($int)
    {
        return filter_var($int, FILTER_VALIDATE_INT);
    }

    protected function filter_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function filter_passwd($passwd)
    {
        $passwd = $this->filter_str($passwd);
        if (preg_match('/[a-z]/', $passwd) === 1){
            if (preg_match('/[A-Z]/', $passwd) === 1){
                if (preg_match('/[0-9]/', $passwd) === 1){
                    if (strlen($passwd)>4){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function filter_fullName($fullName)
    {
        return ctype_alpha(str_replace('-', '', str_replace("'", '', str_replace(' ', '', $fullName)))) && $fullName != '';
    }
}