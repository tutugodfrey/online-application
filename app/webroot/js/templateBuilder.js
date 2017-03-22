$(document).ready(function(){
        $("#TemplateBuilderBaseCobrand").prepend("<option value=''>Select Base Cobrand</option>").val('');
        $('#TemplateBuilderBaseTemplate')[0].options.length = 0;
        $("#TemplateBuilderBaseTemplate").prepend("<option value=''>Select Base Template</option>").val('');

        $("#TemplateBuilderBaseCobrand").on('change', function() {
            $('#TemplateBuilderBaseTemplate')[0].options.length = 0;
            $("#TemplateBuilderBaseTemplate").prepend("<option value=''>Select Base Template</option>").val('');

            value = $("#TemplateBuilderBaseCobrand").val();
            if ($("#TemplateBuilderBaseCobrand option:selected").index() > 0){
                $.ajax({
                    url: "/cobrands/get_template_ids/"+value,
                    data: value,
                    success: function(response){
                        if (response.length != 0) {
                            response = $.parseJSON(response)
                            $.each(response, function(key, val) {
                                name = map[val];
                                $('#TemplateBuilderBaseTemplate').append('<option value="'+val+'">'+name+'</option>');
                            });
                        }
                    },
                    cache: false
                });
            }
        });
    });

    var checkCheckbox = function(arg) {
        $('#'+arg).prop('checked', true);
    };

    var checkAll = function() {   
        var checked = $("#check_all").is(":checked");
        var pattern = /^template_page.+/;

        $('input[type=checkbox]').each(function () {
            var id = $(this).attr('id');
            if (pattern.test(id)) {
                if (checked) {
                    $(this).prop('checked', true);
                }
                else {
                    $(this).prop('checked', false);
                }
            }
        });
    };
