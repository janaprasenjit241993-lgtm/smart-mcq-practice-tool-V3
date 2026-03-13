<?php

class SMPP_Indexer {
    public function build($questions) {
        $index = [];
        $normalized = [];
        $filters = [
            'language' => [],
            'exam' => [],
            'subject' => [],
            'chapter' => [],
            'topic' => [],
        ];

        foreach ($questions as $q) {
            $id = (string) ($q['id'] ?? uniqid('q_', true));
            $normalized[$id] = $q;

            $lang = $q['language'] ?? 'unknown';
            $exam = $q['exam'] ?? 'general';
            $subject = $q['subject'] ?? 'general';
            $chapter = $q['chapter'] ?? 'general';
            $topic = $q['topic'] ?? 'general';

            $index[$lang][$exam][$subject][$chapter][$topic][] = $id;

            $filters['language'][$lang] = true;
            $filters['exam'][$exam] = true;
            $filters['subject'][$subject] = true;
            $filters['chapter'][$chapter] = true;
            $filters['topic'][$topic] = true;
        }

        $filters = array_map(static function ($set) {
            $values = array_keys($set);
            sort($values);
            return array_values($values);
        }, $filters);

        file_put_contents(SMPP_PATH . 'data/questions.json', wp_json_encode($normalized));
        file_put_contents(SMPP_PATH . 'data/questions-index.json', wp_json_encode($index));
        file_put_contents(SMPP_PATH . 'data/filters.json', wp_json_encode($filters));

        return [
            'total_questions' => count($normalized),
            'languages' => count($filters['language']),
            'exams' => count($filters['exam']),
            'subjects' => count($filters['subject']),
            'chapters' => count($filters['chapter']),
            'topics' => count($filters['topic']),
        ];
    }
}
