<?php

class WebsiteController extends Controller
{
	public function actionIndex()
	{
		echo "Главная страница";
	}

	public function actionPage($alias)
	{
		echo "Страница $alias.";
	}
