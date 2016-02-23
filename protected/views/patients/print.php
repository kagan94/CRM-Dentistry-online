<style type="text/css">
h1{
    color: green;
    text-align: center;
}
h2 {
    text-align: center;
}
.date_review table {
    width: 1000px;
    border: 1px solid gray;
    margin: auto;
    text-align: center;
}
.date_review table td, .date_review table th {
    width: 35px;
    height:30px;
    border: 1px solid gray;
}
.date_review table tr td:nth-child(1) {
    width: 120px !important;
    border: 1px solid green;
    background: black;
}
.underline {
    float:left;
    display: inline-block;
    width: auto;
    border-bottom: 2px solid;
}
</style>


<table border="0" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td width="444" valign="top">
                <p>
                    Найменування міністерства, іншого органу виконавчої влади, підприємства, установи, організації, до сфери управління якого належить заклад
                    охорони здоров'я
                </p>
                <p>
                    _______________________________________<br>
                    _______________________________________
                </p>
                <p>
                    Найменування та місцезнаходження (повна поштова адреса) закладу охорони здоров'я, де заповнюється форма
                </p>
                 ________________________________________________
                
                <p>
                    Код за ЄДРПОУ |__|__|__|__|__|__|__|__|
                </p>
            </td>
            <td width="311" valign="top" border="1">
              
                <p align="center">
                    <strong>МЕДИЧНА ДОКУМЕНТАЦІЯ</strong>
                </p>
                <p align="center">
                    Форма первинної облікової документації
                    <br/>
                    <strong>N 043/о</strong>
                </p>
                <p align="center">
                    <strong>ЗАТВЕРДЖЕНО</strong>
                </p>
                <p align="center">
                    Наказ МОЗ України
                    <br/>
                    |__|__|__|__|__|__| <strong>N</strong> |__|__|__|__|
                </p>
            </td>
        </tr>
    </tbody>
</table>
<br><br>

<page_footer>
[[page_cu]]/[[page_nb]]
</page_footer>

<p align="center">
    <strong>
        Медична карта стоматологічного хворого N <?php echo $model->id; ?>
        <br/>
        <?= date('Y')?> рік
        <br/>
        <br/>
    </strong>
</p>
<p>
    1. Прізвище, ім'я, по батькові &nbsp; <div class="underline"> &nbsp; &nbsp; <?php echo $model->fio; ?> &nbsp; &nbsp; </div>
</p>
<p>
    2. Стать: &nbsp;
    <div>
        <?php 
            if($model->gender == 1){
                echo "<div class=\"underline\">чоловіча - 1</div>; &nbsp;  жіноча – 2";
            } else if($model->gender == 2){
                echo "чоловіча - 1;  &nbsp; <div class=\"underline\">жіноча – 2</div>";
            } else {
                echo "чоловіча - 1; жіноча – 2";
            }
        ?>
    </div>
    <br><br>
    3. Дата народження &nbsp;
        <div class="underline">
        <?php 
            if($model->date_birthday != "0000-00-00" AND $model->date_birthday != ""){
                echo $model->date_birthday;
            } else {
                echo $this->getBackspaces(40);
            }
        ?>
        </div>
          <!-- |__|__|__|__|__|__| --> (число, місяць, рік)
    <br><br>
    4. Місце проживання хворого, телефон &nbsp;
        <div class="underline">
        <?php 
            echo $this->getBackspaces(5);
            if($model->adress != "") echo $model->adress . " ";

            if($model->phone != "") echo $model->phone . " ";
            
            if($model->homephone != "") echo $model->homephone." ";
            echo $this->getBackspaces(5);
        ?>
        </div>
    ______________________________________________________________________________________________
</p>
<p>
    5. Діагноз 
    ______________________________________________________________________________________________
   </p>
<p>
    6. Скарги
    ______________________________________________________________________________________________
</p>
<p>
    7. Перенесені та супутні захворювання 
    ______________________________________________________________________________________________
    <br><br>
    ______________________________________________________________________________________________
