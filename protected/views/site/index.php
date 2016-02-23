<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<br>
<h1 style="font-size: 1.6em;">Добро пожаловать в систему управлению пациентами</h1>

<br>

<div style="text-align: center;">
	<div class="admin-menu">
	<?php if(Yii::app()->user->getName()=='admin'): ?>
	<div>
		<a href="/user/admin">
			<img src="/images/icons/users.png">
			<div class="description">
				Пользователи
			</div>
		</a>
	</div>
	<div>
		<a href="/stats/">
			<img src="/images/icons/stats.png">
			<div class="description">
				Статистика
			</div>
		</a>
	</div>
	<div>
		<a href="/sources/admin">
			<img src="/images/icons/client_sources.png">
			<div class="description">
				Источники клиентов
			</div>
		</a>
	</div>
	<div>
		<a href="/affiliate/admin">
			<img src="/images/icons/affiliates.png">
			<div class="description">
				Управление филиалами
			</div>
		</a>
	</div>
	<div>
		<a href="/doctors/admin">
			<img src="/images/icons/doctors.png">
			<div class="description">
				Управление врачами
			</div>
		</a>
	</div>
	<div>
		<a href="/discounts/admin">
			<img src="/images/icons/discounts.png">
			<div class="description">
				Управление скидками
			</div>
		</a>
	</div>
	<div class="display_none">
		<a href="/diagnoses/admin">
			<img src="/images/icons/diagnoses.png">
			<div class="description">
				Управление диагнозами
			</div>
		</a>
	</div>
	<?php endif; ?>


	<div>
		<a href="/patients/admin">
			<img src="/images/icons/patients.png">
			<div class="description">
				Управление пациентами
			</div>
		</a>
	</div>
	<div>
		<a href="/patient/history/admin">
			<img src="/images/icons/patients_history.png">
			<div class="description">
				Управление визитами пациентов
			</div>
		</a>
	</div>
	<div>
		<a href="/services/admin">
			<img src="/images/icons/services.png">
			<div class="description">
				Управление услугами
			</div>
		</a>
	</div>
	<div>
		<a href="/schedule">
			<img src="/images/icons/schedule.png">
			<div class="description">
				Расписание
			</div>
		</a>
	</div>

	</div>
</div>

<!-- <p>В стоматологии on-line доступны такие возможности:</p> -->
<div id="list5">
    <!-- <ol> -->
        <!-- <li>Админская панель (доступна только админу) -->
            <!-- <ol> -->
                <!-- <li><a href="/user/admin">Пользователи</li> -->
                <!-- <li><a href="/sources/admin">Источники клиентов</a></li> -->
                <!-- <li><a href="/affiliate/admin">Филиалы</a></li> -->
                <!-- <li><a href="/doctors/admin">Доктора</a></li> -->
                <!-- <li><a href="/discounts/admin">Скидки</a></li> -->
                <!-- <li><a href="/services/admin">Услуги</a></li> -->
                <!-- <li><a href="/stats/">Статистика</a></li> -->
            <!-- </ol> -->
        <!-- </li> -->

        <!-- <li><a href="/diagnoses/admin">Управление диагнозами</a></li> -->
        <!-- <li><a href="/patients/admin">Управление пациентами</a></li> -->
        <!-- <li><a href="/patientshistory/admin">Управление визитами пациентов</a></li> -->
        <!-- <li><a href="/schedule/">Расписание</a></li> -->
    <!-- </ol> -->
</div>




