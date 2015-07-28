<?php

class Cache {

    public $dirname;
    public $duration; //Durée de vie du cache EN MINUTE
    public $buffer;

    function __construct($dirname, $duration) {
        $this->dirname = $dirname;
        $this->duration = $duration;
    }

    public function write($filename, $content) {
        return file_put_contents($this->dirname . '/' . $filename, $content);
    }

    public function read($filename) {
        $file = $this->dirname . '/' . $filename;
        if (!file_exists($file)) {
            return false;
        }
        $lifetime = (time() - filemtime($file)) / 60;
        if ($lifetime > $this->duration) {
            return false;
        }
        return file_get_contents($file);
    }

    public function delete($filename) {
        $file = $this->dirname . '/' . $filename;
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function clear() {
        $files = glob($this ->dirname . '/*');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function inc($file, $cachename = null) {        
        if (!$cachename) {
            $cachename = basename($file);
        }
        if ($content = $this->read($cachename)) {
            echo $content;
            return true;
        }
        ob_start();
        require $file;
        $content = ob_get_clean();
        $this->write($cachename, $content);
        echo $content;
        return true;
    }

    public function start($cachename) {
        if($content = $this->read($cachename)){
            echo $content;
            $this->buffer=false;
            return true;
        }
        ob_start();
        $this->buffer = $cachename;
    }

    public function end() {
        if(!$this->buffer){
            return false;
        }
        $content = ob_get_clean();
        echo $content;
        $this->write($this->buffer, $content);
    }

}
