;(function ($) {
  $(document).ready(function () {
    "use strict"

    // 00. RTL status check.
    var rtl_status = false
    if ($("html").is("[dir]")) {
      rtl_status = true
    }

    // Carousel Mode.

    if ($(".bkbm-carousel").length) {
      var $bkbm_carousel = $(".bkbm-carousel")

      if ($bkbm_carousel.find(".row").length) {
        $bkbm_carousel.find(".row").removeAttr("class").find('[class^="col-sm-"]').unwrap()
      } else {
        $bkbm_carousel.find(".grid-pad").removeAttr("class").find('[class^="bkbcol-"]').unwrap()
      }

      var $bkbm_carousel = $bkbm_carousel.addClass("bwl-kb")

      $bkbm_carousel.each(function () {
        var $this = $(this)

        var items_val = 2,
          nav_val = true,
          dots_val = true,
          autoplay_val = true,
          autoplaytimeout_val = 5000
        // Status.
        if ($this.attr("data-carousel") && $this.data("carousel") !== 1) {
          $this.removeClass("owl-carousel")
          return ""
        }
        // no of items
        if ($this.attr("data-items") && !isNaN($this.data("items"))) {
          items_val = $this.data("items")
        }
        // navigation status.
        if ($this.attr("data-nav") && !isNaN($this.data("nav"))) {
          nav_val = $this.data("nav")
        }

        // navigation status.
        if ($this.attr("data-dots") && !isNaN($this.data("dots"))) {
          dots_val = $this.data("dots")
        }
        // Autoplay status.
        if ($this.attr("data-autoplay") && !isNaN($this.data("autoplay"))) {
          autoplay_val = $this.data("autoplay")
        }
        // Autoplay status.
        if ($this.attr("data-autoplaytimeout") && !isNaN($this.data("autoplaytimeout"))) {
          autoplaytimeout_val = $this.data("autoplaytimeout")
        }

        $this.owlCarousel({
          rtl: rtl_status,
          items: items_val,
          loop: true,
          autoplay: autoplay_val,
          autoplayTimeout: autoplaytimeout_val,
          autoplayHoverPause: true,
          dots: dots_val,
          nav: nav_val,
          navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
          responsive: {
            0: {
              items: 1,
              nav: false,
              dots: false,
            },
            600: {
              items: 1,
              nav: false,
            },
            1000: {
              items: items_val,
            },
          },
        })
      })
    }

    // Remove Unncessary arrow from breadcrumb. Added Version 1.1.8

    if ($(".bkbm-breadcrumbs").length > 0) {
      var $bkbm_breadcrumbs = $(".bkbm-breadcrumbs")

      $bkbm_breadcrumbs.find("a").each(function () {
        if ($(this).attr("href") == "") {
          $(this).prev("span.fa:first").remove()
          $(this).remove()
        }
      })
    }

    // Function added in version 1.1.8 for nested category and tag widget support.

    if ($(".bkb-nested-category-list").length > 0) {
      $(".bkb-nested-category-list").each(function () {
        var $bkb_nested_category_list = $(this),
          show_icon = $bkb_nested_category_list.data("show_icon"),
          show_count = $bkb_nested_category_list.data("show_count")

        $bkb_nested_category_list.find("li").each(function () {
          var $link_item = $(this).find("a:first")

          $link_item.html('<i class="fa fa-home"></i> ' + $link_item.text())
        })

        var $bkb_icon_data = $bkb_nested_category_list.data("bkb_category_icon")

        var $bkb_icon_data_array = $bkb_icon_data.split("@")

        if ($bkb_icon_data_array.length > 0) {
          for (var i = 0; i < $bkb_icon_data_array.length; i++) {
            var $exploded_data = $bkb_icon_data_array[i].split("|"),
              $cat_item_class = $exploded_data[0],
              $cat_item_icon = $exploded_data[1]

            // Icon Section.

            if (show_icon == 1) {
              if ($cat_item_icon.search("fa") == "-1") {
                $("." + $cat_item_class)
                  .find("i:first")
                  .after('<img src="' + $cat_item_icon + '" class="bkb_taxonomy_img_lists">')
                  .remove("i")
              } else {
                $("." + $cat_item_class)
                  .find("i:first")
                  .attr("class", $cat_item_icon)
              }
            } else {
              $("." + $cat_item_class)
                .find("i:first")
                .remove()
            }
          }
        }
      })
    }

    /*---- Accordion : Since @ 1.1.0 ----*/

    if ($(".smk_accordion").length > 0) {
      $(".smk_accordion").each(function () {
        $(this).smk_Accordion({
          closeAble: true, //boolean
        })
      })
    }

    /*----  Share Button :Since @ 1.1.4----*/

    if ($(".bkb_share").length > 0) {
      $(".bkb_share").tipsy({ fade: true, gravity: "s" })

      $(".bkb_share").on("click", function () {
        var bkb_share_btn = window.open($(this).prop("href"), "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600")
        if (window.focus) {
          bkb_share_btn.focus()
        }
        return false
      })
    }

    /*----  Tipsy & Voting Section : Since @ 1.0.0----*/

    var vote_status, stat_cnt, post_id, bkb_unique_id

    if (typeof bkb_tipsy_status != "undefined" && bkb_tipsy_status == 1) {
      // Initalized Tipsy
      $(".btn_like").tipsy({ fade: true, gravity: "s" })
      $(".btn_dislike").tipsy({ fade: true, gravity: "n" })
    }

    $(document).on("click", ".bkb_container .btn_like", function () {
      vote_status = $(this).attr("vote_status")
      post_id = $(this).attr("post_id")
      bkb_unique_id = $(this).attr("bkb_unique_id")
      stat_cnt = $("#stat-cnt-" + bkb_unique_id)

      if (typeof bkb_tipsy_status != "undefined" && bkb_tipsy_status == 1) {
        // Hide Tipsy
        $("#bkb_btn_container_" + bkb_unique_id)
          .find(".btn_like")
          .tipsy("hide")
        $("#bkb_btn_container_" + bkb_unique_id)
          .find(".btn_dislike")
          .tipsy("hide")
      }

      $("#bkb_btn_container_" + bkb_unique_id).html('<div class="msg_container">' + bkb_wait_msg + "</div>")
      bkb_count_vote(vote_status, post_id, bkb_unique_id)
    })

    $(document).on("click", ".bkb_container .btn_dislike", function () {
      vote_status = $(this).attr("vote_status")
      post_id = $(this).attr("post_id")
      bkb_unique_id = $(this).attr("bkb_unique_id")
      stat_cnt = $("#stat-cnt-" + bkb_unique_id)

      if (typeof bkb_tipsy_status != "undefined" && bkb_tipsy_status == 1) {
        // Hide Tipsy
        $("#bkb_btn_container_" + bkb_unique_id)
          .find(".btn_like")
          .tipsy("hide")
        $("#bkb_btn_container_" + bkb_unique_id)
          .find(".btn_dislike")
          .tipsy("hide")
      }

      $("#bkb_btn_container_" + bkb_unique_id).html('<div class="msg_container">' + bkb_wait_msg + "</div>")
      bkb_count_vote(vote_status, post_id, bkb_unique_id)
    })

    function bkb_count_vote(vote_status, post_id, bkb_unique_id) {
      $.ajax({
        url: ajaxurl,
        type: "POST",
        dataType: "JSON",
        data: {
          action: "bkb_add_rating", // action will be the function name
          count_vote: true,
          post_id: post_id,
          vote_status: vote_status,
        },
        success: function (data) {
          var msg_icon = '<span class="fa fa-info-circle"></span>'

          if (data.status == 1) {
            stat_cnt.find(".total-vote-counter span").html(data.total_vote_counter)
            stat_cnt.find(".like-count-container span").html(data.like_vote_counter)
            stat_cnt.find(".dislike-count-container span").html(data.dislike_vote_counter)

            stat_cnt.find(".like_percentage").attr("style", "width:" + data.like_percentage + "%")
            stat_cnt.find(".dislike_percentage").attr("style", "width:" + data.dislike_percentage + "%")
          }

          if (vote_status == 0 && data.status == 1 && bkb_disable_feedback_status == 0) {
            $("#bkb_feedback_form_" + bkb_unique_id).slideDown("slow", function () {
              var form_field_container = $("#bkb_feedback_form_" + bkb_unique_id + " .bkb_feedback_form"),
                feedback_message_box = form_field_container.find(".feedback_message_box"),
                captcha = form_field_container.find("#captcha"),
                all_fields = $([]).add(feedback_message_box).add(captcha)

              all_fields.removeAttr("disabled").removeClass("bkb_feedback_disabled_field").val("")

              form_field_container.find("input[type=submit]").removeAttr("disabled")
            })
          }

          $("#bkb_btn_container_" + bkb_unique_id).html('<div class="msg_container"> ' + msg_icon + " " + data.msg + "</div>")
        },
        error: function (xhr, textStatus, e) {
          alert("There was an error saving the update.")
          return
        },
      })
    }

    /*---- Form Submission  ----*/

    function randomNum(maxNum) {
      return Math.floor(Math.random() * maxNum + 1) //return a number between 1 - 10
    }

    $(".bkb_feedback_form")
      .find("input[type=submit]")
      .on("click", function () {
        var form_submit_button = $(this),
          bkb_feedback_form_id = form_submit_button.attr("bkb_feedback_form_id"),
          form_box_container = $("#" + bkb_feedback_form_id),
          form_field_container = $("#" + bkb_feedback_form_id + " .bkb_feedback_form")

        var bwl_pro_form_error_message_box = form_box_container.find(".bwl_pro_form_error_message_box"),
          feedback_message_box = form_field_container.find(".feedback_message_box"),
          captcha_status = form_field_container.find("#captcha_status")

        if (captcha_status.val() == 1) {
          var num1 = form_field_container.find("#num1")
          var num2 = form_field_container.find("#num2")
          var captcha = form_field_container.find("#captcha")
          var all_fields = $([]).add(feedback_message_box).add(captcha)
        } else {
          var all_fields = $([]).add(feedback_message_box)
        }

        var bValid = true,
          required_field_msg = "",
          ok_border = "border: 1px solid #EEEEEE",
          error_border = "border: 1px solid #E63F37"

        if ($.trim(feedback_message_box.val()).length < 3) {
          feedback_message_bValid = false
          feedback_message_box.attr("style", error_border)
          required_field_msg += " " + err_feedback_msg + "<br />"
        } else {
          feedback_message_bValid = true
          feedback_message_box.attr("style", ok_border)
          required_field_msg += ""
        }

        bValid = bValid && feedback_message_bValid

        if (captcha_status.val() == 1) {
          if (parseInt($.trim(num1.val())) + parseInt($.trim(num2.val())) != parseInt($.trim(captcha.val()))) {
            captcha_bValid = false
            captcha.attr("style", error_border)
            required_field_msg += " " + err_bkb_captcha
          } else {
            captcha_bValid = true
            captcha.attr("style", ok_border)
            required_field_msg += ""
          }

          bValid = bValid && captcha_bValid
        }

        //Alert Message Box For Required Fields.

        if (bValid == false) {
          bwl_pro_form_error_message_box.html("").addClass("bwl-form-error-box").html(required_field_msg).slideDown("slow")
        }

        if (bValid == true) {
          all_fields.attr("style", ok_border)
          all_fields.addClass("bkb_feedback_disabled_field").attr("disabled", "disabled")
          form_submit_button.addClass("bkb_feedback_disabled_field").attr("disabled", "disabled")
          bwl_pro_form_error_message_box.html("").removeClass("bwl-form-error-box").addClass("bwl-form-wait-box").html(bkb_wait_msg).slideDown("slow")

          $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: "JSON",
            data: {
              action: "bkb_save_feedback_data", // action will be the function name,
              feedback_message_box: feedback_message_box.val(),
              post_id: form_submit_button.attr("post_id"),
              post_type: form_field_container.find("#post_type").val(),
              name_of_nonce_field: form_field_container.find("#name_of_nonce_field").val(),
            },
            success: function (data) {
              if (data.bkb_feedback_status == 1) {
                all_fields.val("")
                all_fields.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")
                form_submit_button.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")

                //Reload For New Number.

                if (captcha_status.val() == 1) {
                  num1.val(randomNum(5))
                  num2.val(randomNum(9))
                }

                bwl_pro_form_error_message_box
                  .removeClass("bwl-form-wait-box")
                  .html("")
                  .html(bkb_feedback_thanks_msg)
                  .addClass("bwl-form-success-box")
                  .delay(3000)
                  .slideUp("slow", function () {
                    $("#bkb_feedback_form_" + post_id).slideUp("slow", function () {
                      $(this).remove()
                    })
                  })
              } else {
                bwl_pro_form_error_message_box.removeClass("bwl-form-wait-box").html("").html(bkb_unable_feedback_msg).addClass("bwl-form-error-box").delay(3000).slideUp("slow")
                all_fields.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")
                form_submit_button.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")
              }
            },
            error: function (xhr, textStatus, e) {
              bwl_pro_form_error_message_box.removeClass("bwl-form-wait-box").html("").html(bkb_unable_feedback_msg).addClass("bwl-form-error-box").delay(3000).slideUp("slow")
              all_fields.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")
              form_submit_button.removeAttr("disabled").removeClass("bkb_feedback_disabled_field")
              return
            },
          })
        }

        return false
      })

    /*----Sticky Tab Options ----*/

    var bkb_sticky_container = $(".bkb-sticky-container")

    var bkb_handle_sticky_container_height = function () {
      var bkb_height = $(window).height()

      if (bkb_height / 2 < 100) {
        bkb_sticky_container.css({
          opacity: 0,
        })
      } else {
        bkb_sticky_container.css({
          top: bkb_height / 2 - 50,
          opacity: 1,
        })

        setTimeout(function () {
          bkb_sticky_container.fadeIn(1000)
        }, 1500)
      }
    }

    $(window).resize(function () {
      bkb_handle_sticky_container_height()
    })

    bkb_handle_sticky_container_height()

    /*---- Modal Action ----*/

    var bkb_search_popup = $(".bkb_search_popup, #bkb_search_popup")

    if (bkb_search_popup.length == 1) {
      var bkb_search_modal = $("[data-remodal-id=bkb_search_modal]").remodal()
      $(document).on("click", "#bkb_search_popup, .bkb_search_popup", function () {
        bkb_search_modal.open()
      })
    }

    var bkb_ask_ques_popup = $("#bkb_ask_ques_popup")

    if (bkb_ask_ques_popup.length > 0 || $(".bkb_ask_ques").length > 0) {
      var bkb_ask_ques_modal = $("[data-remodal-id=bkb_ask_ques_modal]").remodal()

      $(document).on("click", "#bkb_ask_ques_popup, .bkb_ask_ques_popup, .bkb_ask_ques", function () {
        bkb_ask_ques_modal.open()

        return false
      })
    }

    /*----  Inline Question Modal ----*/

    if ($(".bkb_inline_ques_btn").length > 0) {
      var bkb_inline_ask_ques_modal = $("[data-remodal-id=bkb_inline_ask_ques_modal]").remodal()

      $(document).on("click", ".bkb_inline_ques_btn", function () {
        bkb_inline_ask_ques_modal.open()

        return false
      })
    }

    /*--  Nested Category Buttons --*/

    if ($(".bkb-nested-category-list").length > 0) {
      var $bkb_nested_category_list = $(".bkb-nested-category-list")

      var $show_count = $bkb_nested_category_list.data("show_count")

      $bkb_nested_category_list.find(".children").each(function () {
        var $this = $(this)

        if ($this.parent("li").is("[class*='current-cat']")) {
          $this.removeClass("bkbm-dn")
        } else {
          $this.addClass("bkbm-dn")
        }

        var $bkb_set_arrow_class = "set-an-arrow"

        if ($show_count == 0) {
          $bkb_set_arrow_class = "set-an-arrow bkb-hide-count"
        }

        $this
          .parent("li")
          .find("a:first")
          .after('<span class="' + $bkb_set_arrow_class + '"></span>')
      })

      $(".set-an-arrow").on("click", function () {
        $(this).nextAll(".children").first().toggleClass("bkbm-dn")
      })
    }
  })
})(jQuery)
