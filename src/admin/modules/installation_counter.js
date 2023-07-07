;(function ($) {
  function bkbm_tpl_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "bkbm_tpl_installation_counter", // this is the name of our WP AJAX function that we'll set up next
        product_id: BkbmTplAdminData.product_id,
      },
      dataType: "JSON",
    })
  }

  if (typeof BkbmTplAdminData.installation != "undefined" && BkbmTplAdminData.installation != 1) {
    $.when(bkbm_tpl_installation_counter()).done(function (response_data) {
      console.log(response_data)
    })
  }
})(jQuery)
