<?php

namespace App\builder;

/***************************************************************************************************
 * Classe responsavel pelo select no banco do tipo final para não ser instanciada
 ***************************************************************************************************/
final class Read extends Builder
{
    
    // Atributos privados
    private ?string $table = null;
    private ?string $fields = null;
    private string $order;
    private string $group;
    private array $join = [];
    private string $limit;


    // Método para definir os campos que deve retornar na consulta
    /**
     * Parametros:
     * $fields - string com os campos separados por virgula
     * retorna a instancia da classe
     */
    public static function select(string $fields = '*')
    {
        $self = new self;
        $self->fields = $fields;

        return $self;
    }


    // Método para definir a tabela
    /**
     * Parametros:
     * $table - nome da tabela
     */
    public function from(string $table)
    {
        $this->table = $table;

        return $this;
    }


    // Método para criar o join
    /**
     * Parametros:
     * $foreignTable - nome da tabela estrangeira
     * $logic - string com a logica (tabela1.id = tabela2.id)
     * $type - INNER, LEFT, RIGHT
     */
    public function join(string $foreignTable, string $logic, string $type = 'INNER')
    {
        $this->join[] = " {$type} JOIN {$foreignTable} ON {$logic}";

        return $this;
    }


    // Método para ordenar a consulta
    /**
     * Parametros:
     * $field - Campo
     * $value - ASC, DESC
     */
    public function order(string $field, string $value)
    {
        $this->order = " ORDER BY {$field} {$value}";

        return $this;
    }


    // Método responsavel por fazer o agrupamento dos dados na consulta
    /**
     * Parametros:
     * $field - Campo
     */
    public function group(string $field)
    {
        $this->group = " GROUP BY {$field}";

        return $this;
    }


    // Método responsavel por limitar os dados que retornará do banco
    /**
     * Parametros:
     * $limitInitial - valor inicial
     * $limitFinal - valor final
     */
    public function limit(string $limitInitial, string $limitFinal)
    {
        $this->limit = " LIMIT {$limitInitial} , {$limitFinal}";

        return $this;
    }


    // Método privado para criar a string SQL
    /**
     * Parametro:
     * $count - false , true
     */
    private function createQuery(bool $count = false)
    {
        if (!$this->fields) {
            throw new \Exception('A query precisa chamar o método select');
        }

        if (!$this->table) {
            throw new \Exception('A query precisa chamar o método from');
        }

        $query = ($count) ? 'SELECT COUNT(*) AS total ' : 'SELECT ';
        $query .= ($count) ? 'FROM ' : $this->fields . 'FROM ';
        $query .= $this->table;
        $query .= !empty($this->join) ? implode(' ', $this->join) : '';
        $query .= !empty($this->where) ? ' WHERE ' . implode(' ', $this->where) : '';
        $query .= $this->group ?? '';
        $query .= $this->order ?? '';
        $query .= $this->limit ?? '';

        return $query;
    }


    // Método para executar a string SQL e retornar um array de objetos
    public function get()
    {
        $query = $this->createQuery();

        $this->query['get'][] = $query;

        try {
            $prepare = $this->executeQuery($query);

            return $prepare->fetchAll();
        } catch (\PDOException $th) {
            var_dump($th->getMessage());
        }
    }


    // Método para executar a string SQL e retornar apenas uma informação do banco de dados
    public function first()
    {
        $query = $this->createQuery();

        $this->query['first'][] = $query;

        try {
            $prepare = $this->executeQuery($query);

            return $prepare->fetchObject();
        } catch (\PDOException $th) {
            var_dump($th->getMessage());
        }
    }
}