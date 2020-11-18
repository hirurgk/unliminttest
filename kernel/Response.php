<?php

namespace Kernel;

class Response extends Singleton
{
    private $content = "";
    
    /**
     * status
     * Устанавливает HTTP-код и описание
     * @param int $code
     * @param string $description
     * @return void
     */
    public function status($code, $description = "")
    {
        header("HTTP/1.1 {$code} {$description}");
    }
    
    /**
     * header
     * Устанавливает HTTP-заголовок
     * @param string $name
     * @param string $value
     * @return void
     */
    public function header($name, $value)
    {
        header("{$name}: {$value}");
    }
    
    /**
     * content
     * Записывает контент для вывода в теле ответа
     * @param string $content
     * @return void
     */
    public function content($content)
    {
        $this->content .= $content;
    }
    
    /**
     * print
     * Выводит контент в теле ответа
     * @return void
     */
    public function print()
    {
        echo $this->content;
    }
}
