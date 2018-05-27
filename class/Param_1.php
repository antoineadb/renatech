<?php

class Param {

    private $_login;
    private $_mdp;
    private $_host;
    private $_port;
    private $_loginmsg;
    private $_mdpmsg;
    private $_hostmsg;
    private $_portmsg;
    

    function __construct($_login,$_mdp,$_host,$_port,$_loginmsg,$_mdpmsg,$_hostmsg,$_portmsg) {
        $this->set_login($_login);
        $this->set_mdp($_mdp);
        $this->set_host($_host);
        $this->set_port($_portmsg);
        $this->set_loginmsg($_loginmsg);
        $this->set_mdpmsg($_mdpmsg);
        $this->set_hostmsg($_hostmsg);
        $this->set_portmsg($_portmsg);
                
    }
    
    function get_login() {
        return $this->_login;
    }

    function get_mdp() {
        return $this->_mdp;
    }

    function get_host() {
        return $this->_host;
    }

    function get_port() {
        return $this->_port;
    }

    function get_loginmsg() {
        return $this->_loginmsg;
    }

    function get_mdpmsg() {
        return $this->_mdpmsg;
    }

    function get_hostmsg() {
        return $this->_hostmsg;
    }

    function get_portmsg() {
        return $this->_portmsg;
    }

    function set_login($_login) {
        $this->_login = $_login;
    }

    function set_mdp($_mdp) {
        $this->_mdp = $_mdp;
    }

    function set_host($_host) {
        $this->_host = $_host;
    }

    function set_port($_port) {
        $this->_port = $_port;
    }

    function set_loginmsg($_loginmsg) {
        $this->_loginmsg = $_loginmsg;
    }

    function set_mdpmsg($_mdpmsg) {
        $this->_mdpmsg = $_mdpmsg;
    }

    function set_hostmsg($_hostmsg) {
        $this->_hostmsg = $_hostmsg;
    }

    function set_portmsg($_portmsg) {
        $this->_portmsg = $_portmsg;
    }


}
