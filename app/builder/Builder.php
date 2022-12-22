<?php
namespace App\builder;

use App\database\Connection;


/***************************************************************************************************
 * Classe abstrata para evitar que seja instanciada
 ***************************************************************************************************/
abstract class Builder
{

    // Parametros progetidos para serem acessado apenas pelas classes que hendarem ela
    protected array $binds = [];
    protected array $where = [];
    protected array $query = [];


    // Método where que pode ser usado nas instrunções SQL
    /**
     * Parametros:
     * $field - campo da condição
     * $operator - = , < , > , <>
     * $value - valor da condição
     * $logic - (opcional) AND , OR
     */
    public function where(string $field, string $operator, string|int $value, ?string $logic = null)
    {
        $fieldPlaceholder = $field;

        if (str_contains($fieldPlaceholder, '.')) {
            $fieldPlaceholder = str_replace('.', '', $fieldPlaceholder);
        }

        $this->where[] = "{$field} {$operator} :{$fieldPlaceholder} {$logic}";

        $this->binds[$fieldPlaceholder] = strip_tags($value);

        return $this;
    }


    // Método protegido para executar a instrução SQL, só pode ser acessado pelas classes filhas
    /**
     * Parametros:
     * $query - string sql
     * $returnExecute - false , true (false retorna a string, true executa a string)
     */
    protected function executeQuery($query, $returnExecute = false)
    {

        $connection = Connection::getConnection();
        $prepare = $connection->prepare($query);

        $execute = $prepare->execute($this->binds ?? []);

        return ($returnExecute) ? $execute : $prepare;
    }
}