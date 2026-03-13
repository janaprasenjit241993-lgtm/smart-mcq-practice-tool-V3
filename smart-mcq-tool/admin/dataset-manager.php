<?php
$dir = SMPP_PATH . 'data/csv/';
$message = '';
$stats = [];

if (!empty($_FILES['csv_file'])) {
    check_admin_referer('smpp_upload_csv');

    $file = $_FILES['csv_file'];
    $target = $dir . sanitize_file_name($file['name']);

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $loader = new SMPP_CSV_Loader();
        $validator = new SMPP_Validator();
        $dupe_detector = new SMPP_Duplicate_Detector();
        $stats_engine = new SMPP_Dataset_Statistics();

        $raw_questions = $loader->load($target);
        $valid_questions = array_values(array_filter($raw_questions, [$validator, 'is_valid_question']));
        $duplicates = $dupe_detector->detect($valid_questions);

        $indexer = new SMPP_Indexer();
        $stats = $indexer->build($valid_questions);
        $summary = $stats_engine->summarize($valid_questions);

        $message = sprintf(
            'Dataset indexed. Valid: %d, Duplicates detected: %d, Languages: %d, Exams: %d, Subjects: %d.',
            $summary['total_questions'],
            count($duplicates),
            $summary['languages'],
            $summary['exams'],
            $summary['subjects']
        );
    } else {
        $message = 'CSV upload failed.';
    }
}
?>

<h1>Dataset Manager</h1>

<?php if ($message) : ?>
    <div class="updated"><p><?php echo esc_html($message); ?></p></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('smpp_upload_csv'); ?>
    <input type="file" name="csv_file" accept=".csv" required>
    <button class="button button-primary" type="submit">Upload CSV</button>
</form>

<?php if (!empty($stats)) : ?>
    <h2>Dataset Statistics</h2>
    <ul>
        <li>Total Questions: <?php echo esc_html((string) $stats['total_questions']); ?></li>
        <li>Languages: <?php echo esc_html((string) $stats['languages']); ?></li>
        <li>Exams: <?php echo esc_html((string) $stats['exams']); ?></li>
        <li>Subjects: <?php echo esc_html((string) $stats['subjects']); ?></li>
        <li>Chapters: <?php echo esc_html((string) $stats['chapters']); ?></li>
        <li>Topics: <?php echo esc_html((string) $stats['topics']); ?></li>
    </ul>
<?php endif; ?>
