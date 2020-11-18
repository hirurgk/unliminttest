<?php

namespace Kernel;

class Model extends Singleton
{    
    /**
     * db
     * @var Database
     */
    private $db;
    
    /**
     * tablename
     * @var string
     */
    private $tablename = '';
        
    /**
     * fields
     * Массив колонок и значений для таблицы
     * @var array
     */
    private $fields = [];

    public function __construct()
    {
        $this->db = Database::getInstance()->getDB();

        // Получим имя таблицы из названия модели
        $modelName = end(explode("\\", get_called_class()));
        $this->tablename = strtolower($modelName);
    }
    
    /**
     * __set
     * @param string $name
     * @param string $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
    }
    
    /**
     * __get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->fields[$name];
    }
    
    /**
     * findBy
     * Поиск записей по параметрам
     * @param array $where ['param' => 'value']
     * @return array
     */
    public function findBy($where = [])
    {
        // Колонки и значения строками для запроса
        $where = $where ?: ['1' => '1'];
        $columns = implode(',', array_keys($where));
        $params = implode(',',
            array_map(fn() => '?', array_keys($where))
        );

        $sql = "SELECT * FROM {$this->tablename} WHERE {$columns} = {$params}";
        $result = $this->db->prepare($sql);
        $result->execute(array_values($where));

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * findAll
     *
     * @param string $sort сортировка по параметру
     * @param string $order направление сортировки
     * @param int $from с какой записи выборка
     * @param int $countOnPage количество записей
     * @return array
     */
    public function findAll($sort = 'id', $order = 'ASC', $from = 1, $countOnPage = 1)
    {
        $sql = "SELECT * FROM {$this->tablename} ORDER BY {$sort} {$order} LIMIT ?, ?";

        $result = $this->db->prepare($sql);

        $i = 0;
        $result->bindParam(++$i, $from, \PDO::PARAM_INT);
        $result->bindParam(++$i, $countOnPage, \PDO::PARAM_INT);

        $result->execute();

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * count
     * Количество записей в таблице
     * @return int
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM {$this->tablename}";
        return $this->db
            ->query($sql)
            ->fetchColumn();
    }
    
    /**
     * save
     * Сохраняет запись
     * @return bool
     */
    public function save()
    {
        // Если указан id - обновляем запись, иначе создаём новую
        if (!empty($this->fields['id'])) return $this->update();
        else return $this->create();
    }
    
    /**
     * create
     * Создание записи в таблице
     * @return bool
     */
    private function create()
    {
        // Колонки и значения для SQL
        $columns = implode(',', array_keys($this->fields));
        $params = implode(',',
            array_map(fn() => '?', array_keys($this->fields))
        );

        $sql = "INSERT INTO {$this->tablename} ({$columns}) VALUES ($params)";
        return $this->db
            ->prepare($sql)
            ->execute(array_values($this->fields));
    }

    /**
     * update
     * Обновление записи в таблице
     * @return bool
     */
    private function update()
    {
        // Колонки без id
        $fields = array_filter($this->fields, fn($f, $k) => $k !== 'id', ARRAY_FILTER_USE_BOTH);

        // Колонки и значения для SQL
        $arColumns = array_map(function($key) {
            return "SET {$key} = ?";
        }, array_keys($fields));
        $columns = implode(',', $arColumns);
        $params = array_values($fields);
        $params[] = $this->fields['id'];

        $sql = "UPDATE {$this->tablename} {$columns} WHERE id = ?";
        return $this->db
            ->prepare($sql)
            ->execute(array_values($params));
    }
}
