<?php

namespace App\builder;

/***************************************************************************************************
 * Classe para fazer o update de dados no banco do tipo final para não ser instanciada
 ***************************************************************************************************/
final class Update extends Builder
{

    // Atributos privados 
    private string $table;
    private array $data = [];


    // Método para definir a tabela
    /**
     * Parametros:
     * $table - nome da tabela
     * retorna a instancia dela mesma
     */
    public static function table(string $table)
    {
        $self = new self;
        $self->table = $table;

        return $self;
    }


    // Método set para os setar os novos dados na tabela
    /**
     * Parametros:
     * $data - array com os dados (as chaves devem ter o mesmo nome da coluna na base de dados)
     */
    public function set(array $data)
    {
        $this->data = $data;

        return $this;
    }


    // Método privado para criar a string SQL
    private function createQuery()
    {
        if (!$this->table) {
            throw new \Exception('A query precisa chamar o método table');
        }

        if (!$this->data) {
            throw new \Exception('A query precisa de dados para atualizar');
        }

        $query = "UPDATE {$this->table} SET ";

        foreach ($this->data as $field => $value) {
            $query .= "{$field} = :{$field},";
            $this->binds[$field] = $value;
        }

        $query = rtrim($query, ',');
        $query .= !empty($this->where) ? ' WHERE ' . implode(' ', $this->where) : '';

        
        return $query;
    }


    //Método para executar o update
    public function update()
    {
        $query = $this->createQuery();

        try {
            return $this->executeQuery($query, returnExecute:true);
        } catch (\PDOException $th) {
            var_dump($th->getMessage());
        }
    }
}