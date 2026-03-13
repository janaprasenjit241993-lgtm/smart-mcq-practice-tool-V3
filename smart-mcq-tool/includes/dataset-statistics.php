<?php

class SMPP_Dataset_Statistics {
    public function summarize($questions) {
        $stats = [
            'total_questions' => count($questions),
            'languages' => [],
            'exams' => [],
            'subjects' => [],
        ];

        foreach ($questions as $q) {
            $stats['languages'][$q['language'] ?? 'unknown'] = true;
            $stats['exams'][$q['exam'] ?? 'general'] = true;
            $stats['subjects'][$q['subject'] ?? 'general'] = true;
        }

        $stats['languages'] = count($stats['languages']);
        $stats['exams'] = count($stats['exams']);
        $stats['subjects'] = count($stats['subjects']);

        return $stats;
    }
}
