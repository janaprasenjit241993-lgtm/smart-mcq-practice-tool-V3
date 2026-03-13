jQuery(function($){
    const $status = $('#smpp-status');
    const $nextButton = $('#next-q');

    function fillSelect(selector, values, placeholder) {
        const $el = $(selector);
        $el.empty().append('<option value="">' + placeholder + '</option>');
        (values || []).forEach(function(v){
            $el.append('<option value="' + v + '">' + v + '</option>');
        });
    }

    function setStatus(message) {
        $status.text(message);
    }

    setStatus('Loading filters...');

    $.post(smppAjax.url, { action: 'smpp_get_filters', nonce: smppAjax.nonce }, function(res){
        if(!res.success){
            setStatus('Could not load filters. Please upload/index dataset from admin.');
            return;
        }

        const filters = res.data.filters || {};
        fillSelect('#language', filters.language, 'Language');
        fillSelect('#exam', filters.exam, 'Exam');
        fillSelect('#subject', filters.subject, 'Subject');
        fillSelect('#chapter', filters.chapter, 'Chapter');
        fillSelect('#topic', filters.topic, 'Topic');

        setStatus('Filters loaded. Select filters, then press Next.');
    }).fail(function(){
        setStatus('Network error while loading filters.');
    });

    $('#next-q').on('click', function(){
        $nextButton.prop('disabled', true);
        setStatus('Loading question...');

        $.post(
            smppAjax.url,
            {
                action: 'smpp_get_questions',
                nonce: smppAjax.nonce,
                language: $('#language').val(),
                exam: $('#exam').val(),
                subject: $('#subject').val(),
                chapter: $('#chapter').val(),
                topic: $('#topic').val()
            },
            function(res){
                if(res.success){
                    $('#question-box').html(res.data.html);
                    setStatus('Question loaded.');
                    if(window.MathJax){
                        MathJax.typesetPromise();
                    }
                } else {
                    $('#question-box').html('<p>' + ((res.data && res.data.message) || 'No data') + '</p>');
                    setStatus('No question returned for current filters.');
                }
            }
        ).fail(function(){
            $('#question-box').html('<p>Request failed.</p>');
            setStatus('Network error while loading question.');
        }).always(function(){
            $nextButton.prop('disabled', false);
        });
    });

    $(document).on('click','.exp-btn',function(){
        $(this).next('.exp').toggle();
    });
});
