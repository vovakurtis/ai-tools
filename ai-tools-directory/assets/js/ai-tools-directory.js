(function ($) {
  function fetchResults($form) {
    var data = $form.serialize();
    $.ajax({
      method: 'GET',
      url: aiToolsDirectory.ajaxUrl,
      data: data + '&action=ai_tools_directory_filter&nonce=' + aiToolsDirectory.nonce,
      beforeSend: function () {
        $form.closest('[data-directory]').addClass('is-loading');
      },
      success: function (response) {
        if (response.success) {
          $form.closest('[data-directory]').find('[data-directory-results]').html(response.data.markup);
        }
      },
      complete: function () {
        $form.closest('[data-directory]').removeClass('is-loading');
      }
    });
  }

  $(document).on('change', '[data-directory-filters] select', function () {
    fetchResults($(this).closest('form'));
  });

  $(document).on('click', '[data-ai-tool-save]', function () {
    var $button = $(this);
    $button.toggleClass('is-saved');
    $button.text($button.hasClass('is-saved') ? 'Saved' : 'Save');
  });
})(jQuery);
