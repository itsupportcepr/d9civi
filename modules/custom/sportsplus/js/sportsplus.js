(function ($) {

  $(document).ready(function () {
    $('.open-player-address-modal').click(function (e) {
      e.preventDefault();

      let player_id = $(this).data('player-id');

      $.ajax({
        url: `/sportsplus/player/address/${player_id}`,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
          console.log(data)
          Swal.fire({
            title: `<strong>${data.modal.title} <br/> #${data.modal.player_shirt_number}</strong>`,
            iconHtml: `<img src="${data.modal.player_image}" alt="" style="border-radius: 6rem;">`,
            html: data.modal.content,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText:
              '<i class="fa fa-thumbs-up"></i> Great!',
            confirmButtonAriaLabel: 'Thumbs up, great!',
          })
        },
      });
    });
  });
})($);
