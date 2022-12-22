<?php

namespace App\builder;

/***************************************************************************************************
 * Classe responsavel pelo insert de dados no banco do tipo final para não ser instanciada
 ***************************************************************************************************/
final class Insert extends Builder
{

    // Atributos privados
    private string $table;

    // Método para setar a tabela
    /**
     * Parametros:
     * $table - nome da tabela
     * retorna uma instancia dela mesma
     */
    public static function into(string $table)
    {
        $self = new self;
        $self->table = $table;

        return $self;
    }

    // Método privado para criar a string SQL
    private function createQuery()
    {
        if (!$this->table) {
            throw new \Exception('A query precisa chamar o método into');
        }

        if (!$this->binds) {
            throw new \Exception('A query precisa dos dados para cadastrar');
        }

        $query = "INSERT INTO {$this->table}(";
        $query .= implode(',', array_keys($this->binds)) . ') VALUES(';
        $query .= ':' . implode(', :', array_keys($this->binds)) . ')';

        return $query;
    }


    // Método responsavel por executar o insert
    /**
     * Parametros:
     * $data - array com os dados (as keys devem ter o mesmo nome da coluna no banco de dados)
     */
    public function insert(array $data)
    {
        $this->binds = $data;

        $query = $this->createQuery();

        try {
            return $this->executeQuery($query, returnExecute:true);
        } catch (\PDOException $th) {
            var_dump($th->getMessage());
        }
    }
}