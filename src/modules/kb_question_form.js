(function($) {
    
    "use strict";

    function bkb_randomNum(maxNum) {

        return Math.floor(Math.random() * maxNum + 1);//return a number between 1 - 10

    }

    function bkb_checkRegexp(o, regexp) {

        if (!(regexp.test(o.val()))) {

            return false;

        } else {

            return true;

        }

    }

    /*------ASK A KB FORM---------*/

    function bkb_set_user_email() {

        $(".bkb_ques_form").find("input#email").each(function() {
            // Email Status.

            var $this = $(this);

            if ($this.data('value') !== "") {
                $this.val("").val($this.data('value'));
            }
        });

    }

    if ($(".bkb_ques_form").length) {

        // Reset Question Form Field.

        $(".bkb_ques_form").find("input#title, #details, input#email, input#captcha").val("");
        $(".bkb_ques_form").find("select#cat").val("-1");

        if ($(".bkb_ques_form").find("#bkb_privacy").length) {
            $(".bkb_ques_form").find("#bkb_privacy").prop('checked', false);
        }

        bkb_set_user_email();


        // Knowledgebase Question Form Submission.

        $(".bkb_ques_form").find("input[type=submit]").on("click", function() {

            var bkb_ask_email_status = 0;
            
            var bkb_title_bValid = false,
                  bkb_details_bValid = false,
                  bkb_cat_bValid = false, 
                  bkb_ques_email_bValid = false,
                  bkb_captcha_bValid = false,
                  bkb_privacy_bValid = false;

            var bkb_ques_form_submit_button = $(this),
                    bkb_ques_form_id = bkb_ques_form_submit_button.attr('bkb_ques_form_id'),
                    bkb_ques_form_box_container = $("#" + bkb_ques_form_id),
                    bkb_ques_form_field_container = $("#" + bkb_ques_form_id + " .bkb_ques_form"),
                    emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

            var bkb_ques_message_box = bkb_ques_form_box_container.find('.bkb-ques-form-message-box'),
                    bkb_ques_title = bkb_ques_form_field_container.find('#title'),
                    bkb_ques_cat = bkb_ques_form_field_container.find('#cat'),
                    bkb_ques_email = bkb_ques_form_field_container.find('#email'),
                    bkb_ext_attachments = bkb_ques_form_field_container.find('.bkb_ext_attachments'),
                    bkb_ques_captcha_status = bkb_ques_form_field_container.find('#captcha_status'),
                    bkb_privacy = bkb_ques_form_field_container.find('#bkb_privacy');

            if (bkb_ques_captcha_status.val() == 1) {

                var num1 = bkb_ques_form_field_container.find('#num1');
                var num2 = bkb_ques_form_field_container.find('#num2');
                var captcha = bkb_ques_form_field_container.find('#captcha');
                var all_fields = $([]).add(bkb_ques_title).add().add(bkb_ques_cat).add(captcha);

            } else {

                var all_fields = $([]).add(bkb_ques_title).add(bkb_ques_cat);

            }

            if (bkb_ques_email.length == 1) {
                all_fields = $(all_fields).add(bkb_ques_email);
            }

            // Details Field.

            var bkb_ques_details_info = "";

            if (bkb_details_status == 1) {
                var bkb_des_min_length = bkb_details_length;
                var bkb_ques_details = bkb_ques_form_field_container.find('#details');
                all_fields = $(all_fields).add(bkb_ques_details);
            }

            var bkb_bValid = true,
                    required_field_msg = "",
                    ok_border = "border: 1px solid #EEEEEE",
                    error_border = "border: 1px solid #E63F37",
                    bkb_privacy_error = "outline: 2px solid #E63F37";


            if ($.trim(bkb_ques_title.val()).length < 3) {

                bkb_title_bValid = false;
                bkb_ques_title.attr("style", error_border);
                required_field_msg += "" + err_bkb_question + "<br />";

            } else {

                bkb_title_bValid = true;
                bkb_ques_title.attr("style", ok_border);
                required_field_msg += "";

            }

            bkb_bValid = bkb_bValid && bkb_title_bValid;

            // Validate Description Field.

            if (bkb_details_status == 1) {

                if ($.trim(bkb_ques_details.val()).length < bkb_des_min_length) {

                    bkb_details_bValid = false;
                    bkb_ques_details.attr("style", error_border);
                    required_field_msg += "" + err_bkb_details + "<br />";

                } else {

                    bkb_details_bValid = true;
                    bkb_ques_details.attr("style", ok_border);
                    required_field_msg += "";
                    bkb_ques_details_info = bkb_ques_details.val();

                }

                bkb_bValid = bkb_bValid && bkb_details_bValid;

            }

            // End validation of description field.


            if ($.trim(bkb_ques_cat.val()) == -1) {

                bkb_cat_bValid = false;
                bkb_ques_cat.attr("style", error_border);
                required_field_msg += "" + err_bkb_category + "<br />";

            } else {

                bkb_cat_bValid = true;
                bkb_ques_cat.attr("style", ok_border);
                required_field_msg += "";

            }

            bkb_bValid = bkb_bValid && bkb_cat_bValid;

            var bkb_ques_email_val = "";

            if (bkb_ques_email.length == 1) {

                // Email Validation 
                if ($.trim(bkb_ques_email.val()).length == 0 || bkb_checkRegexp(bkb_ques_email, emailRegex) == false) {

                    bkb_ques_email_bValid = false;
                    bkb_ques_email.attr("style", error_border);
                    required_field_msg += "" + err_bkb_ques_email + "<br />";

                } else {

                    bkb_ques_email_bValid = true;
                    bkb_ques_email.attr("style", ok_border);
                    required_field_msg += "";
                    bkb_ques_email_val = bkb_ques_email.val();
                }

                bkb_bValid = bkb_bValid && bkb_ques_email_bValid;

            }

            if (bkb_ques_captcha_status.val() == 1) {

                if ((parseInt($.trim(num1.val())) + parseInt($.trim(num2.val())) != parseInt($.trim(captcha.val())))) {

                    bkb_captcha_bValid = false;
                    captcha.attr("style", error_border);
                    required_field_msg += "" + err_bkb_captcha;

                } else {

                    bkb_captcha_bValid = true;
                    captcha.attr("style", ok_border);
                    required_field_msg += "";

                }

                bkb_bValid = bkb_bValid && bkb_captcha_bValid;

            }


            // Attachments.

            var bkb_attachments = '';

            if (bkb_ext_attachments.length) {
                bkb_ext_attachments.find('li').each(function() {
                    bkb_attachments += $(this).data('file_info') + ','
                })
            }


            // Check Box.

            if (bkb_privacy.length) {

                if (!bkb_privacy.is(':checked')) {

                    bkb_privacy_bValid = false;
                    bkb_privacy.attr("style", bkb_privacy_error);

                } else {
                    bkb_privacy_bValid = true;
                    bkb_privacy.removeAttr("style");

                }

                bkb_bValid = bkb_bValid && bkb_privacy_bValid;

            }

            //Alert Message Box For Required Fields.

            if (bkb_bValid == false) {

                bkb_ques_message_box.html("").addClass("bkb-ques-form-error-box").html(required_field_msg).slideDown("slow");

                setTimeout(function() {
                    bkb_ques_message_box.slideUp("slow");
                }, 3000);

            }


            if (bkb_bValid == true) {

                all_fields.attr("style", ok_border);
                all_fields.addClass('bkb_ques_disabled_field').attr('disabled', 'disabled');
                bkb_ques_form_submit_button.addClass('bkb_ques_disabled_field').attr('disabled', 'disabled');
                bkb_ques_message_box.html("").removeClass("bkb-ques-form-error-box").addClass("bkb-ques-form-wait-box").html(bkb_wait_msg).slideDown("slow");

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        action: 'bkb_ques_save_post_data', // action will be the function name,
                        title: bkb_ques_title.val(),
                        details: bkb_ques_details_info,
                        cat: bkb_ques_cat.val(),
                        email: bkb_ques_email_val,
                        bkb_attachments: bkb_attachments,
                        post_type: bkb_ques_form_field_container.find('#post_type').val(),
                        name_of_nonce_field: bkb_ques_form_field_container.find('#name_of_nonce_field').val()
                    },
                    success: function(data) {

                        if (data.bwl_kb_add_status == 1) {

                            //Reload For New Number.

                            if (bkb_ques_captcha_status.val() == 1) {

                                num1.val(bkb_randomNum(15));
                                num2.val(bkb_randomNum(20));

                            }

                            bkb_ques_message_box.removeClass('bkb-ques-form-wait-box').html("").html(bkb_ques_add_msg).addClass("bkb-ques-form-success-box").delay(3000).slideUp("slow");
                            all_fields.val("").removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                            bkb_ques_cat.val("-1");
                            bkb_ques_form_submit_button.removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                            bkb_set_user_email();
                            bkb_ext_attachments.html("");
                            bkb_privacy.prop('checked', false);
                        } else {

                            bkb_ques_message_box.removeClass('bkb-ques-form-wait-box').html("").html(bkb_ques_add_fail_msg).addClass("bkb-ques-form-error-box").delay(3000).slideUp("slow");
                            all_fields.removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                            bkb_ques_cat.val("-1");
                            bkb_ques_form_submit_button.removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                            bkb_set_user_email();
                            bkb_ext_attachments.html("");
                            bkb_privacy.prop('checked', false);

                        }

                    },
                    error: function(xhr, textStatus, e) {

                        bkb_ques_message_box.removeClass('bkb-ques-form-wait-box').html("").html(bkb_ques_add_fail_msg).addClass("bkb-ques-form-error-box").delay(3000).slideUp("slow");
                        all_fields.removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                        bkb_ques_form_submit_button.removeAttr('disabled').removeClass('bkb_ques_disabled_field');
                        return;

                    }

                });

            }

            return false;

        });

    }


    /*------ Front End Attachments ---------*/

    if ($('.bkb_frontend_attachment_container').length) {

        $('.bkb_frontend_attachment_container').each(function() {

            var $this = $(this);

            var file_frame; // variable for the wp.media file_frame

            var $bkb_ext_attachments = $this.find(".bkb_ext_attachments");

            var $bkb_frontend_attachment_btn = $this.find('.bkb_frontend_attachment_btn');

            $bkb_frontend_attachment_btn.on('click', function(event) {

                event.preventDefault();

                // if the file_frame has already been created, just reuse it
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                file_frame = wp.media.frames.file_frame = wp.media({
                    title: $(this).data('uploader_title'),
                    button: {
                        text: $(this).data('uploader_button_text'),
                    },
                    multiple: true // set this to true for multiple file selection
                });

                file_frame.on('select', function() {

                    var multi_attachment = file_frame.state().get('selection').toJSON();

                    var old_attachment_data = $bkb_ext_attachments.html(),
                            new_attachment_data = "";

                    multi_attachment.forEach(function(o) {

                        new_attachment_data += '<li data-file_info="' + o.url + '">' + o.filename + '<span class="bkbm_ext_remove">X</span></li>';
                    });

                    $bkb_ext_attachments.html("").html(old_attachment_data + new_attachment_data);

                    $(".bkbm_ext_remove").on('click', function() {
                        $(this).parent().remove();
                    });

                });

                file_frame.open();

            });


        })


    }

})(jQuery);