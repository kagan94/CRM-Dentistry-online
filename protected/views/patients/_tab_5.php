<?php  // Вкладка с денежными операциями ?> 

<div id="patient_balance">
  Текущий баланс: <div class="current_balance"><?php echo PatientBalance::getCurrentBalance($patient_id); ?></div>

  <br><br>

  <table border="1" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <td>Дата</td>
        <td>Визит</td>
        <td>Тип операции</td>
        <td>Сумма, грн.</td>
        <td>Комментарий</td>
        <td>Действия</td>
      </tr>
    </thead>

    <tbody>
  <?php 
  $count = 1;
  if($transactions){
    foreach ($transactions as $transaction) {  ?>

      <tr id="transaction_<?php echo $count; ?>">
        <td class="date_visit">
          <input type="hidden" name="transaction_id" value="<?php echo $transaction->id; ?>">
          <?php echo $transaction->date; ?>
        </td>
        <td class="visit"><a href="<?php echo $this->createUrl('patient/history/update/id/'.$transaction->patient_history_id); ?>" target="_blank">Визит (<?php echo PatientBalance::getPrettyDate($transaction->patientHistory->datetime_visit); ?>)</a></td>
        <td><?php echo PatientBalance::getStatusOperation($transaction->operation); ?></td>
        <td class="sum"><?php echo PatientBalance::getSumOperation($transaction->sum, $transaction->operation); ?></td>
        <td class="comment"><?php echo $transaction->comment; ?></td>
        <td class="actions">
          <?php if($transaction->operation != 2) { ?>
            <?php if(User::checkPermissionEditTransactions()){ // Замораживаем если юзер не админ. ?>
            <a href="#" onclick="updateTransaction(<?php echo $count; ?>); return false;" class="update_button">
              <img src="/images/admin/update.png" alt="Редактировать">
            </a>
            <a href="#" onclick="saveTransaction(<?php echo $count; ?>); return false;" class="save_button" style="display: none;">
              <img src="/images/admin/save.png" alt="Сохранить">
            </a>
            <a href="#" onclick="deleteTransaction(<?php echo $count; ?>); return false;" class="delete_button">
              <img src="/images/admin/delete.png" alt="Удалить">
            </a>
            <a href="#" onclick="restoreTransaction(<?php echo $count; ?>); return false;" class="cancel_button" style="display: none;">
              <img src="/images/admin/cancel.png" alt="Отменить">
            </a>
            <?php } ?>
          <?php } ?>
        </td>
      </tr>
  <?php   $count++; ?>
  <?php }
  } else { ?>
      <tr id="no_transactions">
        <td colspan='6'><center><b>Транзакций не найдено.</b></center></td>
      </tr>
  <?php } ?>
    </tbody>
  </table>

  <div class="make_payment">
    Внести оплату
  </div>
  <div class="clearfix"></div>
</div>

<div id="patient_visits">
  <?php echo Patients::getSelectListPatientVisits($patient_id); ?>
</div>

<script type="text/javascript">
  // Считаем номер для новой строки
