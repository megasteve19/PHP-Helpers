<?php
    /**
     * Really basic template engine to rendering views. Uses PHP's syntax.
     * 
     * @author megasteve19
     * @since 1.0.0
     */
    class Templater
    {
        public static string $ViewsDirectory = "";

        private static string $Rendered = "";

        /**
         * Renders a view by given path.
         * 
         * @param string $View Relative path to view. View file extension must `.phtml`. Don't need to add extension into path.
         * @param mixed $Data [Optional] Data to pass view.
         * 
         * @return void
         * @since 1.0.0
         */
        public static function Render(string $View, mixed $Data = null): void
        {
            ob_start();
            require self::$ViewsDirectory . "$View.phtml";
            self::$Rendered .= ob_get_clean();
        }

        /**
         * Renders all views at once by given array.
         * 
         * @param array $Views Views to render.
         * 
         * @return void
         * @since 1.0.0
         */
        public static function MultiRender(array $Views): void
        {
            foreach($Views as $View)
            {
                if(gettype($View) == "string")
                {
                    self::Render($View);
                    continue;
                }
                self::Render($View[0], $View[1]);
            }
        }

        /**
         * Prints rendered buffer.
         * 
         * @param bool $Flush If true it will delete rendered buffer.
         * 
         * @return void
         * @since 1.0.0
         */
        public static function Print(bool $Flush = false): void
        {
            print self::GetRendered($Flush);
        }

        /**
         * Returns rendered buffer.
         * 
         * @param bool $Flush If true it will delete rendered buffer.
         * 
         * @return string Rendered buffer.
         * @since 1.0.0
         */
        public static function GetRendered(bool $Flush = true): string
        {
            if($Flush)
            {
                $Return = self::$Rendered;
                self::$Rendered = "";
                return $Return;
            }
            return self::$Rendered;
        }
    }
?>
