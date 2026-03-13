<?php

class SMPP_Validator {
    public function is_valid_question($question) {
        $required = ['id', 'language', 'exam', 'subject', 'chapter', 'topic', 'question', 'option_a', 'option_b', 'option_c', 'option_d'];
        foreach ($required as $field) {
            if (!isset($question[$field]) || $question[$field] === '') {
                return false;
            }
        }
        return true;
    }
}