var number_transaction = <?php echo $count; ?>;

    $('.make_payment').click(function(event){
      // Проверяем, были ли транзакции

      if($('#no_transactions').length>0){
        $('#no_transactions').remove();
      }

      html ='<tr id="transaction_' + number_transaction + '">';
      html+=' <td class="date_visit">';
      html+='  <input type="hidden" name="transaction_id" value="">';
      html+='  <input type="text" name="date_visit" maxlength="10">';
      html+=' </td>';
      html+=' <td class="visit">';
      html+=    $('#patient_visits').html();
      html+=' </td>';
      html+=' <td><div class="debit">Поступление от пациента</div></td>';
      html+=' <td class="sum"><input type="text" name="sum"></td>';
      html+=' <td class="comment"><textarea maxlength="250" name="comment" placeholder="Комментарий к оплате..."></textarea></td>';
      html+=' <td class="actions">';

      <?php if(User::checkPermissionEditTransactions()){ // Замораживаем если юзер не админ. ?>
      html+='  <a href="#" onclick="updateTransaction(' + number_transaction + '); return false;" class="update_button" style="display: none;">';
      html+='   <img src="/images/admin/update.png" alt="Редактировать">';
      html+='  </a>';
      <?php } ?>

      html+='  <a href="#" onclick="saveTransaction(' + number_transaction + '); return false;" class="save_button">';
      html+='   <img src="/images/admin/save.png" alt="Сохранить">';
      html+='  </a>';
      
      <?php if(User::checkPermissionEditTransactions()){ // Замораживаем если юзер не админ. ?>
      html+='  <a href="#" onclick="deleteTransaction(' + number_transaction + '); return false;" class="delete_button">';
      html+='   <img src="/images/admin/delete.png" alt="Удалить">';
      html+='  </a>';

      html+='  <a href="#" onclick="restoreTransaction(' + number_transaction + '); return false;" class="cancel_button" style="display: none;">';
      html+='   <img src="/images/admin/cancel.png" alt="Отменить">';
      html+='  </a>';
      <?php } ?>

      html+=' </td>';
      html+='</tr>';

      $("#patient_balance table tbody").append(html);

      initializeTimepicker();
      number_transaction++;
    });


    function saveTransaction(row_id){
      var errors = 0,
        $selector = $('#transaction_' + row_id),
        $date_visit = $selector.find('input[name="date_visit"]').val(),
        $sum = $selector.find('input[name="sum"]'),
        $sum_val = $sum.val(),
        $visit = $selector.find('select[name="visit"]').val(),
        $comment = $selector.find('textarea[name="comment"]').val(),
        transaction_id = $selector.find('input[name="transaction_id"]').val();

      if($sum_val <= 0 || isNaN($sum_val) ) {
        alert('Введите правильную сумму оплаты!');
        errors+=1;
      }

      if($visit == '') {
        alert('Выберите визит!');
        errors+=1;
      }

      if($date_visit == '') {
        alert('Выберите дату визита!');
        errors+=1;
      }

      if(errors == 0){
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {
              transaction_id: transaction_id,
              patient_id: <?php echo $patient_id; ?>,
              date_visit: $date_visit,
              patient_history_id: $visit,
              sum: $sum_val,
              comment: $comment,
          },
          url: '/action/transactions/update',
          cache: false,
          success: function(data){
            if(data.status == "success"){
              // $selector.remove();
              $selector.find('.date_visit').html('<input type="hidden" name="transaction_id" value="' + data.transaction_id + '">' + data.date_visit);
              $selector.find('.visit').html('<a href="<?php echo $this->createUrl("patient/history/update/id");?>/' + data.visit + '" target="_blank">Визит (' + data.datetime_visit + ')</a>');
              $selector.find('.sum').html(data.sum_view);
              $selector.find('.comment').html(data.comment);

              $('.current_balance').html(data.patient_balance);
            } else {
              $.each(data.errors, function(key, val) {
                alert(val);                                                    
              });
            }
          },
        });

        $selector.find('.update_button, .delete_button').show(500);
        $selector.find('.save_button, .cancel_button').hide(500);
      }
    }
    
    <?php if(User::checkPermissionEditTransactions()){ // Замораживаем если юзер не админ. ?>
      function restoreTransaction(row_id){
        var $selector = $('#transaction_' + row_id),
            val_transaction_id = $selector.find('input[name="transaction_id"]').val();

          $.ajax({
              type: 'POST',
              dataType: 'json',
              data: {
                  transaction_id: val_transaction_id,
              },
              url: '/action/transactions/getinfo',
              cache: false,
              success: function(data){
                if(data.status == "success"){
                  $selector.find('.date_visit').html('<input type="hidden" name="transaction_id" value="' + data.transaction_id + '">' + data.date_visit);
                  $selector.find('.visit').html('<a href="<?php echo $this->createUrl("patient/history/update/id");?>/' + data.visit + '" target="_blank">Визит (' + data.datetime_visit + ')</a>');
                  $selector.find('.sum').html(data.sum);
                  $selector.find('.comment').html(data.comment);
                } else {
                  console.log(data.errors);
                }
              },
          });

        $selector.find('.save_button, .cancel_button').hide(500);
        $selector.find('.update_button, .delete_button').show(500);
      }

      function updateTransaction(row_id){
        var $selector = $('#transaction_' + row_id),
            val_transaction_id = $selector.find('input[name="transaction_id"]').val();

          $.ajax({
              type: 'POST',
              dataType: 'json',
              data: {
                  transaction_id: val_transaction_id,
              },
              url: '/action/transactions/getinfo',
              cache: false,
              success: function(data){
                if(data.status == "success"){

                  data_visit_html=  '<input type="hidden" name="transaction_id" value="' + data.transaction_id + '">';
                  data_visit_html+= '<input type="text" name="date_visit" value="' + data.date_visit + '" maxlength="10">';
                  $selector.find('.date_visit').html(data_visit_html);
                  $selector.find('.visit').html(data.visit_select);
                  $selector.find('.sum').html('<input type="text" name="sum" value="' + data.sum + '">');
                  $selector.find('.comment').html('<textarea maxlength="250" name="comment" placeholder="Комментарий к оплате...">' + data.comment + '</textarea>');
                  
                  initializeTimepicker();
                } else {
                  console.log(data.errors);
                }
              },
          });

        $selector.find('.save_button, .cancel_button').show(500);
        $selector.find('.update_button, .delete_button').hide(500);
      }

      function deleteTransaction(row_id){
        var $selector = $('#transaction_' + row_id),
        val_transaction_id = $selector.find('input[name="transaction_id"]').val();

        if (confirm('Вы уверенны, что хотите удалить транзакцию?')){
          if(val_transaction_id.length){
            
            // AJAX-запрос на удаление
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {
                    transaction_id: val_transaction_id,
                    patient_id: <?php echo $patient_id; ?>,
                },
                url: '/action/transactions/delete',
                cache: false,
                success: function(data){
                  if(data.status == "success"){
                    $selector.remove();
                    $('.current_balance').html(data.patient_balance);
                  } else {
                    console.log(data.errors);
                  }
                },
            });
          } else {
            $selector.remove();
          }
        }
      }
    <?php } ?>
    
    function initializeTimepicker(){
      $( "input[name=\"date_visit\"]" ).datetimepicker({
       lang:'ru',
       format:'Y-m-d',
       timepicker:false,
       scrollMonth:false,
       scrollInput:false,
      });
    }
</script>
