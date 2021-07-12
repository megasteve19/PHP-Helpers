<?php
    /**
     * Really simple MySQL class to run basic queries.
     * 
     * @since 1.0.0
     * @author megasteve19
     * @link
     */
    class MySQL
    {
        #Config
        public static ?string $Hostname = null;
        public static ?string $Username = null;
        public static ?string $Password = null;
        public static ?string $Database = null;
        public static ?int $Port = null;
        public static ?string $Socket = null;

        #Other
        private static int $InsertId = 0;

        /**
         * Runs a query. Use prepared type query to pass data to query.
         * @see https://www.php.net/manual/tr/mysqli.prepare.php
         * 
         * @param string $Query Query to run.
         * @param array $Data Data to pass query.
         * 
         * @return array|bool Returns array on SELECT* queries bool on other queries.
         * @since 1.0.0
         */
        public static function Query(string $Query, array $Data = null): array|bool
        {
            if(empty($Data))
            {
                return self::SecureQuery($Query);
            }
            return self::PreparedQuery($Query, $Data);
        }

        /**
         * Same as `Query($Query, $Data)`. Diffrence is it will return first key of the array. You can use for other type of queries with it.
         * 
         * @param string $Query Query to run.
         * @param array $Data Data to pass query.
         * 
         * @return array|bool Returns array on SELECT* queries bool on other queries.
         * @since 1.0.0
         */
        public static function SingleRow(string $Query, $Data = null): array|bool
        {
            $Result = self::Query($Query, $Data);
            if(!empty($Result[0]))
            {
                return $Result[0];
            }
            return $Result;
        }

        /**
         * Returns inserted id from last query.
         * 
         * @return int Id
         * @since 1.0.0
         */
        public static function InsertId(): int
        {
            return self::$InsertId;
        }
        
        /**
         * Counts a table.
         * 
         * @param string $TableName Table name to count.
         * 
         * @return int|false Counts on success false on error.
         * @since 1.0.0
         */
        public static function Count(string $TableName): int|false
        {
            $Result = self::Query("SELECT COUNT(*) AS Count FROM $TableName");
            if($Result)
            {
                return (int)$Result[0]["Count"];
            }
            return false;
        }

        /**
         * Esablishes new MySQL connection.
         * 
         * @return mysqli|false MySQLI on success false on failure.
         * @since 1.0.0
         */
        private static function Connect(): mysqli|false
        {
            $Connection = new mysqli(self::$Hostname, self::$Username, self::$Password, self::$Database, self::$Port, self::$Socket);
            if($Connection->connect_error)
            {
                return false;
            }
            return $Connection;
        }

        /**
         * Runs a query that not need to run prepared query.
         * 
         * @param string $Query Query to run.
         * 
         * @return array|bool On fetching something it will return array, otherwise true on success false on error.
         * @since 1.0.0
         */
        private static function SecureQuery(string $Query): array|bool
        {
            $Connection = self::Connect();
            if(!$Connection)
            {
                return false;
            }
            $Result = self::DigestResult($Connection->query($Query));
            $Connection->close();
            return $Result;
        }

        /**
         * Runs a prepared query.
         * 
         * @param string $Query Query to run.
         * @param array $Data Data to pass.
         * 
         * @return array|bool On fetching something it will return array, otherwise true on success false on error.
         * @since 1.0.0
         */
        private static function PreparedQuery(string $Query, array $Data): array|bool
        {
            $Connection = self::Connect();
            if($Connection)
            {
                $Statement = $Connection->prepare($Query);
                if($Statement)
                {
                    $Statement->bind_param(self::GetTypes($Data), ...$Data);
                    if($Statement->execute())
                    {
                        self::$InsertId = $Connection->insert_id;
                        $Result = self::DigestResult($Statement->get_result());
                        if(gettype($Result) != "array")
                        {
                            $Result = true;
                        }
                        $Statement->close();
                        $Connection->close();
                        return $Result;
                    }
                }
            }
            return false;
        }

        /**
         * Generates type string for prepared queries.
         * 
         * @param array $Variables Array to check types and add to string.
         * 
         * @return string Generated string.
         * @since 1.0.0
         */
        private static function GetTypes(array $Variables): string
        {
            $Types = "";
            foreach($Variables as $Variable)
            {
                if(is_numeric($Variable))
                {
                    $Types .= "i";
                    continue;
                }
                $Types .= "s";
            }
            return $Types;
        }

        /**
         * Digests result, makes me happy to reduce some code!
         * 
         * @return array|bool Array on if result is mysqli_result object and bool is up to quality of your query.
         * @since 1.0.0
         */
        private static function DigestResult(mysqli_result|bool $Result): array|bool
        {
            if($Result instanceof mysqli_result)
            {
                return $Result->fetch_all(MYSQLI_ASSOC);
            }
            return $Result;
        }
    }
?>
