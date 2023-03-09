<?php

namespace app\Models;

use app\Helpers\DbHelper;
use \mysqli;

class DB extends DbHelper
{
    protected $stmt;
    protected $conn;
    protected $query = null;
    protected $table;
    protected $select = "*";
    protected $where = [];
    protected $joins = [];
    protected $limit = 200;
    protected $offset = 0;
    protected $vals = [];
    protected $order = 'ASC';
    protected $orderBy = null;
    protected $groupBy = "";

    function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // Create connection
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function groupBy($attr)
    {
        // $arr = explode('.', $attr);
        // if (count($arr) == 1) {
        //     $this->groupBy = $arr[0];
        // }
        // if (count($arr) == 2) {
        //     $this->groupBy = $arr[1];
        // }
        $this->groupBy = $attr;

        return $this;
    }

    public function select($attr)
    {
        if (!is_string($attr)) die("invalid value for select");
        $this->select = $attr;
        return $this;
    }

    public function limit($limit)
    {
        if (!is_numeric($limit)) die("invalid value for limit");
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        if (!is_numeric($offset)) die("invalid value for offset");
        $this->offset = $offset;
        return $this;
    }

    public function orderBy($orderBy, $order = "ASC")
    {

        if (strtoupper($order) == "RAND" || strtoupper($orderBy) == "RAND") {
            $this->order = "RAND()";
            $this->orderBy = '';
            return $this;
        }

        $ORDERS = ['ASC', 'DESC', 'RAND', 'RAND()'];
        if (!in_array(strtoupper($order), $ORDERS)) die("Invalid order $order");

        if (!is_string($orderBy)) die("Invalid orderBy $orderBy");

        $this->order = $order;
        $this->orderBy = $orderBy;
        return $this;
    }

    public function where($col, $val, $opr = "=")
    {
        $operators = ['=', '>', '<', '>', '!=', '>=', '<=', 'LIKE', 'IN'];

        if (is_numeric($col)) die("invalid col $col");

        if (in_array(strtoupper($val), $operators) && $val != $col) {
            $temp = $val;
            $val = $opr;
            $opr = $temp;
        }

        if (!in_array(strtoupper($opr), $operators)) {
            die("Invalid operator $opr");
        }
        $where['col'] = " `$col`  ";
        $where['opr'] = $opr;
        $where['val'] = $val;
        $where['opd'] = 'AND';
        $this->where[] = $where;

        return $this;
    }

    public function orWhere($col, $val, $opr = "=")
    {
        $operators = ['=', '>', '<', '>', '!=', '>=', '<=', 'LIKE'];

        if (is_numeric($col)) die("invalid col $col");

        if (in_array(strtoupper($val), $operators) && !in_array(strtoupper($opr), $operators)) {
            $temp = $val;
            $val = $opr;
            $opr = $temp;
        }

        if (!in_array($opr, $operators)) {
            die("Invalid operator");
        }
        $where['col'] = '`' . $col . '`';
        $where['opr'] = $opr;
        $where['val'] = $val;
        $where['opd'] = 'OR';
        $this->where[] = $where;

        return $this;
    }

    public function save($array, $table = null)
    {
        return $this->insert($array, $table);
    }

    // INSERT INTO DB
    public function insert($array, $table = null)
    {
        if (empty($array)) die('Attributes Array Cannot be empty');
        else if ($table) {

            if (is_string($array && is_array($table))) {
                $this->table = $array;
                $array = $table;
            } else if (is_string($table)) {
                $this->table = $table;
            } else {
                die("Invalid table $table");
            }
        } else if (!is_array($array)) die('insert expect the assoc input array');

        $return = $this->buildInsertQuery($this->table, $array);
        $query = $return[0];
        $types = $return[1];
        $values = $return[2];
        $this->stmt = $this
            ->conn
            ->prepare($query) or die($this
                ->conn
                ->error);
        $this->bind_custom_param($values, $types);
        $this
            ->stmt
            ->execute() or die($this
                ->conn
                ->error);
        if (
            $this
            ->conn->affected_rows === 0
        ) return false;
        else {
            $data = (object)$array;
            if (isset($data->password)) unset($data->password);
            $data->id = $this
                ->conn->insert_id;
            return $data;
        }
    }

