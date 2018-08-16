<?php

class QueryBox
{
    const SELECT = "SELECT";
    const FROM = "FROM";
    const WHERE = "WHERE";
    const INSERT_INTO = "INSERT INTO";
    const VALUES = "VALUES";
    const UPDATE = "UPDATE";
    const SET = "SET";

    /** @var string $query */
    private $query = "";

    /**
     * Adds all selectable columns from the params array
     *
     * To select all columns just write select( [*] );
     *
     * Call this function : QueryBoxObject->select( [ $statement1, $statement2, statement3, ...] );
     *
     * The query will look like : SELECT $statement1, $statement2, statement3
     *
     * @param array $params
     */
    public function select($params)
    {
        $i = 0;
        $this->query .= self::SELECT;

        for(; $i < sizeof($params); $i++){
            $this->query .= ($i < (sizeof($params) - 1) ) ? " $params[$i]," : " $params[$i]";
        }
    }

    /**
     * Adds the location string after a FROM statement
     *
     * Call this function : QueryBoxObject->from( "statementString" );
     *
     * The query will look like : ... FROM statementString
     *
     * @param string $location
     */
    public function from($location)
    {
        $this->query .= " " . self::FROM . " $location";
    }

    /**
     * Adds a condition to your query
     *
     * Call this function : QueryBoxObject->where( "condition", "=", "value" );
     *
     * The query will look like : ... WHERE condition = 'value'
     *
     * @param string $condition
     * @param string $operator  | could be ( = , < , >, <=> ... )
     * @param string $value
     */
    public function where($condition, $operator, $value)
    {
        $this->query .= " " . self::WHERE . " $condition $operator '$value';";
    }

    /**
     * Adds an insert action to your query
     *
     * Call this function : QueryBoxObject->insertInto( "table", ["column1", "column2"], ["value1", "value2"] );
     *
     * The query will look like : INSERT INTO table ( column1, column2 ) VALUES ('value1', 'value2')
     *
     * @param string $location | table
     * @param array $paramsN | columnNames
     * @param array $paramsV | columnValues
     */
    public function insertInto($location, $paramsN, $paramsV)
    {
        $i = 0;
        $this->query .= self::INSERT_INTO . " $location(";

        for(; $i < sizeof($paramsN); $i++){
            $this->query .= ($i < (sizeof($paramsN) - 1) ) ? " $paramsN[$i]," : " $paramsN[$i]";
        }

        $this->query .= ") " . self::VALUES . " (";

        for($i = 0; $i < sizeof($paramsV); $i++){
            $this->query .= ($i < (sizeof($paramsV) - 1) ) ? " '$paramsV[$i]'," : " '$paramsV[$i]')";
        }

        $this->query .= ";";
    }

    /**
     * Adds an update action to your query
     *
     * Call this function : QueryBoxObject->update( "table" )
     *
     * The query will look like : UPDATE table
     *
     * @param $location | table
     */
    public function update($location)
    {
        $this->query = self::UPDATE . " $location ";
    }

    /**
     * Adds a set action to your query
     *
     * Call this function : QueryBoxObject->set( ['column' => 'value', ...] )
     *
     * @param array
     */
    public function set($array)
    {
        $this->query .= self::SET;
        foreach ($array as $key => $value){
            $this->query .= " $key = '$value',";
        }

        $this->query = substr_replace($this->query, " ", -1);

    }

    /**
     * Returns the objectvariable query and resets it with ""
     *
     * @return string $q
     */
    public function getQuery()
    {
        $q = $this->query;
        $this->clearQuery();
        return $q;
    }

    /**
     * Resets the objectvariable query to ""
     */
    private function clearQuery()
    {
        $this->query = "";
    }

}