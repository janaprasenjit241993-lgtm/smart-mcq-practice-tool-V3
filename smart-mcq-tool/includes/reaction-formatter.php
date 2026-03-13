<?php

class SMPP_Reaction_Formatter {
    public function format($reaction) {
        return $reaction . ' <br> $\\rightarrow$';
    }
}
