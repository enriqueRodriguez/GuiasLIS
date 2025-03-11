<?php
//Definición de la clase empleado
class empleado
{
    //Estableciendo las propiedades de la clase
    private static $idEmpleado = 0;
    private $nombre;
    private $apellido;
    private $isss;
    private $renta;
    private $afp;
    private $sueldoNominal;
    private $sueldoLiquido;
    private $pagoxhoraextra;
    private $prestamo; // Propiedad para manejar el préstamo
    //Declaración de constantes para los descuentos del empleado
    //Se inicializan porque pertenecen a la clase
    const descISSS = 0.03;
    const descRENTA = 0.075;
    const descAFP = 0.0625;
    //Constructor de la clase
    function __construct()
    {
        self::$idEmpleado++;
        $this->nombre = "";
        $this->apellido = "";
        $this->sueldoLiquido = 0.0;
        $this->pagoxhoraextra = 0.0;
        $this->prestamo = 0.0; // Inicialización del préstamo
    }
    //Destructor de la clase
    function __destruct()
    {
        echo "<p class=\"msg\">El salario y descuentos del empleado han sido calculados.</p>";
        $backlink = "<a class=\"a-btn\" href=\"sueldoneto.php\">";
        $backlink .= "<span class=\"a-btn-text\">Calcular salario </span>";
        $backlink .= "<span class=\"a-btn-slide-text\">a otro empleado</span>";
        $backlink .= "<span class=\"a-btn-slide-icon\"></span>";
        $backlink .= "</a>";
        echo $backlink;
    }

    //Métodos de la clase empleado
    function obtenerSalarioNeto(
        $nombre,
        $apellido,
        $salario,
        $horasextras,
        $pagoxhoraextra = 0.0,
        $prestamo = 0.0 // Nuevo parámetro para el préstamo
    ) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->pagoxhoraextra = $horasextras * $pagoxhoraextra;
        $this->sueldoNominal = $salario;
        $this->prestamo = $prestamo; // Asignación del valor del préstamo

        if ($this->pagoxhoraextra > 0) {
            $this->isss = ($salario + $this->pagoxhoraextra) * self::descISSS;
            $this->afp = ($salario + $this->pagoxhoraextra) * self::descAFP;
        } else {
            $this->isss = $salario * self::descISSS;
            $this->afp = $salario * self::descAFP;
        }
        $salariocondescuento = $this->sueldoNominal - ($this->isss + $this->afp);
        //De acuerdo a criterios del Ministerio de Hacienda
        //el descuento de la renta varía según el ingreso percibido
        if ($salariocondescuento > 2038.10) {
            $this->renta = $salariocondescuento * 0.3;
        } elseif ($salariocondescuento > 895.24 && $salariocondescuento <= 2038.10) {
            $this->renta = $salariocondescuento * 0.2;
        } elseif ($salariocondescuento > 472.00 && $salariocondescuento <= 895.24) {
            $this->renta = $salariocondescuento * 0.1;
        } elseif ($salariocondescuento > 0 && $salariocondescuento <= 472.00) {
            $this->renta = 0.0;
        }
        /* else {
 //Significa que el salario obtenido es negativo
 } */
        $this->sueldoNominal = $salario;
        // Incluir el descuento por préstamo en el cálculo del sueldo líquido
        $this->sueldoLiquido = $this->sueldoNominal + $this->pagoxhoraextra - ($this->isss + $this->afp + $this->renta + $this->prestamo);
        $this->imprimirBoletaPago();
    }
    function imprimirBoletaPago()
    {
        $tabla = "<table class='table '><tr>";
        $tabla .= "<td>Id empleado: </td>";
        $tabla .= "<td>" . self::$idEmpleado . "</td></tr>";
        $tabla .= "<tr><td>Nombre empleado: </td>\n";
        $tabla .= "<td>" . $this->nombre . " " . $this->apellido . "</td></tr>";
        $tabla .= "<tr><td>Salario nominal: </td>";
        $tabla .= "<td>$ " . number_format($this->sueldoNominal, 2, '.', ',') . "</td></tr>";
        $tabla .= "<tr><td>Salario por horas extras: </td>";
        $tabla .= "<td>$ " . number_format($this->pagoxhoraextra, 2, '.', ',') . "</td></tr>";
        $tabla .= "<tr class='success'><td colspan=\"2\"><h4>Descuentos</h4></td></tr>";
        $tabla .= "<tr ><td >Descuento seguro social: </td>";
        $tabla .= "<td>$ " . number_format($this->isss, 2, '.', ',') . "</td></tr>";
        $tabla .= "<tr><td>Descuento AFP: </td>";
        $tabla .= "<td>$ " . number_format($this->afp, 2, '.', ',') . "</td></tr>";
        $tabla .= "<tr><td>Descuento renta: </td>";
        $tabla .= "<td>$ " . number_format($this->renta, 2, '.', ',') . "</td></tr>";

        // Mostrar el descuento por préstamo solo si existe
        if ($this->prestamo > 0) {
            $tabla .= "<tr><td>Descuento por préstamo: </td>";
            $tabla .= "<td>$ " . number_format($this->prestamo, 2, '.', ',') . "</td></tr>";
        }

        // Calcular el total incluyendo el préstamo si existe
        $totalDescuentos = $this->isss + $this->afp + $this->renta + $this->prestamo;
        $tabla .= "<tr><td>Total descuentos: </td>";
        $tabla .= "<td>$ " . number_format($totalDescuentos, 2, '.', ',') . "</td></tr>";

        $tabla .= "<tr><td>Sueldo líquido a pagar: </td>";
        $tabla .= "<td> $" . number_format($this->sueldoLiquido, 2, '.', ',') . "</td></tr>";
        $tabla .= "</table>";
        echo $tabla;
    }
}