    public function get($table = null)
    {
        if ($table) $this->table = $table;

        $this->query = "SELECT $this->select FROM $this->table ";

        if (!empty($this->joins)) $this->query .= $this->makeJoins($this->joins);

        // WHERE CONDITIONS
        if (!empty($this->where)) $this->query .= $this->makeWhereConditions($this->where);

        // GROUP BY CONDITION
        if (!empty($this->groupBy)) $this->query .= $this->makeGroupByCondition($this->groupBy);

        // die($this->query);

        //ORDER BY
        if ($this->orderBy && is_string($this->orderBy)) $this->query .= " ORDER BY $this->orderBy $this->order ";

        //OFFSET
        if ($this->offset && is_numeric($this->offset) && $this->offset > 0)
            $this->query .= " OFFSET $this->offset";

        // die($this->query);
        //LIMIT
        //if ($this->limit && is_numeric($this->limit)) $this->query .= " LIMIT $this->limit";

        if (!empty($this->where)) $data = $this->getDataWIthQuery($this->query, $this->vals);
        else $data = $this->getDataWIthQuery($this->query);
        return $data;
    }



    public function paginate($perPage)
    {
        if (!is_int($perPage) || $perPage < 1) {
            die("inavlid Per Page value in paginate method");
        }
        $this->limit = $perPage;
        $page = $_GET['page'] ??  1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $perPage;

        $this->query = "SELECT $this->select FROM $this->table ";

        if (!empty($this->joins)) $this->query .= $this->makeJoins($this->joins);

        // WHERE CONDITIONS
        $where = '';
        if (!empty($this->where)) {
            $where = $this->makeWhereConditions($this->where);
            $this->query .= $where;
        }

        //ORDER BY
        $orderBy = '';
        if ($this->orderBy && is_string($this->orderBy)) {
            $orderBy = " ORDER BY $this->orderBy $this->order ";
            $this->query .= $orderBy;
        }

        //GET TOTAL COUNT FOR PAGINATION
        $query = $this->query;
        $vals = $this->vals;
        $totalResults = $this->returnCountForPagination($this->table, $where, $vals, $orderBy);
        $this->query = $query;
        $lastPage = ceil($totalResults / $perPage);

        //LIMIT
        $this->query .= " LIMIT $perPage";
        $this->query .= " OFFSET $offset";

        if ($lastPage >= $page) {

            if (!empty($where))
                $data = $this->getDataWIthQuery($this->query,  $vals);
            else
                $data = $this->getDataWIthQuery($this->query);
        } else {
            $data = [];
        }

        $pagination['currentPage'] = $page;
        $pagination['perPage'] = $perPage;
        $pagination['prevPage'] = $page - 1;
        $pagination['nextPage'] = $page < $lastPage ? $page + 1 : 0;
        $pagination['lastPage'] = $lastPage;
        $pagination['totalResults'] = $totalResults;
        $pagination['from'] = ($page - 1) * $perPage;
        $pagination['to'] = $page * $perPage;
        $pagination['data'] = $data;
        return $pagination;
    }


    public function first($table = null)
    {
        if ($table) $this->table = $table;

        $this->query = "SELECT $this->select FROM $this->table ";

        // WHERE CONDITIONS
        if ($this->where) $this->query .= $this->makeWhereConditions($this->where);

        //ORDER BY
        if ($this->orderBy && is_string($this->orderBy)) $this->query .= " ORDER BY $this->orderBy $this->order ";

        //LIMIT
        $this->query .= " LIMIT 1";
        if (!empty($this->where)) $data = $this->getDataWIthQuery($this->query, $this->vals);
        else $data = $this->getDataWIthQuery($this->query);

        if (!empty($data) && isset($data[0]) && $data[0])
            return $data[0];

        return null;
    }


