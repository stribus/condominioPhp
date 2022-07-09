<?php

namespace app\repositories\traits;

use app\Exceptions\SQLException;

trait Read
{
    protected $sqlRead;
    protected $filters = [];
    protected $args = [];
    protected $orderFields = '';
    protected $pagination = [];
    protected $rowPerPage = 20;
    protected $pageCount = 1;
    protected $rowCount = 0;
    protected $offset = 0;
    protected $groupFields = '';

    /**
     * inicializa o select com os campos informados.
     *
     * @param string $fields campos a serem buscados separados por virgula
     *
     * @return $this
     */
    public function select($fields = '*'): static
    {
        $this->sqlRead = " select {$fields} from ".$this->tableName.' x ';
        $this->filters = [];
        $this->args = [];
        $this->pagination = [];
        $this->orderFields = '';
        $this->groupFields = '';

        return $this;
    }

    /**
     * Adiciona Join a consulta
     * @param string $join tipo de join (inner, left, right)
     * @param string $table tabela a ser joinada
     * @param string $condition condição do join
     */
    public function addJoins(string $join ='inner' ,string $table, string $condition): static
    {
        $this->sqlRead .= " {$join} join {$table} on {$condition} ";

        return $this;
    }

    /**
     * Adiciona os filtros where a pesquisa
     * aceitando 2 ou 3 argumentos.<br>
     * Com <b>2</b> argumentos devem ser informado o <i>$field</i> e o <i>$valor</i>,
     *  e será considerado uma igualdade.<br>
     * Com <b>3</b> agrumentos devem ser informado na ordem <i>$field ,$operador, $valor</i> <br>
     * <b>Multiplas chamadas serão unidos com o <i>AND</i> </b>.
     *
     * @param string $field    campo a ser pesquisado
     * @param string $operador operador de comparacao,
     *                         caso não informado considera <b>Default</b> igualdade <b><i>"="</i><b>
     * @param string $valor    valor a ser comparado
     *
     * @throws \Exception quantidade de parametros diferente de 2 ou 3
     *
     * @return $this
     */
    public function where(): static
    {
        $num_args = func_num_args();

        $args = func_get_args();

        if ($num_args < 2) {
            throw new \Exception('Opa, algo errado aconteceu, o where precisa de no mínimo 2 argumentos');
        }

        if (2 == $num_args) {
            $field = $args[0];
            $sinal = '=';
            $value = $args[1];
        }

        if (3 == $num_args) {
            $field = $args[0];
            $sinal = $args[1];
            $value = $args[2];
        }

        if ($num_args > 3) {
            throw new \Exception('Opa, algo errado aconteceu, o where não pode ter mais que 3 argumentos');
        }

        $this->filters[] = " {$field} {$sinal} ? ";
        $this->args[] = $value;

        return $this;
    }

    /**
     * Adiciona os filtros where a pesquisa
     * buscando entre os valores informados no array.
     *
     * @param string $field  campo a ser pesquisado
     * @param array  $values valores a serem pesquisados
     *
     * @return $this
     */
    public function whereInArray(string $field, array $values): static
    {
        $this->filters[] = " {$field} in ( ".implode(',', array_fill(1, count($values), ' ? ')).' ) ';
        $this->args[] = array_merge($this->args, $values);

        return $this;
    }

    /**
     * Adiciona os filtros where a pesquisa
     * buscando por registros com os valores diferentes do array.
     *
     * @param string $field  campo a ser pesquisado
     * @param array  $values valores a serem pesquisados
     *
     * @return $this
     */
    public function whereNotInArray(string $field, array $values): static
    {
        $this->filters[] = " {$field} not in ( ".implode(',', array_fill(1, count($values), ' ? ')).' ) ';
        $this->args[] = array_merge($this->args, $values);

        return $this;
    }

    /**
     * Adiciona os filtros where a pesquisa
     * buscando por valor em um subquery.
     *
     * @param string $field    campo a ser pesquisado
     * @param string $subquery subquery a ser pesquisada
     * @param mixed  $select
     *
     * @return $this
     */
    public function whereInSelect($field, $select): static
    {
        $this->filters[] = " {$field} in ( {$select} ) ";

        return $this;
    }

    /**
     * busca filtrando entre dois valores.
     *
     * @param string $field campo a ser pesquisado
     * @param string $min   valor minimo
     * @param string $max   valor maximo
     *
     * @return $this
     */
    public function whereBetween($field, $min, $max): static
    {
        $this->filters[] = " {$field} between ? and ? ";
        $this->args[] = $min;
        $this->args[] = $max;

        return $this;
    }

