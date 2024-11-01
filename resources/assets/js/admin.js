jQuery(function ($) {
  if ($('body').hasClass('taxonomy-post_series')) {
    $('.series-list').sortable({
      update: function () {
        let posts_ids_sorted = $(this).sortable('toArray');
        $('input[name="posts_ids"]').val(posts_ids_sorted);
      },
    });
  }
});