    public function count()
    {

        $this->query = "SELECT COUNT(*) as count FROM $this->table ";

        // WHERE CONDITIONS
        if ($this->where) $this->query .= $this->makeWhereConditions($this->where);

        //ORDER BY
        if ($this->orderBy && is_string($this->orderBy)) $this->query .= " ORDER BY $this->orderBy $this->order ";

        //OFFSET
        if ($this->offset && is_numeric($this->offset) && $this->offset > 0)
            $this->query .= " OFFSET $this->offset";

        //LIMIT
        if ($this->limit && is_numeric($this->limit)) $this->query .= " LIMIT $this->limit";

        if (!empty($this->where)) $data = $this->getDataWIthQuery($this->query, $this->vals);
        else $data = $this->getDataWIthQuery($this->query);
        return $data[0]->count;
    }


    public function delete($table = null)
    {
        if ($table) $this->table = $table;

        $this->query = "DELETE FROM $this->table ";

        // WHERE CONDITIONS
        if ($this->where) $this->query .= $this->makeWhereConditions($this->where);

        //ORDER BY
        if ($this->orderBy && is_string($this->orderBy)) $this->query .= " ORDER BY $this->orderBy $this->order ";

        //OFFSET
        if ($this->offset && is_numeric($this->offset) && $this->offset > 0)
            $this->query .= " OFFSET $this->offset";

        //LIMIT
        if ($this->limit && is_numeric($this->limit)) $this->query .= " LIMIT $this->limit";

        if (!empty($this->where))
            return $this->deleteDataWIthQuery($this->query, $this->vals);
        else
            return $this->deleteDataWIthQuery($this->query);
    }

    public function join($table, $col1, $col2, $opr = "=")
    {

        if (is_numeric($table)) die("invalid table $table");

        $join['table'] = $table;
        $join['col1'] = $col1;
        $join['col2'] = $col2;
        $join['join'] = '';
        $this->joins[] = $join;

        return $this;
    }

    public function leftJoin($table, $col1, $col2, $opr = "=")
    {

        if (is_numeric($table)) die("invalid table $table");

        $join['table'] = $table;
        $join['col1'] = $col1;
        $join['col2'] = $col2;
        $join['join'] = 'LEFT';
        $this->joins[] = $join;

        return $this;
    }

    public function increment($field, $val = 1)
    {
        if (!is_numeric($val) || $val < 1)
            die('Invalid value passed in increment mehtod');

        return $this->incrementDecrement($field, '+', $val);
    }

    public function decrement($field, $val = 1)
    {
        if (!is_numeric($val) || $val < 1)
            die('Invalid value passed in increment mehtod');

        return $this->incrementDecrement($field, '-', $val);
    }
    public function incrementDecrement($field, $opr, $val)
    {
        $field = addslashes($field);
        if (is_numeric($field) || is_array($field) || strpos($field, "'") !== false)
            die('Invalid filed passed in increment mehtod');

        $this->query = "UPDATE `$this->table` SET `$field` = `$field` $opr$val ";

        // WHERE CONDITIONS
        if ($this->where) $this->query .= $this->makeWhereConditions($this->where);

        //ORDER BY
        if ($this->orderBy && is_string($this->orderBy)) $this->query .= " ORDER BY $this->orderBy $this->order ";

        $this->stmt = $this->conn->prepare($this->query) or die($this->conn->error);
        $types = '';
        if (!empty($this->vals) && empty($types)) {
            foreach ($this->vals as $val) $types .= $this->returnTypeOfVar($val);
        }
        $this->bind_custom_param($this->vals, $types);
        $this->stmt->execute() or die($this->conn->error);
        if ($this->conn->affected_rows === 0) {
            if (isset($this->stmt->error) && !empty($this->stmt->error))
                return false;
        }
        $this->emptyObj();
        return true;
    }

