$(document).ready(function() {
console.log('sadasd');
  $('body').on('click', '.remove--gb', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    var section = $(this).attr('data-section');

    $.ajax({
      url: '/sections/' + section + '/gb/' + id,
      type: 'DELETE',
      dataType: 'json',
      data : { _token: $('meta[name="_token"]').attr('content')},
      success: function(data) {
        if (data.success) {
          $("#gb--item-" + id).remove();
          messageSuccess(data.success);
        } else {
          messageError(data.errors);
        }
      }
    });
  });

});
