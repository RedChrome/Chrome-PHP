<?php

/* PHP Implementation of XTEA (www.php-einfach.de)
*
* XTEA was designed in 1997 by David Wheeler AND Roger Needham
* of the Cambridge Computer Laboratory.
* It is not subject to any patents.
*
* It is a 64-bit Feistel cipher, consisting of 64 rounds.
* XTA has a key length of 128 bits.
*
*
* ***********************
* Diese Implementierung darf frei verwendet werden, der Autor uebernimmt keine
* Haftung fuer die Richtigkeit, Fehlerfreiheit oder die Funktionsfaehigkeit dieses Scripts.
* Benutzung auf eigene Gefahr.
*
* Ueber einen Link auf www.php-einfach.de wuerden wir uns freuen.
*
* ************************
* Usage:
* <?php
* include("xtea.class.php");
*
* $xtea = new XTEA("secret Key");
* $cipher = $xtea->encrypt("Hello World"); //Encrypts 'Hello World'
* $plain = $xtea->decrypt($cipher); //Decrypts the cipher text
*
* echo $plain;
* ?>
*/

/**
 *@ignore
 */
class XTEA
{

    //Private
    var $key;

    // CBC OR ECB Mode
    // normaly, CBC Mode would be the right choice
    var $cbc = 1;

    function XTEA($key)
    {
        $this->key_setup($key);
    }

    //Verschluesseln
    function encrypt($text)
    {
        $n = strlen($text);
        if($n % 8 != 0)
            $lng = ($n + (8 - ($n % 8)));
        else
            $lng = 0;

        $text = str_pad($text, $lng, ' ');
        $text = $this->_str2long($text);

        //Initialization vector: IV
        if($this->cbc == 1) {
            $cipher[0][0] = time();
            $cipher[0][1] = (double) microtime() * 1000000;
        }

        $a = 1;
        for($i = 0; $i < count($text); $i += 2) {
            if($this->cbc == 1) {
                //$text mit letztem Geheimtext XOR Verknuepfen
                //$text is XORed with the previous ciphertext
                $text[$i] ^= $cipher[$a - 1][0];
                $text[$i + 1] ^= $cipher[$a - 1][1];
            }

            $cipher[] = $this->block_encrypt($text[$i], $text[$i + 1]);
            $a++;
        }

        $output = '';
        for($i = 0; $i < count($cipher); $i++) {
            $output .= $this->_long2str($cipher[$i][0]);
            $output .= $this->_long2str($cipher[$i][1]);
        }

        return base64_encode($output);
    }


    //Entschluesseln
    function decrypt($text)
    {
        $plain = array();
        $cipher = $this->_str2long(base64_decode($text));

        if($this->cbc == 1)
            $i = 2; //Message start at second block
        else
            $i = 0; //Message start at first block

        for($i; $i < count($cipher); $i += 2) {
            $return = $this->block_decrypt($cipher[$i], $cipher[$i + 1]);

            //Xor Verknuepfung von $return und Geheimtext aus von den letzten beiden Bloecken
            //XORed $return with the previous ciphertext
            if($this->cbc == 1)
                $plain[] = array($return[0] ^ $cipher[$i - 2], $return[1] ^ $cipher[$i - 1]);
            else //EBC Mode

                $plain[] = $return;
        }

        for($i = 0; $i < count($plain); $i++) {
            $output .= $this->_long2str($plain[$i][0]);
            $output .= $this->_long2str($plain[$i][1]);
        }

        return $output;
    }

    //Bereitet den Key zum ver/entschluesseln vor
    function key_setup($key)
    {
        if(is_array($key))
            $this->key = $key;
        else
            if(isset($key) && !empty($key))
                $this->key = $this->_str2long(str_pad($key, 16, $key));
            else
                $this->key = array(0, 0, 0, 0);
    }

    /***********************************
    Some internal functions
    ***********************************/
    function block_encrypt($y, $z)
    {
        $sum = 0;
        $delta = 0x9e3779b9;

        // start cycle
        for($i = 0; $i < 32; $i++) {
            $y = $this->_add($y, $this->_add($z << 4 ^ $this->_rshift($z, 5), $z) ^ $this->_add($sum, $this->key[$sum & 3]));

            $sum = $this->_add($sum, $delta);

            $z = $this->_add($z, $this->_add($y << 4 ^ $this->_rshift($y, 5), $y) ^ $this->_add($sum, $this->key[$this->_rshift($sum, 11) & 3]));

        }

        // end cycle
        $v[0] = $y;
        $v[1] = $z;

        return array($y, $z);

    }

    function block_decrypt($y, $z)
    {
        $delta = 0x9e3779b9;
        $sum = 0xC6EF3720;
        $n = 32;

        // start cycle
        for($i = 0; $i < 32; $i++) {
            $z = $this->_add($z, -($this->_add($y << 4 ^ $this->_rshift($y, 5), $y) ^ $this->_add($sum, $this->key[$this->_rshift($sum, 11) & 3])));
            $sum = $this->_add($sum, -$delta);
            $y = $this->_add($y, -($this->_add($z << 4 ^ $this->_rshift($z, 5), $z) ^ $this->_add($sum, $this->key[$sum & 3])));

        }
        // end cycle

        return array($y, $z);
    }


    function _rshift($integer, $n)
    {
        // convert to 32 bits
        if(0xffffffff < $integer || -0xffffffff > $integer) {
            $integer = fmod($integer, 0xffffffff + 1);
        }

        // convert to unsigned integer
        if(0x7fffffff < $integer) {
            $integer -= 0xffffffff + 1.0;
        } elseif(-0x80000000 > $integer) {
            $integer += 0xffffffff + 1.0;
        }

        // do right shift
        if(0 > $integer) {
            $integer &= 0x7fffffff; // remove sign bit before shift
            $integer >>= $n; // right shift
            $integer |= 1 << (31 - $n); // set shifted sign bit
        }
        else {
            $integer >>= $n; // use normal right shift
        }

        return $integer;
    }


    function _add($i1, $i2)
    {
        $result = 0.0;

        foreach(func_get_args() AS $value) {
            // remove sign if necessary
            if(0.0 > $value) {
                $value -= 1.0 + 0xffffffff;
            }

            $result += $value;
        }

        // convert to 32 bits
        if(0xffffffff < $result || -0xffffffff > $result) {
            $result = fmod($result, 0xffffffff + 1);
        }

        // convert to signed integer
        if(0x7fffffff < $result) {
            $result -= 0xffffffff + 1.0;
        } elseif(-0x80000000 > $result) {
            $result += 0xffffffff + 1.0;
        }

        return $result;
    }


    //Einen Text in Longzahlen umwandeln
    //Covert a string into longinteger
    function _str2long($data)
    {
        $n = strlen($data);
        $tmp = unpack('N*', $data);
        $data_long = array();
        $j = 0;

        foreach($tmp AS $value)
            $data_long[$j++] = $value;
        return $data_long;
    }

    //Longzahlen in Text umwandeln
    //Convert a longinteger into a string
    function _long2str($l)
    {
        return pack('N', $l);
    }

} ?>