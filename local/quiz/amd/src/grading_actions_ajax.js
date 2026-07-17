define([
    "jquery",
    "core/ajax",
    "core/templates",
    "core/notification",
    "core/str",
], function ($, ajax, templates, notification, str) {
    return {
        init: function () {
            $('#id_fill_ai_grade').on('click', function () {

                var url = window.location.href;
                var attemptid = getURLParameter("attempt", url);
                var slot = getURLParameter("slot", url);
                var qid = $(this).attr('data-qid');
                var button = $(this);
                
                $.ajax({
                    url: M.cfg.wwwroot + '/local/quiz/ajax.php',
                    method: 'POST',
                    data: {
                        action: 'getgrades',
                        attemptid: attemptid,
                        qid: qid,
                        sesskey: M.cfg.sesskey
                    },
                    success: function (data) {
                        try {
                            if (data.status) {
                                if (data.errormessage) {
                                    notification.alert(
                                            'Grading Issue',
                                            `There is some issue with the following message: <strong>${data.errormessage}</strong>. Kindly grade this manually.`,
                                            'OK'
                                            );
                                } else {

                                    var question = button.closest('.que');

                                    console.log(question);
                                    var textarea = $('textarea[name$="_-comment"]');

                                    textarea.val(data.feedback);

                                    textarea.trigger('change');

                                    $('input[id$="_-mark"]').val(data.grade).trigger('change');

                                }
                            } else {
                                notification.alert('Error', 'There is some issue. Please try again later.', 'OK');
                            }
                        } catch (err) {
                            notification.exception(err);
                        }
                    },
                    error: function (err) {
                        notification.alert('AJAX Error', err.statusText || 'Unknown error', 'OK');
                    }
                });
            });
        }
    };

    function getURLParameter(name, url) {
        return (
                decodeURIComponent(
                        (new RegExp("[?|&]" + name + "=" + "([^&;]+?)(&|#|;|$)").exec(
                                url
                                ) || [null, ""])[1].replace(/\+/g, "%20")
                        ) || null
                );
    }
});
