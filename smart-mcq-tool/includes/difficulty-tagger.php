<?php

class SMPP_Difficulty_Tagger {
    public function tag($question) {
        $length = strlen(strip_tags($question['question'] ?? ''));
        if ($length < 60) {
            return 'easy';
        }
        if ($length < 140) {
            return 'medium';
        }
        return 'hard';
    }
}
