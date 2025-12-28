<?php

class reliefModel
{
    protected $pdo;

	public function __construct()
	{
		$this->pdo = Database::getInstance();
	}
}