<?php
    class Text
    {
        /**
         * Generates random string to use.
         * ! Not be used for cryptographically purposes. @see `Text::GenerateAPIKey()`
         * 
         * @param int $Lenght Lenght of the generated string.
         * 
         * @return string Generated string
         * @since 1.0.0
         */
        public static function Random(int $Lenght): string
        {
            $Chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
            $Generated = "";
            for($i=0; $i < $Lenght; $i++)
            {
                $Generated .= $Chars[rand(0, count($Chars) - 1)];
            }
            return $Generated;
        }

        /**
         * Cuts string if necessary, by given max length.
         * 
         * @param string $String String to truncate.
         * @param int $MaxLenght Max lenght of string.
         * 
         * @return string Truncated string
         * @since 1.0.0
         */
        public static function Truncate(string $String, int $MaxLenght): string
        {
            if(strlen($String) > $MaxLenght)
            {
                return substr($String, 0, $MaxLenght);
            }
            return $String;
        }

        /**
         * Generates `cryptographically secured' string, i still didn't learn to this part. So it's open source, if you have better idea; you can change this.
         * 
         * @param int $Lenght
         * 
         * @return string
         * @since 1.0.0
         */
        public static function GenerateAPIKey(int $Lenght = 48): string
        {
            return bin2hex(openssl_random_pseudo_bytes($Lenght));
        }
    }
?>
