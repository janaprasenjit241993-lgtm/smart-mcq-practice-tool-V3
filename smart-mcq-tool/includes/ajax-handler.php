<?php

add_action('wp_ajax_smpp_get_questions', 'smpp_get_questions');
add_action('wp_ajax_nopriv_smpp_get_questions', 'smpp_get_questions');
add_action('wp_ajax_smpp_get_filters', 'smpp_get_filters');
add_action('wp_ajax_nopriv_smpp_get_filters', 'smpp_get_filters');

function smpp_load_json($path) {
    if (!file_exists($path)) {
        return [];
    }

    $decoded = json_decode(file_get_contents($path), true);
    return is_array($decoded) ? $decoded : [];
}

function smpp_get_filters() {
    check_ajax_referer('smpp_nonce', 'nonce');
    $filters = smpp_load_json(SMPP_PATH . 'data/filters.json');
    wp_send_json_success(['filters' => $filters]);
}

function smpp_get_questions() {
    check_ajax_referer('smpp_nonce', 'nonce');

    $index = smpp_load_json(SMPP_PATH . 'data/questions-index.json');
    $questions = smpp_load_json(SMPP_PATH . 'data/questions.json');

    if (empty($index) || empty($questions)) {
        wp_send_json_error(['message' => 'Dataset unavailable']);
    }

    $language = sanitize_text_field(wp_unslash($_POST['language'] ?? ''));
    $exam = sanitize_text_field(wp_unslash($_POST['exam'] ?? ''));
    $subject = sanitize_text_field(wp_unslash($_POST['subject'] ?? ''));
    $chapter = sanitize_text_field(wp_unslash($_POST['chapter'] ?? ''));
    $topic = sanitize_text_field(wp_unslash($_POST['topic'] ?? ''));

    $list = $index[$language][$exam][$subject][$chapter][$topic] ?? [];

    if (empty($list)) {
        wp_send_json_error(['message' => 'No questions found for selected filters']);
    }

    $id = $list[array_rand($list)];
    $q = $questions[$id] ?? null;

    if (!$q) {
        wp_send_json_error(['message' => 'Question not found']);
    }

    ob_start();
    ?>
    <div class="mcq">
        <h3><?php echo wp_kses_post($q['question'] ?? ''); ?></h3>
        <ul>
            <li><?php echo esc_html($q['option_a'] ?? ''); ?></li>
            <li><?php echo esc_html($q['option_b'] ?? ''); ?></li>
            <li><?php echo esc_html($q['option_c'] ?? ''); ?></li>
            <li><?php echo esc_html($q['option_d'] ?? ''); ?></li>
        </ul>
        <button class="exp-btn" type="button">Explanation</button>
        <div class="exp" style="display:none;"><?php echo wp_kses_post($q['explanation'] ?? ''); ?></div>
    </div>
    <?php

    wp_send_json_success(['html' => ob_get_clean()]);
}
