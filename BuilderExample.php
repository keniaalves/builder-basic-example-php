<?php

interface BuilderImposto
{
    public function calculaImposto(): float;
}

class ConcreteISS implements BuilderImposto
{
    public const ALIQUOTA = 0.04;
    private Imposto $imposto;

    public function __construct() {
        $this->reset();
    }

    function reset() : void {
        $this->imposto = new Imposto();
    }
    public function calculaImposto(): float
    {
        return $this->imposto->baseDeCalculo * self::ALIQUOTA;
    }
}

class ConcreteICMS implements BuilderImposto
{
    public const ALIQUOTA = 0.18;
    public const LISTA_CNAES = [
        123 => 1,00,
        456 => 2,45,
        789 => 0,55
    ];
    public string $estado;
    public string $cnae;
    private Imposto $imposto;

    public function __construct(string $estado, string $cnae) {
        $this->estado = $estado;
        $this->cnae = $cnae;
        $this->reset();
    }

    function reset() : void {
        $this->imposto = new Imposto();
    }

    public function calculaImposto(): float
    {
        return ($this->imposto->baseDeCalculo + $this->recuperaValorAdicionalCNAE())
            * self::ALIQUOTA * $this->recuperaAliquotaPeloEstado();
    }

    private function recuperaAliquotaPeloEstado()
    {
        switch ($this->estado) {
            case 'MG':
                return 0.1;
                break;
            case 'PE':
                return 0.2;
                break;
            case 'ES':
                return 0.3;
                break;
            //e assim por diante
            default:
                return 0;
        }
    }

    private function recuperaValorAdicionalCNAE()
    {
        //imagine uma lista gigante de cnaes ou uma busca no banco dessa lista
        if (isset(self::LISTA_CNAES[$this->cnae])) {
            return self::LISTA_CNAES[$this->cnae];
        }
    }
}

class Imposto
{
    public int $aliquota;
    public int $baseDeCalculo;
    public int $valorFinal;

    public function imprimeValorFinal()
    {
        echo "Valor total dos impostos a pagar: " . $this->valorFinal;
    }
}
