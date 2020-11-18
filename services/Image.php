<?php

namespace Services;

class Image
{
    private $image;

    public function __construct($file)
    {
        $this->file = $file;
        $this->createFromFile();
    }

    private function createFromFile()
    {
        $type = mime_content_type($this->file);

        switch ($type) {
            case 'image/jpeg':
                $this->image = imageCreateFromJpeg($this->file);
            break;

            case 'image/png':
                $this->image = imageCreateFromPng($this->file);
            break;

            case 'image/gif':
                $this->image = imageCreateFromGif($this->file);
            break;
        }

        $this->width = imageSX($this->image);
        $this->height = imageSY($this->image);
    }

    public function resize($newWidth, $newHeight)
    {
        $newImage = imageCreateTrueColor($newWidth, $newHeight);

        imageCopyResampled(
            $newImage,
            $this->image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $this->width,
            $this->height
        );
        
        $this->image = $newImage;
        $this->width = $newWidth;
        $this->height = $newHeight;
    }

    public function over($width, $height)
    {
        return ($this->width > $width || $this->height > $height) ? true : false;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getBase64()
    {
        ob_start();
            imageJpeg($this->image, NULL, 100);
            $image = ob_get_contents();
        ob_end_clean();

        $base64 = base64_encode($image);
        
        return "data:image/jpeg;base64,{$base64}";
    }

    public function save()
    {
        $imageName = time() . '.jpg';
        $filename = PATH_ROOT . '/public/upload/' . $imageName;
        imageJpeg($this->image, $filename, 100);

        return $imageName;
    }
}
