;(function ($) {
  // Default settings.
  var defaults = {
    ulClass: null,
    appendAfterSelector: "h1",
    H_MIN: 1,
    H_MAX: 6,
  }

  var opt = null

  jQuery.fn.tableOfContents = function (options) {
    opt = $.extend(true, defaults, options)

    var H_MIN = $(opt.appendAfterSelector).data("bkb_min_heading_tag")
    var H_MAX = opt.H_MAX

    var h = H_MIN

    var counter = 1

    var out = "<ul"
    if (opt.ulClass) {
      out += ' class="' + opt.ulClass + '"'
    }
    out += ">"

    var out = '<div id="bkbm_top" class="bkb_toc_wrapper"><p class="bkb-toc-title">' + $(opt.appendAfterSelector).data("bkb_toc_title") + "</p><ol>"

    function buildListItemsAndAnchors(headers, headerNum) {
      if (headerNum > H_MAX) {
        return
      }

      $(opt.appendAfterSelector)
        .find(headers)
        .each(function () {
          var self = $(this)

          self.before('<a name="' + counter + '"></a>')

          self.addClass("bkb-toc-heading")

          var hnext = self.nextUntil("h" + headerNum, "h" + (headerNum + 1))
          var hnextLength = hnext.size()

          out += "<li>"
          out += '<a class="jqtoc-link" href="#' + counter + '">' + self.text() + "</a>"

          counter++

          if (hnextLength) {
            out += "<ol>"
            buildListItemsAndAnchors(hnext, headerNum + 1)
            out += "</ol>"
          }
          out += "</li>"
        })
    }

    buildListItemsAndAnchors(this.find("h" + h), h)

    out += "</ol></div>"

    if (counter < $(opt.appendAfterSelector).data("bkb_toc_min_tag")) {
      return ""
    }

    if ($(".bkb-toc-widget").length == 0) {
      $(opt.appendAfterSelector).find(".bkb-toc-heading").append(' &nbsp; <a href="#bkbm_top" class="bkb-toc-top"><i class="fa fa-angle-up"></i></a>')
    }

    var appendAfter = $(opt.appendAfterSelector)

    if (appendAfter) {
      appendAfter.prepend($(out))
    }

    $(document).on("click", ".jqtoc-link", function () {
      // New Code Start.

      var $bkb_toc_widget = $(".bkb-toc-widget").find("a")
      $bkb_toc_widget.removeClass("active-toc-link")
      $(this).addClass("active-toc-link")

      // End New Code.

      var anchor = $(this).attr("href").replace("#", "")
      var $target_heading = $("a[name=" + anchor + "]").next()
      var additional_offset = $(".bkb-toc-heading").outerHeight()

      if ($("#wpadminbar").length) {
        additional_offset = additional_offset + parseInt($("#wpadminbar").outerHeight(), 0)
      }

      $("html, body").animate(
        {
          scrollTop: $target_heading.offset().top - parseInt(bkb_toc_offset, 0) - parseInt(additional_offset, 0),
        },
        1000
      )

      return false
    })

    $(document).on("click", ".bkb-toc-top", function () {
      var target = $($(this).attr("href"))
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]")
      if (target.length) {
        $("html, body").animate(
          {
            scrollTop: target.offset().top - parseInt(bkb_toc_offset, 0),
          },
          1000
        )
      }

      return false
    })

    return this
  }
})(jQuery)
;(function ($) {
  "use strict"

  $("body").tableOfContents({
    ulClass: "bkb-toc-wrapper",
    appendAfterSelector: ".bkb-single-post",
  })

  if ($(".bkb-toc-widget").length > 0) {
    var $bkb_toc_widget = $(".bkb-toc-widget")
    var $bkb_toc_wrapper = $(".bkb-single-post").find(".bkb_toc_wrapper")

    var $bkb_toc = $bkb_toc_wrapper.html()

    $bkb_toc_wrapper.hide()

    $(".bkb-toc-widget").html($bkb_toc)

    $(window).resize(function () {
      var bkb_window_width = jQuery(window).width()

      if (bkb_window_width < 900) {
        $bkb_toc_wrapper.show()
        $bkb_toc_widget.hide()
      } else {
        $bkb_toc_wrapper.hide()
        $bkb_toc_widget.show()
      }
    })
  }

  // If table of content is activated and element of that content is null plugin will delete the widget.

  if ($(".BKB_Toc_Widget").length && $(".bkb-toc-widget").find("ol").length == 0) {
    $(".BKB_Toc_Widget").remove()
  }

  // Checking if toc wrapper is available or not.

  function bkbm_adjust_toc_sticky_layout() {
    var $bkb_toc_widget = $(".bkb-toc-widget")

    if ($bkb_toc_widget.length === 0) return 1

    var $bkb_toc_widget_parent = $bkb_toc_widget,
      $bkb_toc_widget_width = $bkb_toc_widget.width(),
      $bkb_toc_widget_height = $bkb_toc_widget.height()

    var top = $bkb_toc_widget_parent.offset().top,
      $total_hide_need_to_cross = parseInt(top, 0) + parseInt($bkb_toc_widget_height, 0)

    var $bkb_toc_widget_top_value = 0

    var $bkb_current_screen_size = $(window).width()

    if ($bkb_current_screen_size < 767) {
      $bkb_toc_widget_parent.removeClass("sticky-fixed").hide().removeAttr("style")
      $bkb_toc_widget.width($bkb_toc_widget.parent().width())
    }

    if ($("#wpadminbar").length) {
      $bkb_toc_widget_top_value = $("#wpadminbar").outerHeight()
    }

    $bkb_toc_widget.append('<span class="bkb_toc_close_btn"><i class="fa fa-close"></i></span>')

    $(document).on("click", ".bkb_toc_close_btn", function () {
      $bkb_toc_widget_parent.slideUp("slow", function () {
        $(this).addClass("hidden").removeClass("sticky-fixed").removeAttr("style")
      })
    })

    $(window).on("scroll", function (event) {
      var $bkb_scrolling_value = $(this).scrollTop()

      if ($bkb_scrolling_value >= $total_hide_need_to_cross && $bkb_current_screen_size > 767) {
        $bkb_toc_widget_parent.addClass("sticky-fixed").css({
          width: $bkb_toc_widget_width,
          top: $bkb_toc_widget_top_value,
        })

        $(".bkb_toc_close_btn").show()
      } else if ($bkb_scrolling_value == 0) {
        $bkb_toc_widget_parent.removeClass("sticky-fixed").hide().removeAttr("style").removeClass("hidden")
      } else {
        $bkb_toc_widget_parent.removeClass("sticky-fixed").hide().removeAttr("style")
        $bkb_toc_widget.width($bkb_toc_widget.parent().width())
        $(".bkb_toc_close_btn").hide()
      }
    })
  }

  if ($(".bkb_toc_wrapper").length > 0) {
    // Add TOC menu in right place.

    bkbm_adjust_toc_sticky_layout()

    // On resize screen plugin will place nav menu in right place.

    $(window).on("resize", function (event) {
      if ($(window).width > 767) {
        bkbm_adjust_toc_sticky_layout()
      }
    })

    // Color Nav  Links.

    $(".bkb-toc-heading").mouseenter(function () {
      var $bkb_toc_widget = $(".bkb-toc-widget").find("a")
      $bkb_toc_widget.removeClass("active-toc-link")

      $('a[href="#' + $(this).prev("a").attr("name") + '"]').addClass("active-toc-link")
    })
  }
})(jQuery)
