<?php

class Projetphase2LTM {

    private $_idprojet;
    private $_refinterneprojet;

    public function __construct($idprojet, $refinterneprojet) {
        $this->setIdprojet($idprojet);
        $this->setRefinterneprojet($refinterneprojet);
    }

    public function getIdprojet() {
        return $this->_idprojet;
    }

    public function setIdprojet($idprojet) {
        $this->_idprojet = (int) $idprojet;
    }

    public function getRefinterneprojet() {
        return $this->_refinterneprojet;
    }

    public function setRefinterneprojet($param) {
        $strExpression = "#^[a-zA-ZàáâäãåèéêëìíîïòóôöõøùúûüÿýñçčšžÀÁÂÄÃÅÈÉÊËÌÍÎÏÒÓÔÖÕØÙÚÛÜŸÝÑßÇŒÆČŠŽ∂ð%&:0-9\042\'’/=°()*_ ,.-]+$#";
        if (preg_match($strExpression, $param) || empty($param)) {
            $this->_refinterneprojet = $param;
        } else {
            include_once '../decide-lang.php';
            trigger_error((TXT_ERR_REFINTERNEPROJET));
            exit();
        }
    }

}