</p>
<p>
    8. Розвиток теперішнього захворювання
    ______________________________________________________________________________________________
</p>
<p>
    9. Прикус
    ______________________________________________________________________________________________
</p>
<p>
    10. Стан гігієни порожнини рота, стан слизової оболонки порожнини рота, ясен, альвеолярних відростків та піднебіння. Індекси: ГІ та РМА
    ______________________________________________________________________________________________
</p>
<p>
    11. Дані рентгенівських обстежень, лабораторних досліджень
    ______________________________________________________________________________________________
</p>
<p>
    12. Колір за шкалою "Віта"
    ________________________________________________________________________________________
</p>

<br><br><br><br><br><br><br>
<p>
    13. Дані об'єктивного дослідження, зовнішній огляд, стан зубів:
    ______________________________________________________________________________________________
</p>
<br>

<div class="date_review">
    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Дата огляду</td>
                <td>8</td>
                <td>7</td>
                <td>6</td>
                <td>5 (V)</td>
                <td>4 (IV)</td>
                <td>3 (III)</td>
                <td>2 (II)</td>
                <td>1 (I)</td>
                <td>1 (I)</td>
                <td>2 (II)</td>
                <td>3 (III)</td>
                <td>4 (IV)</td>
                <td>5 (V)</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>


<p align="center">
    <strong>Умовні позначення</strong>
</p>
<p>
    C - карієс, P - пульпіт, Pt - періодонтит, Lp - локалізований пародонтит, Gp - генералізований пародонтит, R - корінь, A - відсутній, Cd - коронка, PI -
    пломба, F - фасетка, ar - штучний зуб, r - реставрація, H - гемісекція, Am - ампутація, res - резекція, pin - штифт, i - імплантація, Rp - реплантація, Dc
    - зубний камінь.
</p>
<p>
    14. Дата навчання навичкам гігієни порожнини рота
    ______________________________________________________________________________________________
</p>
<p>
    15. Дата контролю гігієни порожнини рота
    ______________________________________________________________________________________________
</p>
<br>
<br>
<div align="center"><table align="center" border="1" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="2" style="width:100.0%;">
            <p align="center"><strong>16. ЩОДЕННИК ЛІКАРЯ</strong></p>
            </td>
        </tr>
        <tr>
            <td style="width:17.0%;" height="33">
            <br><strong>Дата</strong>
            </td>
            <td style="width:83.0%;">
            <br><strong>Анамнез, статус, діагноз, лікування та рекомендації</strong><br>
            </td>
        </tr>
        <tr>
            <td style="width:17.0%;">
            <p align="right">&nbsp;</p>
            </td>
            <td style="width:83.0%;">
            <p align="right">&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td style="width:17.0%;">
            <p align="right">&nbsp;</p>
            </td>
            <td style="width:83.0%;">
            <p align="right">&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td style="width:17.0%;">
            <p align="right">&nbsp;</p>
            </td>
            <td style="width:83.0%;">
            <p align="right">&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width:100.0%;">
            <p align="center">Лікар _________________________________________ Завідувач відділення</p>

            <p align="center">Дата заповнення &quot;<?=date("d / m / Y")?> р. &quot; </p><br>
            </td>
        </tr>
    </tbody>
</table>
</div>

<br><br><br>

<div align="center">
<table align="center" border="1" cellpadding="0" cellspacing="0" width="700">
    <tbody>
        <tr>
            <td style="width:48.0%;" height="30">
            <br><strong>План обстеження</strong>
            </td>
            <td style="width:52.0%;">
            <br><strong>План лікування</strong>
            </td>
        </tr>
        <tr>
            <td style="width:48.0%;">
            <p>&nbsp;</p>
            </td>
            <td style="width:52.0%;">
            <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td style="width:48.0%;">
            <p>&nbsp;</p>
            </td>
            <td style="width:52.0%;">
            <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td style="width:48.0%;">
            <p>&nbsp;</p>
            </td>
            <td style="width:52.0%;">
            <p>&nbsp;</p>
            </td>
        </tr>
    </tbody>
</table>
</div>