    public function update($array)
    {
        if (empty($this->where)) die("Where condition is required in update ");

        foreach ($this->where as $where) {
            $col = $where['col'];
            if (isset($array[$col])) unset($array[$col]);
        }

        $return = $this->buildUpdateQuery($this->table, $array, $this->where);

        $this->query = $return[0];

        $types = $return[1];
        $this->vals = $return[2];

        $this->stmt = $this->conn->prepare($this->query) or die($this->conn->error);
        $this->bind_custom_param($this->vals, $types);
        $this->stmt->execute() or die($this->conn->error);
        $data = $array;
        if ($this->conn->affected_rows === 0) {
            if (isset($this->stmt->error) && !empty($this->stmt->error))
                die('Query Error ');
        }

        // GET AND RETURN UPDATED ROW
        $query = "SELECT * FROM $this->table ";
        $query .= $this->makeWhereConditions($this->where);
        $query .= " LIMIT 1";
        foreach ($this->where as $where)
            $vals[] = $where['val'];
        $data = $this->getDataWIthQuery($query, $vals);
        return $data[0] ?? null;
    }

    public function deleteDataWIthQuery($query, $vals = [], $types = "")
    {
        if (is_string($vals))
            $vals[0] = $vals;

        if (empty($types) && empty($vals)) {
            $result = $this
                ->conn
                ->query($query) or die($this
                    ->conn
                    ->error);
        } else {
            if (empty($types)) foreach ($vals as $val) $types .= $this->returnTypeOfVar($val);
            $this->stmt = $this->conn->prepare($query) or die($this->conn->error);
            $this->bind_custom_param($vals, $types);
            $this->stmt->execute() or die($this->stmt->error);
        }
        $this->emptyObj();
        if (
            $this
            ->conn->affected_rows > 0
        ) return true;
        else return false;
    }

    public function getDataWIthQuery($query, $vals = [], $types = '')
    {
        $data = array();
        if (empty($vals)) {
            $result = $this
                ->conn
                ->query($query) or die($this
                    ->conn
                    ->error);
            while ($row = $result->fetch_object()) $data[] = $row;
        } else if (is_array($vals)) {
            if (empty($types)) foreach ($vals as $val) $types .= $this->returnTypeOfVar($val);

            $this->stmt = $this->conn->prepare($query) or die($this->conn->error);
            $this->bind_custom_param($vals, $types);
            $this->stmt->execute() or die($this->stmt->error);
            $result = $this->stmt->get_result();
            while ($row = $result->fetch_object()) $data[] = $row;
        }
        $this->emptyObj();
        return $data;
    }


    public function exist($table, $col, $val, $opr = "=")
    {
        $query = "SELECT * FROM $table WHERE $col $opr ? LIMIT 1";
        $this->stmt = $this->conn->prepare($query) or die($this->conn->error);
        $this->stmt->bind_param("s", $val);
        $this->stmt->execute();
        $result = $this->stmt->get_result();
        if ($result->num_rows === 0)
            return FALSE;
        return TRUE;
    }

    private function emptyObj()
    {
        $this->query = [];
        $this->where = [];
        $this->vals = [];
        $this->joins = [];
        $this->table = null;
        $this->order = 'ASC';
        $this->orderBy = null;
        $this->select = '*';
        $this->limit = 200;
        $this->offset = 0;
    }


    protected function returnCountForPagination($table, $where, $vals, $orderBy)
    {
        $query = "SELECT COUNT(*) as count FROM $table  $where   $orderBy";
        if (!empty($where))
            $data = $this->getDataWIthQuery($query, $vals);
        else
            $data = $this->getDataWIthQuery($query);
        return $data[0]['count'];
    }
}
