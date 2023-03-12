<?php


function eliminaAcentos($incoming_string){        
        $tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
        $replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";

        return utf8_encode(strtr(utf8_decode($incoming_string),utf8_decode($tofind),$replac));

    }
