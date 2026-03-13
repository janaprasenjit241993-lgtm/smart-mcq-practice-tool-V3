<?php

class SMPP_Explanation_Generator {
    public function generate($question) {
        $ai = new SMPP_AI_Engine();
        return $ai->generate($question);
    }
}
