<?php
namespace WilliamNovak\Cnpj;

use BadMethodCallException;

class Cnpj {

    const INVALID_CNPJ   = 'cnpj inválido';
    const VALID_CNPJ     = 'cnpj válido';
    const CNPJ_FOUND     = 'cnpj encontrado';
    const NOT_FOUND_CNPJ = 'cnpj não encontrado';
    const UNPROCESSED    = 'consulta não realizada';
    const INVALID_RESULT = 'resultado inválido';

    private $url = 'http://receitaws.com.br/v1/cnpj/';

    private $cnpj = null;

    protected $fillable = array(
        'tividade_principal',
        'data_situacao',
        'complemento',
        'tipo',
        'nome',
        'telefone',
        'atividades_secundarias',
        'situacao',
        'bairro',
        'logradouro',
        'numero',
        'cep',
        'municipio',
        'uf',
        'abertura',
        'natureza_juridica',
        'fantasia',
        'cnpj',
        'ultima_atualizacao',
        'status',
        'email',
        'efr',
        'motivo_situacao',
        'situacao_especial',
        'data_situacao_especial'
    );

    private function getFillable()
    {
        return $this->fillable;
    }

    public function setUrl($value)
    {
        $this->url = (string) $value;
        return $this;
    }

    private function getUrl()
    {
        return $this->url;
    }

    public function setCnpj($value)
    {
        if (!is_null($value))
        {
          $this->cnpj = (string) preg_replace("/[^0-9\s]/", "", trim($value));
        }
        return $this;
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function cnpjIsValid($cnpj = null)
    {
        if (is_null($cnpj))
        {
            $cnpj = $this->getCnpj();
        } else {
            $cnpj = $this->setCnpj($cnpj)->getCnpj();
        }

        if (is_null($cnpj))
        {
            return false;
        }

        if (strlen($cnpj) != 14)
        {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
          $soma += $cnpj{$i} * $j;
          $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
        {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    private $content = array();

    private function setContent($value)
    {
        if (is_array($value))
        {
            $this->content = $value;
        }
        return $this;
    }

    private function getContent()
    {
        return $this->content;
    }

    public function get($returnType = null)
    {
        if (!is_null($returnType))
        {
            $this->setReturnType($returnType);
        }
        $cnpj = $this->getCnpj();
        $status = false;
        if ($this->cnpjIsValid())
        {
            $startTime = microtime(true);
            $content = file_get_contents($this->url . $cnpj);
            if ($this->isValidJson($content) === true)
            {
                $data = json_decode($content, true);
                $this->setContent($data);
                if ( strtolower($data['status']) == 'ok')
                {
                    $status = true;
                    $this->setResultMessage(self::CNPJ_FOUND);
                } else {
                    $this->setResultMessage(self::NOT_FOUND_CNPJ);
                }
            } else {
                $this->setResultMessage(self::INVALID_RESULT);
            }
            $endTime = microtime(true);
            $this->queryTime = number_format($endTime - $startTime, 5);

        } else {
            $this->setResultMessage(self::INVALID_CNPJ);
        }
        $this->convert($status);
        return $this->getResult();
    }

    private $returnType = 'array';

    public function setReturnType($value)
    {
        if ($value == 'array' || $value == 'json')
        {
            $this->returnType = (string) $value;
        }
        return $this;
    }

    private function getReturnType()
    {
        return $this->returnType;
    }

    private $result;

    private function convert($status = false)
    {
        $result = array(
            'status'     => (bool) $status,
            'alert'      => (string) $this->getResultMessage(),
            'data'       => (array) $this->getContent(),
            'queryTime'  => $this->queryTime
        );
        if ($this->getReturnType() == 'json')
        {
            $this->result = (string) json_encode($result);
        } else {
            $this->result = (array) $result;
        }
        return $this;
    }

    private function getResult()
    {
        return $this->result;
    }

    private function isValidJson($value = null)
    {
        if (is_null($value))
        {
            return false;
        } else {
            json_decode($value);
            return (json_last_error()===JSON_ERROR_NONE);
        }
    }

    private $resultMessage = self::UNPROCESSED;

    private function setResultMessage($value)
    {
        $this->resultMessage = $value;
        return $this;
    }

    public function getResultMessage()
    {
        return $this->resultMessage;
    }

    private $queryTime = 0;

    public function test()
    {
        return __CLASS__ . __FUNCTION__;
    }

    /**
     * Changing detection type to extended.
     *
     * @inherit
     */
    public function __call($name, $arguments)
    {
        // Make sure the name starts with 'is', otherwise
        if (substr($name, 0, 2) != 'is')
        {
            throw new BadMethodCallException("No such method exists: $name");
        }

        $this->setDetectionType(self::DETECTION_TYPE_EXTENDED);

        $key = substr($name, 2);

        return $this->matchUAAgainstKey($key);
    }

}
