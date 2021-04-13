jQuery(function($) {
    $('body').on('change', '#cmt_attach', function() {
        $this = $(this);
        file_data = $(this).prop('files')[0];
        form_data = new FormData();
        form_data.append('cmt_attach', file_data);
        form_data.append('action', 'file_upload');
        $.ajax({
            url: aw.ajaxurl,
            type: 'POST',
            contentType: false,
            processData: false,
            data: form_data,
            beforeSend : function ( xhr ) {
                $('.cmt_att_img').text('Loading...'); // preloader here
            },
            success: function (response) {
                //$this.after(response);
                //alert('File uploaded successfully.');
                $('.cmt_att_img').html(response);
                $('.rmv_attach').show();

            }
        });
        $(document).on('click','.rmv_attach',function(){
            $('#cmt_img_path').val('');
            $('.cmt_img').remove();
            $('.rmv_attach').hide();
            //$(this).closest('#cmt_img_path').attr('value', '');
            //alert("hello");
        });
    });    
});