    /**
     * Adiciona os filtros where a pesquisa.
     *
     * @param string $where condição a ser pesquisada
     * @param array  $args  valores a serem pesquisados
     *
     * @return $this
     */
    public function genericWhere(string $where, array $args): static
    {
        $this->filters[] = $where;
        $this->args = array_merge($this->args, $args);

        return $this;
    }

    /**
     * @return array retorna um array com todos os registro do select
     */
    public function getList(string $class = null,array $ctor_args): array
    {
        return $this->prepared()->connection->getAll($class, $ctor_args);
    }

    /**
     * @return array retorna um array com todos os registro do select,
     *               indexado com o valor da primarykey na key
     */
    public function getKeyList(): array
    {
        $stm = $this->prepared()->connection->getStatement();
        $a = [];
        while ($row = $stm->fetch(\PDO::FETCH_ASSOC)) {
            $a[$row[$this->PK]] = $row;
        }
        $stm->closeCursor();

        return $a;
    }

    public function getFirst()
    {
        $removedSelect = preg_replace('/^\s*select(\stop \d+)*/', '', $this->sqlRead);

        $this->sqlRead = 'select top 1 '.$removedSelect;
        $stm = $this->prepared()->connection->getStatement();
        $row = $stm->fetch(\PDO::FETCH_ASSOC);
        $stm->closeCursor();

        return $row;
    }

    /**
     * busca e preenche as informações de quantidades de registros e paginação.
     *
     * @return int quantidade de registros da consulta SQL
     */
    public function count():int
    {
        $this->prepared();
        $this->rowCount = $this->connection->rowCount();

        $this->pageCount = ceil($this->rowCount / $this->rowPerPage);

        return $this->rowCount;
    }

    /**
     * @param string $field null = Primarykey
     *
     * @return $this
     */
    public function orderBy(string $field = null)
    {
        $this->orderFields = $field ?? $this->PK;

        return $this;
    }

    public function paginate(int $start, int $rows)
    {
        $this->pagination = ['start' => ($start >= 0 ? $start : 0), 'rowsCount' => $rows];

        return $this;
    }

    /**
     * prepara e executa o select gerado.
     *
     * @param array $params
     * @param array $options <p>An array specifying query property options.
     *                       The supported keys are described in the following table:</p>  <b>Query Options</b>
     *                       Key Values Description     QueryTimeout A positive integer value.
     *                       Sets the query timeout in seconds. By default, the driver will wait
     *                       indefinitely for results.   SendStreamParamsAtExec <b><code>true</code></b>
     *                       or <b><code>false</code></b> (the default is <b><code>true</code></b>)
     *                       Configures the driver to send all stream data at execution
     *                       (<b><code>true</code></b>), or to send stream data in chunks
     *                       (<b><code>false</code></b>). By default, the value is set to
     *                       <b><code>true</code></b>. For more information, see <code>sqlsrv_send_stream_data()
     *                       </code>.   Scrollable SQLSRV_CURSOR_FORWARD, SQLSRV_CURSOR_STATIC,
     *                       SQLSRV_CURSOR_DYNAMIC, or SQLSRV_CURSOR_KEYSET See Specifying a Cursor
     *                       Type and Selecting Rows in the Microsoft SQLSRV documentation.
     *
     * @throws SQLException
     *
     * @return $this retorna o proprio objeto
     */
    private function prepared($params = [], $options = []): static
    {
        if (empty($this->sqlRead)) {
            throw new SQLException('Select não iniciado.');
        }

        $orderbyString = empty($this->orderFields) ? '' : " order by {$this->orderFields} ";
        $paginationString = empty($this->pagination) ? '' : " LIMIT  {$this->pagination['start']}, {$this->pagination['rowsCount']} ";

        $where = ' ';
        if (!empty($this->filters)) {
            $where .= ' where ';
            $where .= implode(' and ', $this->filters);

            $params = $this->args;
        } elseif (empty($params) && !empty($this->args)) {
            $params = $this->args;
        }
        if (!!trim($this->groupFields)){
            $groupbyString = " group by {$this->groupFields} ";
        } else {
            $groupbyString = '';
        }

        $this->connection->query($this->sqlRead.$where.' '.$groupbyString.' '.$orderbyString.' '.$paginationString, $params, $options);

        return $this;
    }

    private function groupBy(string $field): static
    {
        $this->groupFields = $field;

        return $this;
    
    }
}
