    $(function() {
        $('body').on('click', '#savePatientHistory', function () {
            $.ajax({
                'dataType': 'json',
                'type': 'POST',
                'success': function (data) {
                    $("#AjaxLoaderTeeth").hide();

                    if (data.status == "success") {
                        $("#result_of_saving").html("");
						
                        $("#patient_history_id").val(data.patient_history_id);
                        $.each(data.successAddedServices, function (index, val) {
                            var add_id = index.split("_");
                            $("#service_key_" + add_id["2"]).val(val).attr("value", val)
                        });
                        $.each(data.successDeletedServices, function (index, value) {
                            var delete_id = value.split("_");
                            $("#service_id_" + delete_id["2"]).val("0").attr("value", "0");
                            $("#service_price_" + delete_id["2"] + ", #total_price_" + delete_id["2"]).text("0");
                            $("#parameters_" + delete_id["2"]).hide();
                        });
                        updateTotalPrice();
						
						if(data.disable_services == 1){
							disable_services();
						}						
						
						$(".make_payment").show();
						$(".current_balance").html(data.patient_balance);
						
                        $("#result_of_saving").attr("class", "goodNotice");
                        $("#result_of_saving").html("Данные успешно сохранены.<br>");
                    } else {
                        $("#result_of_saving").show();
                        $("#result_of_saving").attr("class", "errorNotice");
                        $("#result_of_saving").html("В заполненных данных есть ошибки.<br>");
                        $.each(data, function (ind, value) {
                            $("#result_of_saving").append("<br> " + value);
                        });
                    }
                },
                'beforeSend': function () {
                    $("#AjaxLoaderTeeth").show();
                },
                'url': '/patient/history/SavePatientHistory',
                'cache': false,
                'data': $(this).parents("form").serialize(),
            });
            return false;
        });
    });
	