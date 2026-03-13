<?php

class SMPP_LaTeX_Formatter {
    public function clean($text) {
        $text = str_replace('CO2', '$CO_2$', $text);
        $text = str_replace('H2O', '$H_2O$', $text);
        return $text;
    }
}
