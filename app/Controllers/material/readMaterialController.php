<?php
require_once __DIR__ . '/../../Model/materialModel.php';

$model = new Material();
$materials = $model->showMaterials();
