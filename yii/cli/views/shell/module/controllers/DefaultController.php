<?php

class DefaultController extends CliController
{
	public function actionIndex()
	{
		$this->render('index');
	}
}