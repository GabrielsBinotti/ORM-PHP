<?php

namespace App\builder;


/***************************************************************************************************
 * Classe para deletar dados no banco do tipo final para não ser instanciada
 ***************************************************************************************************/
final class Delete extends Builder
{
    // Atributo privado para ser acesso apenas pela classe
    private string $table;


    // Método para expecificar a tabela 
    /**
     * Parametros:
     * $table - nome da tabela
     * retorna a propria classe
     */
    public static function table(string $table)
    {
        $self = new self;
        $self->table = $table;

        return $self;
    }


    // Método privado responsavel por criar a string SQL
    private function createQuery()
    {
        if(!$this->table)
        {
            throw new \Exception("A query precisa chamar o método table.");
        }

        $query  = "DELETE FROM {$this->table}";
        $query .= !empty($this->where) ? " WHERE " . implode(" ", $this->where) : "";

        return $query;
    }


    // Método responsavel por executar o comando delete
    public function delete()
    {
        $query = $this->createQuery();

        try{
            
            return $this->executeQuery($query, returnExecute:true);

        }catch(\PDOException $e)
        {
            var_dump($e->getMessage());
        }
    }
}