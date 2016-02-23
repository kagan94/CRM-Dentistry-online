
	function updateTotalPrice(){
		sum_up = 0;

		$(".total_price").each(function(){
			if (parseInt($(this).text(),10) > 0) {
				sum_up = sum_up + parseInt($(this).text(),10);
			}
		});
		
		discount_type = parseInt( $('#discount :selected').attr('discount-type') );
		discount_size = parseInt( $('#discount :selected').attr('discount-size') );
		discount_amount = 0;
		
		if (discount_type > 0 && discount_size > 0) {

			if(discount_type == 1){   /* Скидка в грн.  */
				if((sum_up - discount_size) > 0){
					discount_amount = discount_size;
				}
			} else if(discount_type == 2){   /* Скидка в %  */
				if((sum_up * (1 - (discount_size/100) ) ) > 0){
					discount_amount = sum_up * (discount_size/100);
				}
			}
		} 
		
		$('#discount_amount').text(parseInt(discount_amount, 10) + " грн.");
		$('#sum_up').text(sum_up + " грн.");
		$('#sum_up_total').text(parseInt((parseInt(sum_up, 10) - parseInt(discount_amount, 10)), 10) + " грн.");
	}


	function updatePrice(id){
		$('#service_id_'+id+', #service_quantity_'+id).bind('change', function() {

	    	quantity = $('#service_quantity_'+id).val();
	    	serv_price = $('#service_id_'+ id + ' :selected').attr('data-price');
    		arr_check =[];

			// $(".checkSimilar").bind('change', function() {
			// 		$(".checkSimilar").each(function(indx, element){
			// 		if($('#service_id_'+indx).val() != 0 && indx != $(this).val() ){
			// 			arr_check.push($('#service_id_'+indx).val());
			// 		}
			// 	});

			// 	if(jQuery.inArray($(this).val(),arr_check)){
			// 		alert('ОШИБКА');
			// 	}
			// });

			if (parseInt($(this).text(),10) > 0) {
				sum_up = sum_up + parseInt($(this).text(),10);
			}

	    	if(quantity > 0 && serv_price > 0){
	    		$('#service_price_'+id).text(serv_price);
				$('#total_price_'+id).text(serv_price*quantity);

	    	} else {
	    		$('#service_price_'+id).text('0');
	    		$('#total_price_'+id).text('0');
	    	}

	    	updateTotalPrice();
		});
	}


    function deleteRow(btn_id){
        var del_id = btn_id.split('_');
        $('#parameters_'+del_id[1]).hide();
        $('#service_id_'+del_id[1]+', #service_quantity_'+del_id[1]+', service_price_'+del_id[1]).val('0').attr('value','0');
        $('#total_price_'+del_id[1]).text('0');
    	updateTotalPrice();
    }

