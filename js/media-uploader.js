(function( $ ) {
  'use strict';

  $(function() {
    
    $('.upload_image').click(open_custom_media_window);
    $('.remove_image').click(remove_uploaded_image);
    function remove_uploaded_image(){
      $(this).closest('.upload_file_data').find('.attachment_id').val("");
      $(this).closest('.upload_file_data').find('.image_path').val("");
      $(this).parent().empty();
      return false;
    }
    function open_custom_media_window() {
      if (this.window === undefined) {
        this.window = wp.media({
          title: 'Insert Image',
          library: {type: 'image'},
          multiple: false,
          button: {text: 'Insert Image'}
        });

        var self = this;
        this.window.on('select', function() {
          var response = self.window.state().get('selection').first().toJSON();

          $(self).closest('.upload_file_data').find('.attachment_id').val(response.id);
          $(self).closest('.upload_file_data').find('.image_path').val(response.sizes.full.url);
          //$('.image').attr('src', response.sizes.full.url);
          //$('.image').show();
          $(self).closest('.upload_file_data').children('.show_upload_preview').empty();
          $(self).closest('.upload_file_data').children('.show_upload_preview').prepend('<img src="'+response.sizes.full.url+'" width="260px" height="160px"/>');
          $(self).closest('.upload_file_data').children('.show_upload_preview').append('<input type="button" name="remove" value="Remove Image" class="button-primary remove_image"/>');
          $(self).closest('.upload_file_data').children('.show_upload_preview').show();
          $('.remove_image').click(remove_uploaded_image);
        });
      }

      this.window.open();
      return false;
    }
  });
})( jQuery );