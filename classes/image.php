<?php
    /**
     * GD based JPEG image class.
     * 
     * @author megasteve19
     * @since 1.0.0
     */
    class Image
    {
        /**
         * @var bool $Error If any error occurs when loading image it will be true.
         */
        public bool $Error = false;

        /**
         * @var GdImage $Image Image to play with.
         */
        private GdImage $Image;

        /**
         * @param string $PathToImage Absolute or relative path to image file.
         * 
         * @return void
         * @since 1.0.0
         */
        public function __construct(string $PathToImage)
        {
            if(file_exists($PathToImage))
            {
                if(in_array(pathinfo($PathToImage, PATHINFO_EXTENSION), ["jpeg", "jpg"]))
                {
                    $Image = imagecreatefromstring(file_get_contents($PathToImage));
                    if($Image != false)
                    {
                        $this->Image = $Image;
                        return;
                    }
                }
            }
            $this->Error = true;
        }

        /**
         * If necessary scales image by width.
         * 
         * @param int $Width Base width to scale image
         * 
         * @return void
         * @since 1.0.0
         */
        public function ScaleX(int $Width): void
        {
            if($this->Width() > $Width)
            {
                $Height = ($Width / $this->Width()) * $this->Height();
                $this->Resize($Width, $Height);
            }
        }

        /**
         * If necessary scales image by heigth.
         * 
         * @param int $Height Base heigth to scale image.
         * 
         * @return void
         * @since 1.0.0
         */
        public function ScaleY(int $Height): void
        {
            if($this->Width() > $Height)
            {
                $Width = ($Height / $this->Height()) * $this->Width();
                $this->Resize($Width, $Height);
            }
        }

        /**
         * Compresses image by given level.
         * 
         * @param int $Level Level of compression. 0 to 100. 0 worst quality, 100 best quality.
         * 
         * @return void
         * @since 1.0.0
         */
        public function Compress(int $Level): void 
        {
            ob_start();
            imagejpeg($this->Image, null, $Level);
            $this->Image = imagecreatefromstring(ob_get_clean());
        }

        /**
         * Saves image by given path. Image saved as jpeg, so use `.jpeg` or `.jpg` extension.
         * 
         * @param string $Path Path to save file.
         * 
         * @return bool True on success false on failure.
         * @since 1.0.0
         */
        public function Save(string $Path): bool
        {
            if(ob_start())
            {
                if(imagejpeg($this->Image))
                {
                    if(!is_dir(pathinfo($Path, PATHINFO_DIRNAME)))
                    {
                        if(!mkdir(pathinfo($Path, PATHINFO_DIRNAME)))
                        {
                            return false;
                        }
                    }
                    if(file_put_contents($Path, ob_get_clean()))
                    {
                        return true;
                    }
                }
            }
            return false;
        }

        /**
         * Returns image width
         * 
         * @return int Image's width.
         * @since 1.0.0
         */
        private function Width(): int
        {
            return imagesx($this->Image);
        }

        /**
         * Returns image heigth
         * 
         * @return int Image's heigth.
         * @since 1.0.0
         */
        private function Height(): int
        {
            return imagesy($this->Image);
        }

        /**
         * Resizes image by given new `width` and `height`
         * 
         * @param int $Width New width of image.
         * @param int $Height New height of image.
         */
        private function Resize(int $Width, int $Height): void
        {
            $Image = imagecreatetruecolor($Width, $Height);
            imagecopyresampled($Image, $this->Image, 0, 0, 0, 0, $Width, $Height, $this->Width(), $this->Height());
            $this->Image = $Image;
        }
    }
?>
