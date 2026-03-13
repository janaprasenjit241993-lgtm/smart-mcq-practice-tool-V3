# Smart MCQ Tool V3

## Installation
1. Download this repository as ZIP.
2. In WordPress go to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP and activate **Smart MCQ Tool V3**.

> This repository includes a root plugin bootstrap (`smart-mcq-tool.php`) so WordPress can detect the plugin when uploading the repo ZIP directly.
> If you zip only the `smart-mcq-tool/` folder, `smart-mcq-tool/smart-mcq-tool.php` is also a valid plugin entry header.

## Shortcode
Use this shortcode in any WordPress page/post:

```text
[smart_mcq]
```

## How it works (end-to-end)

1. **Admin uploads CSV**
   - Go to **WP Admin → MCQ Dataset**.
   - Upload a CSV file.

2. **Dataset processing**
   - CSV rows are loaded and normalized.
   - Invalid rows are filtered out.
   - Indexer generates:
     - `data/questions.json`
     - `data/questions-index.json`
     - `data/filters.json`

3. **Frontend rendering**
   - Add `[smart_mcq]` to a page.
   - The shortcode renders filter dropdowns and question container UI.

4. **AJAX flow**
   - Frontend JS first requests filters via `smpp_get_filters`.
   - User selects filter values and clicks **Next Question**.
   - JS requests one random question via `smpp_get_questions`.
   - The response HTML is injected into the question box.
   - If MathJax is available, formulas are typeset.

## Expected CSV columns
At minimum, include:

- `id`
- `language`
- `exam`
- `subject`
- `chapter`
- `topic`
- `question`
- `option_a`
- `option_b`
- `option_c`
- `option_d`

Optional but recommended:

- `explanation`

Sample file included: `data/csv/sample-mcq-dataset.csv`

## Notes

- If no dataset is indexed yet, frontend AJAX endpoints return dataset/filter errors.
- Nonce checks are used in AJAX handlers for request validation.
