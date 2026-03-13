<?php

class SMPP_Duplicate_Detector {
    public function detect($questions) {
        $seen = [];
        $duplicates = [];

        foreach ($questions as $q) {
            $hash = md5(strtolower(trim(($q['question'] ?? '') . '|' . ($q['option_a'] ?? '') . '|' . ($q['option_b'] ?? '') . '|' . ($q['option_c'] ?? '') . '|' . ($q['option_d'] ?? ''))));
            if (isset($seen[$hash])) {
                $duplicates[] = $q;
            } else {
                $seen[$hash] = true;
            }
        }

        return $duplicates;
    }
}
