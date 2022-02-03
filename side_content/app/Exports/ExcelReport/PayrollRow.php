<?php

namespace App\Exports\ExcelReport;

class PayrollRow
{

    //region Properties
    private string $empleado;
    private float $sueldo;
    private float $horas_extra;
    private float $bonos;
    private float $total;
    private string $obra;
    private string $banco;
    private string $cuenta;
    private string $clabe;
    private string $imss;
    private string $tipo;
    private string $firma;
    private string $comentarios;
    //endregion

    /**
     * @param string $empleado
     * @param float $sueldo
     * @param float $horas_extra
     * @param float $bonos
     * @param float $total
     * @param string $obra
     * @param string $banco
     * @param string $cuenta
     * @param string $clabe
     * @param string $imss
     * @param string $tipo
     * @param string $firma
     * @param string $comentarios
     */
    public function __construct(string $empleado, float $sueldo, float $horas_extra, float $bonos, float $total, string $obra, string $banco, string $cuenta, string $clabe, string $imss, string $tipo, string $firma, string $comentarios)
    {
        $this->empleado = $empleado;
        $this->sueldo = $sueldo;
        $this->horas_extra = $horas_extra;
        $this->bonos = $bonos;
        $this->total = $total;
        $this->obra = strtoupper($obra);
        $this->banco = $banco;
        $this->cuenta = $cuenta;
        $this->clabe = $clabe;
        $this->imss = $imss;
        $this->tipo = $tipo;
        $this->firma = $firma;
        $this->comentarios = $comentarios;
    }

    public static function head(): array
    {
        return [
            'Nombre Completo',
            'Sueldo',
            'Horas extra',
            //'Bonos',
            'Total',
            'Obra',
            'Banco',
            'Cuenta',
            'Clabe',
            'Imss',
            'Tipo',
            'Firma',
            'Comentarios'
        ];
    }

    public function toArray(){
        return [
            $this->empleado,
            $this->sueldo,
            $this->horas_extra,
            //$this->bonos,
            $this->total,
            $this->obra,
            $this->banco,
            $this->cuenta,
            $this->clabe,
            $this->imss,
            $this->tipo,
            $this->firma,
            $this->comentarios
        ];
    }

    //region Getters
    /**
     * @return string
     */
    public function getEmpleado(): string
    {
        return $this->empleado;
    }

    /**
     * @return float
     */
    public function getSueldo(): float
    {
        return $this->sueldo;
    }

    /**
     * @return float
     */
    public function getHorasExtra(): float
    {
        return $this->horas_extra;
    }

    /**
     * @return float
     */
    public function getBonos(): float
    {
        return $this->bonos;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @return string
     */
    public function getObra(): string
    {
        return $this->obra;
    }

    /**
     * @return string
     */
    public function getBanco(): string
    {
        return $this->banco;
    }

    /**
     * @return string
     */
    public function getCuenta(): string
    {
        return $this->cuenta;
    }

    /**
     * @return string
     */
    public function getClabe(): string
    {
        return $this->clabe;
    }

    /**
     * @return string
     */
    public function getImss(): string
    {
        return $this->imss;
    }

    /**
     * @return string
     */
    public function getTipo(): string
    {
        return $this->tipo;
    }

    /**
     * @return string
     */
    public function getFirma(): string
    {
        return $this->firma;
    }

    /**
     * @return string
     */
    public function getComentarios(): string
    {
        return $this->comentarios;
    }
    //endregion

}
