<?php

/**
 * A interface Builder declara etapas de construção do produto que são comuns a todos os tipos de builders.
 */
interface BuilderImposto
{
    public function calculaImposto(): float;
    public function calculaImpostoAlimentos(): float;
    public function calculaImpostoMedicamentos(): float;
}

/*
 * Builders Concretos provém diferentes implementações das etapas de construção.
 * Builders concretos podem produzir produtos que não seguem a interface comum.
 */
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

    public function calculaImpostoAlimentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) - 1;
    }

    public function calculaImpostoMedicamentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) + 2.5;
    }
}

class ConcreteCofins implements BuilderImposto
{
    public const ALIQUOTA = 0.03;
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

    public function calculaImpostoAlimentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) - 1;
    }

    public function calculaImpostoMedicamentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) + 2.5;
    }
}

class ConcretePis implements BuilderImposto
{
    public const ALIQUOTA = 0.10;
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

    public function calculaImpostoAlimentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) - 1;
    }

    public function calculaImpostoMedicamentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) + 2.5;
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

    public function calculaImpostoAlimentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) - 1;
    }

    public function calculaImpostoMedicamentos(): float
    {
        return ($this->imposto->baseDeCalculo * self::ALIQUOTA) + 2.5;
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

/**
 * Produtos são os objetos resultantes. 
 * Produtos construídos por diferentes builders não precisam pertencer a mesma interface ou hierarquia da classe.
 */
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

/**
 * A classe Diretor define a ordem na qual as etapas de construção são chamadas, 
 * então você pode criar e reutilizar configurações específicas de produtos.
 */
class Director
{
    public function calculaImpostoImportacao(BuilderImposto $builder)
    {
        $builder->calculaImposto();
    }

    public function calculaImpostoMedicamentos(BuilderImposto $builder)
    {
        $builder->calculaImpostoMedicamentos();
    }

    public function calculaImpostoAlimentos(BuilderImposto $builder)
    {
        $builder->calculaImpostoAlimentos();
    }
}

class Client
{
    public function venda()
    {
        /**
         * Um monte de coisas antes de calcular o imposto
         */
        $director = new Director();
        $builder = new ConcreteISS();
        $impostoAlimento = $director->calculaImpostoAlimentos($builder);
    }

}
