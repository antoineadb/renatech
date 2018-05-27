<?php

class Params {

    private $id;
    private $description;
    private $login;
    private $mdp;
    private $host;
    private $port;


    
    function __construct($description,$login,$mdp,$host,$port) {        
        $this->setDescription($description);
        $this->setLogin($login);
        $this->setMdp($mdp);
        $this->setHost($host);
        $this->setPort($port);
    }
    
    function getDescription() {
        return $this->description;
    }

    function getLogin() {
        return $this->login;
    }

    function getMdp() {
        return $this->mdp;
    }

    function getHost() {
        return $this->host;
    }

    function getPort() {
        return $this->port;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    function setHost($host) {
        $this->host = $host;
    }

    function setPort($port) {
        $this->port = $port;
    }

    function getId() {
        return $this->id;
    }


   
}
