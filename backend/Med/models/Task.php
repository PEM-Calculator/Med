<?php

namespace Med\Models;

use \Med\Libraries\{Errors, UserAuth};

class Task extends \Med\Models\BaseModel
{
	public
		$id_parent,
		$name,                      // Название задачи
		$date_plan_begin,           // План. начало
		$date_plan_end,             // План. конец
		$plan_length,               // План. длительность в днях
		$plan_preiod_count,         // План. длительность в периодах
		$plan_expense,              // План. бюджет
		$date_over_end,             // Сверхплан. конец
		$over_length,               // Сверхплан. длительность в днях
		$over_period_count,         // Сверхплан. длительность в периодах
		$over_expense,              // Сверхплан. бюджет
		$id_user_creator,           // Автор
		$time_create,               // Время создания
		$id_user_editor,            // Редактор
		$time_update,               // Последняя редакция
		$ids_user_member,           // Члены, имеющие доступ на чтение
		$id_user_manager,           // Менеджер
		$id_user_executor,          // Исполнитель
		$ids_period,                // Набор периодов задачи
		$data_options;              // Настройки {period_width, expense_unit, plan_method, result_method, eff_method, eff_min, eff_max}

	function assign(array $data, $dataColumnMap = NULL, $whiteList = NULL)
	{
		# проверка обязательных полей
		$mandatory = ['name', 'begin', 'end', 'expense'];

		foreach($mandatory as $fieldName)
			if (!isset($data[$fieldName]) || !strlen($data[$fieldName]))
				Errors::e(Errors::ERR_NEED_FIELD_VALUE, $fieldName);

		// план. начало
		$begin = strtotime($data['begin'] . ' UTC');
		if(!$begin)
			Errors::e(Errors::ERR_WRONG_FIELD_VALUE, 'begin');

		// план. окончание
		$end = strtotime($data['end'] . ' UTC');
		if(!$end)
			Errors::e(Errors::ERR_WRONG_FIELD_VALUE, 'end');

		// план. длина
		$length = floor(($end - $begin) / 86400);
		if($length <= 0)
			Errors::e(Errors::ERR_WRONG_FIELD_VALUE, 'begin" or "end');

		// бюджет
		$expense = 1. * $data['expense'];
		if($expense <= 0)
			Errors::e(Errors::ERR_WRONG_FIELD_VALUE, 'expense');

		$new_data = [
			'name' => $data['name'],
			'date_plan_begin' => $begin,
			'date_plan_end' => $end,
			'date_over_end' => $end,        // дата окончания сверхплана задается сразу
			'plan_length' => $length + 1,
			'over_length' => $length + 1,   // длина сверхплана задается сразу
			'plan_expense' => $expense,
			'over_expense' => $expense,     // бюджет сверхплана задается сразу
			'id_user_creator' => UserAuth::$user->getId(),
			'time_create' => time(),
			'id_user_manager' => UserAuth::$user->getId(),
			'id_user_executor' => UserAuth::$user->getId(),
			'ids_period' => [],
			'data_options' => [],
		];

		# проверка необязательных полей
		if(isset($data['parent'])) {
			$parentTask = self::findFirst($data['parent']);
			if($parentTask)
				$new_data['id_parent'] = $parentTask->getId();
			else {
				Errors::e(Errors::ERR_TASK_ACCESS_DENIED_OR_NOT_CREATED);
			}
		}
		// TODO 2016-12-06 22:33 - добавить проверку необязательных полей

		# расчетные переменные
		$new_data['plan_preiod_count'] = 1;
		$new_data['over_period_count'] = 1;

		parent::assign($new_data, $dataColumnMap, $whiteList);
	}
}