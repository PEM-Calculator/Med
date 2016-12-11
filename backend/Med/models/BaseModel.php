<?php

namespace Med\Models;

use \Med\Libraries\Errors;

abstract class BaseModel extends \Phalcon\Mvc\Model
{
	static private
		# поля для обработки
		$jsonPref = ['ids', 'json', 'data', 'file'],
		$idsPref = ['ids'],
		$filePref = ['file'],
		$boolPref = ['is'],        // int -> bool поля
		$timePref = ['time'],    // TIMESTAMP поля
		$datePref = ['date'],    // Date поля

		# какие классы проиничены
		$fieldsInited = [];

	static protected
		$readOnlyFields = [];

	protected
		$id,
		$class = null,
		$withHistory = false;    // нужно ли вести историю объекта

	private
		$data = [];                // данные объекта

	public function onConstruct()
	{
		$this->class = get_class($this);

		// устанавливаем типы полей по их именам
		if (!$this->isFieldsInited()) {
			$this->setFieldsType();
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function isFieldsInited()
	{
		return isset(self::$fieldsInited[$this->class]);
	}

	public function getFieldsInited()
	{
		return self::$fieldsInited[$this->class] ?? null;
	}

	/*
	 * Инициализация полей модели
	 */
	public function setFieldsType()
	{
		$fields = $this->toArray();

		$all = $json = $ids = $file = $bool = $time = $date = [];

		foreach ($fields as $field => $value) {
			list($prefix) = explode('_', $field);

			if (in_array($prefix, self::$jsonPref)) {
				$json[] = $field;
			}
			if (in_array($prefix, self::$idsPref)) {
				$ids[] = $field;
			}
			if (in_array($prefix, self::$filePref)) {
				$file[] = $field;
			}
			if (in_array($prefix, self::$boolPref)) {
				$bool[] = $field;
			}
			if (in_array($prefix, self::$timePref)) {
				$time[] = $field;
			}
			if (in_array($prefix, self::$datePref)) {
				$date[] = $field;
			}

			$all[] = $field;
		}

		self::$fieldsInited[$this->class] = [
			'all' => $all,
			'json' => $json,
			'ids' => $ids,
			'file' => $file,
			'bool' => $bool,
			'time' => $time,
			'date' => $date,
		];
	}

	/**
	 * Инициализация модели
	 */
	public function initialize()
	{
		// Устанавливаем таблицу
		if (isset($this->table) && $this->table !== null) {
			$this->setSource($this->table);
		}

		$this->useDynamicUpdate(true);
		$this->keepSnapshots(true);

		if ($this->withHistory) {
			$this->addBehavior(new History());
		}
	}

	//---------------------------------
	//	Event triggers
	//---------------------------------

	public function beforeSave()
	{
		$this->convertStringFields();
		$this->encodeFields();
	}

	public function convertStringFields()
	{
		// вырезка 4-х байтовых символов (т.к. в базе хранятся только 2х байтовые символы)
		foreach ($this->toArray() as $key => $value) {
			if (is_string($value)) {
				$this->{$key} = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $value);
			}
		}
	}

	public function beforeDelete()
	{
		$this->encodeFields();
	}

	public function afterSave()
	{
		$this->decodeFields();
	}

	public function afterFetch()
	{
		$this->decodeFields();
	}

	public function decodeFields()
	{
		$fields = $this->getFieldsInited();

		foreach ($fields['json'] as $field)
			if (isset($this->{$field}) && is_string($this->{$field}))
				$this->$field = json_decode($this->$field, true);

		foreach ($fields['file'] as $field) {
			if ($this->$field == null) continue;
			$file = new File();
			$file->loadFromJson($this->$field);

			$this->$field = $file;
		}

		foreach ($fields['bool'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: ($this->$field == 0 ? false : true));

		foreach ($fields['date'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: strtotime($this->$field . ' UTC'));

		foreach ($fields['time'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: strtotime($this->$field . ' UTC'));
	}

	public function encodeFields()
	{
		$fields = $this->getFieldsInited();

		foreach ($fields['ids'] as $field => $name)
			if (isset($this->$field) && is_array($this->$field)) {
				$data = $this->$field;
				foreach ($data as $key => $item)
					$data[$key] = $item * 1;
				$this->$field = $data;
			}

		foreach ($fields['file'] as $field => $name) {
			if ($this->$field == 'empty')
				$this->$field = null;
			if (!$this->$field instanceof File)
				continue;

			/** @var File $file */
			$file = $this->$field;
			if (!$file->uploadTempFile())
				$this->$field = null;
			else
				$this->$field = $file->getInformation();
		}

		foreach ($fields['json'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: json_encode($this->$field, JSON_NUMERIC_CHECK));

		foreach ($fields['bool'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: ($this->$field ? 1 : 0));

		foreach ($fields['date'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: date('Y-m-d', $this->$field));

		foreach ($fields['time'] as $field)
			$this->$field = (!isset($this->$field) ? null
				: gmdate('Y-m-d H:i:s', $this->$field));
	}

	//---------------------------------
	// Методы поиска
	//---------------------------------

	static function findFirstByField($field, $value)
	{
		return self::findFirst([
			'conditions' => $field . ' = ?1',
			'bind' => [
				1 => $value
			],
		]);
	}
}