<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Convertir entre monedas</title>
    <link rel="stylesheet" href="fonts.css">
    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.
css" integrity="sha384-
Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-dark bg-primary">
            <span class="navbar-text">
                <h1>Equivalencias entre monedas</h1>
            </span>
        </nav>
    </header>
    <?php
    $conversion =
        array("aDolaresBelice", "aQuetzal", "aLempira", "aCordova", "aColon");
    $precios = range(10, 1000, 100);
    //Funciones para usar como funciones variables
    //y hacer la tabla de llamadas que permitirá
    //invocar a las funciones del array con retrollamadas
    function aDolaresBelice($dato)
    {
        return sprintf("%02.2f", $dato * 2.01);
    }
    function aQuetzal($dato)
    {
        return sprintf("%02.2f", $dato * 07.72);
    }
    function aLempira($dato)
    {
        return sprintf("%02.2f", $dato * 25.60);
    }
    function aCordova($dato)
    {
        return sprintf("%02.2f", $dato * 36.75);
    }
    function aColon($dato)
    {
        return sprintf("%02.2f", $dato * 505.68);
    }
    ?>
    <section>
        <article>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Dolares (sv)</th>
                        <th>Dólares (bz)</th>
                        <th>Quetzales (gt)</th>
                        <th>Lempiras (ho)</th>
                        <th>Córdovas (ni)</th>
                        <th>Colones (cr)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < sizeof($precios); $i++) {
                        if ($i % 2 == 0) {
                            echo "\t<tr>\n";
                        } else {
                            echo "\t<tr class=\"odd\">\n";
                        }
                        echo "\t\t<td>\t\t&cent; " . $precios[$i] . "\t\t</td>\n\t\t";
                        for ($j = 0; $j < sizeof($conversion); $j++):
                            $resultado = $conversion[$j];
                            switch ($resultado):
                                case "aDolaresBelice":
                                    $signo = "BZ$";
                                    break;
                                case "aQuetzal":
                                    $signo = "Q";
                                    break;
                                case "aLempira":
                                    $signo = "L";
                                    break;
                                case "aCordova":
                                    $signo = "C$";
                                    break;
                                case "aColon":
                                    $signo = "&cent;";
                                    break;
                            endswitch;
                            echo "<td>\t\t$signo " .
                                number_format($resultado($precios[$i]), 3, ".", ",") . "\t\t</td>\n\t";
                        endfor;
                        echo "\t</tr>\n";
                    }
                    ?>
            </table>
        </article>
    </section>
</body>

</html>