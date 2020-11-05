<?php

error_reporting(E_ALL);
$array_codigos = $_SESSION["user_stores"];
/*   */
/* print_r($array_codigos); */
$tipo_usuario = $_SESSION["user_type"];
$current_landing = '';
$current_landing = '';



function arrayCastRecursive($array)
{
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = arrayCastRecursive($value);
            }
            if ($value instanceof stdClass) {
                $array[$key] = arrayCastRecursive((array) $value);
            }
        }
    }
    if ($array instanceof stdClass) {
        return arrayCastRecursive((array) $array);
    }
    return $array;
}

function getData($array, $titulo)
{
    //Datos totales
    $total_nuevos = 0;
    $total_contactado = 0;
    $total_cotizado = 0;
    $total_facturado = 0;
    $total_cancelado = 0;
    $total_gestionado = 0;

    foreach ($array as $lead) {
        //Datos del total de leads
        if ($lead['estado'] == 'Nuevo') {
            $total_nuevos++;
        } else if ($lead['estado'] == 'Gestionado') {
            $total_gestionado++;
        } else if ($lead['estado'] == 'Contactado') {
            $total_contactado++;
        } else if ($lead['estado'] == 'Cotizado') {
            $total_cotizado++;
        } else if ($lead['estado'] == 'Facturado') {
            $total_facturado++;
        } else if ($lead['estado'] == 'Cancelado') {
            $total_cancelado++;
        }
    }

    $datosPerLanding = array(
        'titulo' => $titulo,
        'total_leads' => count($array),
        'total_nuevos' => $total_nuevos,
        'total_contactado' => $total_contactado,
        'total_cotizado' => $total_cotizado,
        'total_facturado' => $total_facturado,
        'total_cancelado' => $total_cancelado,
        'total_gestionado' => $total_gestionado,
    );

    return $datosPerLanding;
}
$fecha_inicio = date('Y-m-d');
$fecha_end = date('Y-m-d');
$optionDashboard = 'Por Campaña y Websites';
if (isset($_POST['start']) && $_POST['start'] != '') {
    $old_date = explode('/', $_POST['start']);
    $fecha_inicio = $old_date[2] . '-' . $old_date[0] . '-' . $old_date[1];
}
if (isset($_POST['end']) && $_POST['end'] != '') {
    $old_date = explode('/', $_POST['end']);
    $fecha_end = $old_date[2] . '-' . $old_date[0] . '-' . $old_date[1];
}

if (isset($_POST['optionDashboard'])) {
    $optionDashboard = $_POST['optionDashboard'];
}


$dataQuadro = '';
$isAdmin = false;

// Leads a traer



$url_api = "https://cotizadorderco.com/clients/totales";
$url_api2 = "https://cotizadorderco.com/clients/totalesGeneral";
$curl = curl_init();

$arrayLinks = array(
    'https://derco.com.pe/catalogo-derco/',
    'https://derco.com.pe/dercoutlet/',
    'https://derco.com.pe/cybergo/',
);

curl_setopt_array($curl, array(
    CURLOPT_URL => $url_api2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\n \"date1\": \"" . $fecha_inicio . "\",\n    \"date2\": \"" . $fecha_end . "\"\n}",
    //CURLOPT_POSTFIELDS => $params,
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json"
    ),
));
$response = curl_exec($curl);
//Respuesta del endpoint
$totales_response = json_decode($response);
//Conversion a array
$nuevo_array = arrayCastRecursive($totales_response);

$arrayExcel = array();

curl_close($curl);

// ************************************************************

if ($_SESSION['user_type'] == 'Administrador') {
    $isAdmin = true;

    if ($optionDashboard == 'Por Campaña y Websites') {
        //Division por Campaña (Link)
        $arrayCatalogoDerco = array();
        $arrayCyberGO = array();
        $arrayDercoOulet = array();
        $arraySuzuki = array();
        $arrayMazda = array();
        $arrayRenault = array();
        $arrayChangan = array();
        $arrayHaval = array();
        $arrayJac = array();
        $arrayCitroen = array();
        $arrayGreatWall = array();
        $arrayExceptions = array();
        foreach ($nuevo_array as $lead) {
            if ($lead['url1_w2l'] != '') {
                if ($lead['url1_w2l'] == 'https://derco.com.pe/catalogo-derco/') {
                    array_push($arrayCatalogoDerco, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://derco.com.pe/dercoutlet/' || $lead['url1_w2l'] == 'https://derco.com.pe/dercoutletdc/') {
                    array_push($arrayDercoOulet, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://derco.com.pe/cybergo/') {
                    array_push($arrayCyberGO, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://autos.suzuki.com.pe') {
                    array_push($arraySuzuki, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.mazda.pe') {
                    array_push($arrayMazda, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.renault.pe') {
                    array_push($arrayRenault, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.changan.com.pe/') {
                    array_push($arrayChangan, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.haval.com.pe/' || $lead['url1_w2l'] == 'https://www.haval.com.pe/cotizador/') {
                    array_push($arrayHaval, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.jac.pe/') {
                    array_push($arrayJac, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://citroen.com.pe/ofertas-2/') {
                    array_push($arrayCitroen, $lead);
                    array_push($arrayExcel, $lead);
                } else if ($lead['url1_w2l'] == 'https://www.greatwall.com.pe') {
                    array_push($arrayGreatWall, $lead);
                    array_push($arrayExcel, $lead);
                } else {
                    array_push($arrayExceptions, $lead);
                }
            }
        }

        /* print_r($arrayExceptions); */
        $arrayTablaFinal = array();
        array_push($arrayTablaFinal, getData($nuevo_array, 'Total de Leads General'));
        array_push($arrayTablaFinal, getData($arrayCatalogoDerco, 'Catálogo Derco'));
        array_push($arrayTablaFinal, getData($arrayDercoOulet, 'DercoOulet'));
        array_push($arrayTablaFinal, getData($arrayCyberGO, 'Cyber GO'));
        if (count($arraySuzuki) > 0) array_push($arrayTablaFinal, getData($arraySuzuki, 'Suzuki'));
        if (count($arrayMazda) > 0) array_push($arrayTablaFinal, getData($arrayMazda, 'Mazda'));
        if (count($arrayRenault) > 0) array_push($arrayTablaFinal, getData($arrayRenault, 'Renault'));
        if (count($arrayChangan) > 0) array_push($arrayTablaFinal, getData($arrayChangan, 'Changan'));
        if (count($arrayHaval) > 0) array_push($arrayTablaFinal, getData($arrayHaval, 'Haval'));
        if (count($arrayJac) > 0) array_push($arrayTablaFinal, getData($arrayJac, 'Jac'));
        if (count($arrayCitroen) > 0) array_push($arrayTablaFinal, getData($arrayCitroen, 'Citroën'));
        if (count($arrayGreatWall) > 0) array_push($arrayTablaFinal, getData($arrayGreatWall, 'Great Wall'));

        $contadorRows = 0;
        foreach ($arrayTablaFinal as $data) {
            $catalogo_conversiones = 0;
            if ($data['total_leads'] != 0) {
                $catalogo_conversiones = $data['total_facturado'] / $data['total_leads'];
            }
            $dataQuadro .= '
            <tr>
            <th scope="row">' . ($contadorRows + 1) . '</th>
            <td>' . $data['titulo'] . '</td>
            <td>' . $data['total_leads'] . '</td>
            <td>' . number_format($catalogo_conversiones, 5, ',', '') . '</td>
            <td>' . $data['total_nuevos'] . '</td>
            <td>' . $data['total_contactado'] . '</td>
            <td>' . $data['total_cotizado'] . '</td>
            <td>' . $data['total_facturado'] . '</td>
            <td>' . $data['total_cancelado'] . '</td>
            <td>' . $data['total_gestionado'] . '</td>
            </tr>
            ';
            $contadorRows++;
        }
    } else {
        $count = 0;
        $arrayConcesionarios = array();

        foreach ($array_codigos as $store_code => $key) {
            $arrayConcesionarios[$array_codigos[$store_code]['concesionario']] = array();
        }

        $arrayExcepciones = array();

        foreach ($nuevo_array as $lead) {
            if ($lead['store'] !== '') {
                $band = false;
                foreach ($array_codigos as $store_code => $key) {
                    if (in_array($lead['store'], array_column($array_codigos[$store_code]['tiendas'], 'code'))) {
                        array_push($arrayConcesionarios[$array_codigos[$store_code]['concesionario']], $lead);
                        array_push($arrayExcel, $lead);
                        $band = true;
                    }
                }
                if (!$band) {
                    array_push($arrayExcepciones, $lead);
                }
            }
        }

        /* print_r($arrayExcepciones); */

        $arrayTablaFinal = array();
        array_push($arrayTablaFinal, getData($nuevo_array, 'Total de Leads General'));
        foreach ($arrayConcesionarios as $concesionarioLeads => $key) {
            array_push($arrayTablaFinal, getData($arrayConcesionarios[$concesionarioLeads], $concesionarioLeads));
        }

        $contadorRows = 0;
        foreach ($arrayTablaFinal as $data) {
            $catalogo_conversiones = 0;
            if ($data['total_leads'] != 0) {
                $catalogo_conversiones = $data['total_facturado'] / $data['total_leads'];
            }
            $dataQuadro .= '
            <tr>
            <th scope="row">' . ($contadorRows + 1) . '</th>
            <td>' . $data['titulo'] . '</td>
            <td>' . $data['total_leads'] . '</td>
            <td>' . number_format($catalogo_conversiones, 5, ',', '') . '</td>
            <td>' . $data['total_nuevos'] . '</td>
            <td>' . $data['total_contactado'] . '</td>
            <td>' . $data['total_cotizado'] . '</td>
            <td>' . $data['total_facturado'] . '</td>
            <td>' . $data['total_cancelado'] . '</td>
            <td>' . $data['total_gestionado'] . '</td>
            </tr>
            ';
            $contadorRows++;
        }
    }
} else {

    if ($optionDashboard == 'Por Campaña y Websites') {
        //Division por Campaña (Link)
        $arrayTotales = array();
        $arrayCatalogoDerco = array();
        $arrayCyberGO = array();
        $arrayDercoOulet = array();
        $arraySuzuki = array();
        $arrayMazda = array();
        $arrayRenault = array();
        $arrayChangan = array();
        $arrayHaval = array();
        $arrayJac = array();
        $arrayCitroen = array();
        $arrayGreatWall = array();
        $arrayExceptions = array();
        foreach ($nuevo_array as $lead) {
            if ($lead['url1_w2l'] != '' && $lead['store'] != '') {
                foreach ($array_codigos as $store_code => $key) {
                    if ($lead['store'] == $array_codigos[$store_code]['store_code']) {
                        if ($lead['url1_w2l'] == 'https://derco.com.pe/catalogo-derco/') {
                            array_push($arrayCatalogoDerco, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://derco.com.pe/dercoutlet/' || $lead['url1_w2l'] == 'https://derco.com.pe/dercoutletdc/') {
                            array_push($arrayDercoOulet, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://derco.com.pe/cybergo/') {
                            array_push($arrayCyberGO, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://autos.suzuki.com.pe') {
                            array_push($arraySuzuki, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.mazda.pe') {
                            array_push($arrayMazda, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.renault.pe') {
                            array_push($arrayRenault, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.changan.com.pe/') {
                            array_push($arrayChangan, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.haval.com.pe/' || $lead['url1_w2l'] == 'https://www.haval.com.pe/cotizador/') {
                            array_push($arrayHaval, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.jac.pe/') {
                            array_push($arrayJac, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://citroen.com.pe/ofertas-2/') {
                            array_push($arrayCitroen, $lead);
                            array_push($arrayExcel, $lead);
                        } else if ($lead['url1_w2l'] == 'https://www.greatwall.com.pe') {
                            array_push($arrayGreatWall, $lead);
                            array_push($arrayExcel, $lead);
                        } else {
                            array_push($arrayExceptions, $lead);
                        }
                        array_push($arrayTotales, $lead);
                    }
                }
            }
        }
        /* print_r($arrayTotales); */
        /* print_r($arrayExceptions); */
        $arrayTablaFinal = array();
        array_push($arrayTablaFinal, getData($arrayTotales, 'Total de Leads General'));
        array_push($arrayTablaFinal, getData($arrayCatalogoDerco, 'Catálogo Derco'));
        array_push($arrayTablaFinal, getData($arrayDercoOulet, 'DercoOulet'));
        array_push($arrayTablaFinal, getData($arrayCyberGO, 'Cyber GO'));
        if (count($arraySuzuki) > 0) array_push($arrayTablaFinal, getData($arraySuzuki, 'Suzuki'));
        if (count($arrayMazda) > 0) array_push($arrayTablaFinal, getData($arrayMazda, 'Mazda'));
        if (count($arrayRenault) > 0) array_push($arrayTablaFinal, getData($arrayRenault, 'Renault'));
        if (count($arrayChangan) > 0) array_push($arrayTablaFinal, getData($arrayChangan, 'Changan'));
        if (count($arrayHaval) > 0) array_push($arrayTablaFinal, getData($arrayHaval, 'Haval'));
        if (count($arrayJac) > 0) array_push($arrayTablaFinal, getData($arrayJac, 'Jac'));
        if (count($arrayCitroen) > 0) array_push($arrayTablaFinal, getData($arrayCitroen, 'Citroën'));
        if (count($arrayGreatWall) > 0) array_push($arrayTablaFinal, getData($arrayGreatWall, 'Great Wall'));

        $contadorRows = 0;
        foreach ($arrayTablaFinal as $data) {
            $catalogo_conversiones = 0;
            if ($data['total_leads'] != 0) {
                $catalogo_conversiones = $data['total_facturado'] / $data['total_leads'];
            }
            $dataQuadro .= '
            <tr>
            <th scope="row">' . ($contadorRows + 1) . '</th>
            <td>' . $data['titulo'] . '</td>
            <td>' . $data['total_leads'] . '</td>
            <td>' . number_format($catalogo_conversiones, 5, ',', '') . '</td>
            <td>' . $data['total_nuevos'] . '</td>
            <td>' . $data['total_contactado'] . '</td>
            <td>' . $data['total_cotizado'] . '</td>
            <td>' . $data['total_facturado'] . '</td>
            <td>' . $data['total_cancelado'] . '</td>
            <td>' . $data['total_gestionado'] . '</td>
            </tr>
            ';
            $contadorRows++;
        }
    } else {
        $arrayTiendas = array();
        $arrayTiendas['Total de Leads General'] = array();

        foreach ($array_codigos as $store_code => $key) {
            $arrayTiendas[$array_codigos[$store_code]['store_name']] = array();
        }

        foreach ($nuevo_array as $lead) {
            if ($lead['store'] !== '') {
                foreach ($array_codigos as $store_code => $key) {
                    if ($lead['store'] == $array_codigos[$store_code]['store_code']) {
                        array_push($arrayTiendas[$array_codigos[$store_code]['store_name']], $lead);
                        array_push($arrayExcel, $lead);
                        array_push($arrayTiendas['Total de Leads General'], $lead);
                    }
                }
            }
        }

        $arrayTablaFinal = array();
        foreach ($arrayTiendas as $tiendaLead => $key) {
            array_push($arrayTablaFinal, getData($arrayTiendas[$tiendaLead], $tiendaLead));
        }

        $contadorRows = 0;
        foreach ($arrayTablaFinal as $data) {
            $catalogo_conversiones = 0;
            if ($data['total_leads'] != 0) {
                $catalogo_conversiones = $data['total_facturado'] / $data['total_leads'];
            }
            $dataQuadro .= '
            <tr>
            <th scope="row">' . ($contadorRows + 1) . '</th>
            <td>' . $data['titulo'] . '</td>
            <td>' . $data['total_leads'] . '</td>
            <td>' . number_format($catalogo_conversiones, 5, ',', '') . '</td>
            <td>' . $data['total_nuevos'] . '</td>
            <td>' . $data['total_contactado'] . '</td>
            <td>' . $data['total_cotizado'] . '</td>
            <td>' . $data['total_facturado'] . '</td>
            <td>' . $data['total_cancelado'] . '</td>
            <td>' . $data['total_gestionado'] . '</td>
            </tr>
            ';
            $contadorRows++;
        }
    }
}
?>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!--begin::Dashboard-->
        <div class="row">
            <div class="col-sm-5 text-left mb-5">
                <form action="index.php?page=dashboard" method="POST">
                    <select class=" btn-group bootstrap-select bs-select form-control" name="optionDashboard" tabindex="-98">
                        <option selected disabled>Seleccione una opción</option>
                        <option><?php if ($isAdmin) echo 'Por Concesionarios';
                                else {
                                    echo 'Por Tienda';
                                } ?></option>
                        <option>Por Campaña y Websites</option>
                    </select>
                    <span>Rango de fechas:</span>
                    <div class="input-daterange input-group" id="kt_datepicker_5">
                        <input id="initDate" type="text" class="form-control" name="start" />
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="la la-ellipsis-h"></i>
                            </span>
                        </div>
                        <input id="endDate" type="text" class="form-control" name="end" />
                    </div>
                    <span class="form-text text-muted">Seleccione un rango de fechas</span>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <div class="mt-6">
                        <h5 style="<?php if ($isAdmin) echo 'display:block';
                                    else {
                                        echo 'display:none';
                                    } ?>">
                            Opción: <?= $optionDashboard; ?>
                        </h5>
                        <h5>
                            Fecha Inicio: <?= $fecha_inicio; ?>
                        </h5>
                        <h5>
                            Fecha Fin: <?= $fecha_end; ?>
                        </h5>
                    </div>
                </form>
            </div>
            <div class="col-sm-5 text-right">
                <button type="button" class="btn btn-success" onclick="downloadExcelTotales()">Descargar Excel</button>
            </div>
        </div>

        <!--begin::Row-->
        <div class="row">
            <!-- CATALOGO DERCO -->
            <div class="table-responsive">
                <table class="table text-center">
                    <thead>
                        <tr class="table-dark">
                            <th scope="col">#</th>
                            <th scope="col">Título</th>
                            <th scope="col">Total</th>
                            <th scope="col">% Conversiones</th>
                            <th scope="col">Por gestionar</th>
                            <th scope="col">Contactado - cerrado</th>
                            <th scope="col">Contactado - cotizado</th>
                            <th scope="col">Reservado</th>
                            <th scope="col">Facturados</th>
                            <th scope="col">No Contactado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $dataQuadro; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Row-->
        <!--end::Dashboard-->
    </div>
    <!--end::Container-->

    <script type="text/javascript" src="assets/js/pages/custom/xlsx/xlsx.full.min.js"></script>

    <script>
        var clients = JSON.parse('<?= json_encode($arrayExcel) ?>');
        console.log('Entro aqui')

        /* let array_leadsnew = array_leads.map(({
            updateFecha,
            estado,
            __v,
            terms,
            updated_at,
            ...keepFields
        }) => {
            return {
                ...keepFields,
                ...{estado},
                ...{updateFecha}
            }
        }) */

        var array_leadsnew = []
        for (var i = 0; i < clients.length; i++) {
            var d = new Date(clients[i].created_at);
            var n = d.toLocaleString('es-PE');
            array_leadsnew.push({
                id: clients[i]._id,
                id_w2l: clients[i].id_w2l,
                rut_w2l: clients[i].rut_w2l,
                first_name: clients[i].first_name,
                last_name: clients[i].last_name,
                fone1_w2l: clients[i].fone1_w2l,
                email: clients[i].email,
                state: clients[i].state,
                url1_w2l: clients[i].url1_w2l,
                url2_w2l: clients[i].url2_w2l,
                brand_w2l: clients[i].brand_w2l,
                model_w2l: clients[i].model_w2l,
                version_w2l: clients[i].version_w2l,
                sap_w2l: clients[i].sap_w2l,
                price_w2l: clients[i].price_w2l,
                local_w2l: clients[i].local_w2l,
                cod_origen2_w2l: clients[i].cod_origen2_w2l,
                store: clients[i].store,
                terms: clients[i].terms,
                tipo_documento: clients[i].tipo_documento,
                razon_social: clients[i].razon_social,
                direccion: clients[i].direccion,
                distrito: clients[i].distrito,
                estado: clients[i].estado,
                created_at: n,
            })
        }

        console.log(array_leadsnew)

        function downloadExcelTotales() {

            var createXLSLFormatObj = [];
            var xlsHeader = ['ID WEB', 'ID W2L', 'DNI / RUC', 'NOMBRES', 'APELLIDOS', 'CELULAR', 'E-MAIL', 'ESTADO', 'URL FUENTE', 'URL PRINCIPAL', 'MARCA', 'MODELO', 'VERSIÓN', 'CÓDIGO SAP', 'PRECIO', 'LOCAL', 'CÓDIGO WEB', 'CÓDIGO DE TIENDA', 'LEGALES', 'TIPO DE DOCUMENTO', 'RAZÓN SOCIAL', 'DIRECCIÓN', 'DISTRITO', 'ESTADO', 'FECHA DE REGISTRO'];

            var xlsRows = [];
            xlsRows.push(array_leadsnew);

            createXLSLFormatObj.push(xlsHeader);

            var count = 0;


            $.each(xlsRows[0], function(index, value) {
                var innerRowData = [];

                $.each(value, function(ind, val) {
                    innerRowData.push(val);
                });

                count++;

                createXLSLFormatObj.push(innerRowData);
            });

            var filename = "CES_LEADS.xlsx";
            var ws_name = "Reporte de LEADS";

            if (typeof console !== 'undefined') console.log(new Date());
            var wb = XLSX.utils.book_new(),
                ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);


            XLSX.utils.book_append_sheet(wb, ws, ws_name);


            if (typeof console !== 'undefined') console.log(new Date());
            XLSX.writeFile(wb, filename);
            if (typeof console !== 'undefined') console.log(new Date());
        }
    </script>

</div>
<!--end::Entry-->

<!--end::Viejo Cuadro de Dashboard-->
<?php

/*
$oldDataQuadro = '
<div class="col-lg-6 col-xl-4">
        <!--begin::Mixed Widget 1-->
        <div class="card card-custom bg-gray-100 card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header border-0 bg-danger py-5">
                <h3 class="card-title font-weight-bolder text-white">
                '.$data['titulo'].'
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body p-0 position-relative overflow-hidden">
                <!--begin::Chart-->
                <div class="card-body card-rounded-bottom bg-danger" style="height: 200px">
                    <div class="font-weight-bold text-white font-size-sm">
                        <span class="font-size-h2 mr-2">'.number_format($catalogo_conversiones, 5, ',', '').' %</span> de conversiones
                    </div>
                    <div class="progress progress-xs mt-7 bg-white-o-90">
                        <div class="progress-bar bg-white" role="progressbar" style="width: '.$catalogo_conversiones.'%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Chart-->
                <!--begin::Stats-->
                <div class="card-spacer mt-n25">
                    <!--begin::Row-->
                    <div class="row m-0">
                        <div class="col bg-light-info px-6 py-8 rounded-xl mr-7 mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-info d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
                                        <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
                                        <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
                                        <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-info font-weight-bold font-size-h6">Total</span>
                            <span class="card-title font-weight-bolder text-info font-size-h2 mb-0 d-block">'.$data['total_leads'].'</span>
                        </div>
                        <div class="col bg-primary px-6 py-8 rounded-xl mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-white d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-white font-weight-bold font-size-h6 mt-2">Nuevos</span>
                            <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 d-block">'.$data['total_nuevos'].'</span>
                        </div>
                    </div>
                    <!--end::Row-->

                    <!--begin::Row-->
                    <div class="row m-0">
                        <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M13.0799676,14.7839934 L15.2839934,12.5799676 C15.8927139,11.9712471 16.0436229,11.0413042 15.6586342,10.2713269 L15.5337539,10.0215663 C15.1487653,9.25158901 15.2996742,8.3216461 15.9083948,7.71292558 L18.6411989,4.98012149 C18.836461,4.78485934 19.1530435,4.78485934 19.3483056,4.98012149 C19.3863063,5.01812215 19.4179321,5.06200062 19.4419658,5.11006808 L20.5459415,7.31801948 C21.3904962,9.0071287 21.0594452,11.0471565 19.7240871,12.3825146 L13.7252616,18.3813401 C12.2717221,19.8348796 10.1217008,20.3424308 8.17157288,19.6923882 L5.75709327,18.8875616 C5.49512161,18.8002377 5.35354162,18.5170777 5.4408655,18.2551061 C5.46541191,18.1814669 5.50676633,18.114554 5.56165376,18.0596666 L8.21292558,15.4083948 C8.8216461,14.7996742 9.75158901,14.6487653 10.5215663,15.0337539 L10.7713269,15.1586342 C11.5413042,15.5436229 12.4712471,15.3927139 13.0799676,14.7839934 Z" fill="#000000"/>
                                        <path d="M14.1480759,6.00715131 L13.9566988,7.99797396 C12.4781389,7.8558405 11.0097207,8.36895892 9.93933983,9.43933983 C8.8724631,10.5062166 8.35911588,11.9685602 8.49664195,13.4426352 L6.50528978,13.6284215 C6.31304559,11.5678496 7.03283934,9.51741319 8.52512627,8.02512627 C10.0223249,6.52792766 12.0812426,5.80846733 14.1480759,6.00715131 Z M14.4980938,2.02230302 L14.313049,4.01372424 C11.6618299,3.76737046 9.03000738,4.69181803 7.1109127,6.6109127 C5.19447112,8.52735429 4.26985715,11.1545872 4.51274152,13.802405 L2.52110319,13.985098 C2.22450978,10.7517681 3.35562581,7.53777247 5.69669914,5.19669914 C8.04101739,2.85238089 11.2606138,1.72147333 14.4980938,2.02230302 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-warning font-weight-bold font-size-h6">Contactados</span>
                            <span class="card-title font-weight-bolder text-warning font-size-h2 mb-0 d-block">'.$data['total_contactado'].'</span>
                        </div>
                        <div class="col bg-light-primary px-6 py-8 rounded-xl mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <rect fill="#000000" opacity="0.3" x="7" y="4" width="10" height="4"/>
                                        <path d="M7,2 L17,2 C18.1045695,2 19,2.8954305 19,4 L19,20 C19,21.1045695 18.1045695,22 17,22 L7,22 C5.8954305,22 5,21.1045695 5,20 L5,4 C5,2.8954305 5.8954305,2 7,2 Z M8,12 C8.55228475,12 9,11.5522847 9,11 C9,10.4477153 8.55228475,10 8,10 C7.44771525,10 7,10.4477153 7,11 C7,11.5522847 7.44771525,12 8,12 Z M8,16 C8.55228475,16 9,15.5522847 9,15 C9,14.4477153 8.55228475,14 8,14 C7.44771525,14 7,14.4477153 7,15 C7,15.5522847 7.44771525,16 8,16 Z M12,12 C12.5522847,12 13,11.5522847 13,11 C13,10.4477153 12.5522847,10 12,10 C11.4477153,10 11,10.4477153 11,11 C11,11.5522847 11.4477153,12 12,12 Z M12,16 C12.5522847,16 13,15.5522847 13,15 C13,14.4477153 12.5522847,14 12,14 C11.4477153,14 11,14.4477153 11,15 C11,15.5522847 11.4477153,16 12,16 Z M16,12 C16.5522847,12 17,11.5522847 17,11 C17,10.4477153 16.5522847,10 16,10 C15.4477153,10 15,10.4477153 15,11 C15,11.5522847 15.4477153,12 16,12 Z M16,16 C16.5522847,16 17,15.5522847 17,15 C17,14.4477153 16.5522847,14 16,14 C15.4477153,14 15,14.4477153 15,15 C15,15.5522847 15.4477153,16 16,16 Z M16,20 C16.5522847,20 17,19.5522847 17,19 C17,18.4477153 16.5522847,18 16,18 C15.4477153,18 15,18.4477153 15,19 C15,19.5522847 15.4477153,20 16,20 Z M8,18 C7.44771525,18 7,18.4477153 7,19 C7,19.5522847 7.44771525,20 8,20 L12,20 C12.5522847,20 13,19.5522847 13,19 C13,18.4477153 12.5522847,18 12,18 L8,18 Z M7,4 L7,8 L17,8 L17,4 L7,4 Z" fill="#000000"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-primary font-weight-bold font-size-h6 mt-2">Cotizado</span>
                            <span class="card-title font-weight-bolder text-primary font-size-h2 mb-0 d-block">'.$data['total_cotizado'].'</span>
                        </div>
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row m-0">
                        <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
                            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M4.5,3 L19.5,3 C20.3284271,3 21,3.67157288 21,4.5 L21,19.5 C21,20.3284271 20.3284271,21 19.5,21 L4.5,21 C3.67157288,21 3,20.3284271 3,19.5 L3,4.5 C3,3.67157288 3.67157288,3 4.5,3 Z M8,5 C7.44771525,5 7,5.44771525 7,6 C7,6.55228475 7.44771525,7 8,7 L16,7 C16.5522847,7 17,6.55228475 17,6 C17,5.44771525 16.5522847,5 16,5 L8,5 Z M10.5857864,14 L9.17157288,15.4142136 C8.78104858,15.8047379 8.78104858,16.4379028 9.17157288,16.8284271 C9.56209717,17.2189514 10.1952621,17.2189514 10.5857864,16.8284271 L12,15.4142136 L13.4142136,16.8284271 C13.8047379,17.2189514 14.4379028,17.2189514 14.8284271,16.8284271 C15.2189514,16.4379028 15.2189514,15.8047379 14.8284271,15.4142136 L13.4142136,14 L14.8284271,12.5857864 C15.2189514,12.1952621 15.2189514,11.5620972 14.8284271,11.1715729 C14.4379028,10.7810486 13.8047379,10.7810486 13.4142136,11.1715729 L12,12.5857864 L10.5857864,11.1715729 C10.1952621,10.7810486 9.56209717,10.7810486 9.17157288,11.1715729 C8.78104858,11.5620972 8.78104858,12.1952621 9.17157288,12.5857864 L10.5857864,14 Z" fill="#000000"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-danger font-weight-bold font-size-h6 mt-2">Cancelado</span>
                            <span class="card-title font-weight-bolder text-danger font-size-h2 mb-0 d-block">'.$data['total_cancelado'].'</span>
                        </div>
                        <div class="col bg-light-success px-6 py-8 rounded-xl">
                            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Urgent-mail.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z" fill="#000000" opacity="0.3" transform="translate(11.500000, 12.000000) rotate(-345.000000) translate(-11.500000, -12.000000) "/>
                                        <path d="M2,6 L21,6 C21.5522847,6 22,6.44771525 22,7 L22,17 C22,17.5522847 21.5522847,18 21,18 L2,18 C1.44771525,18 1,17.5522847 1,17 L1,7 C1,6.44771525 1.44771525,6 2,6 Z M11.5,16 C13.709139,16 15.5,14.209139 15.5,12 C15.5,9.790861 13.709139,8 11.5,8 C9.290861,8 7.5,9.790861 7.5,12 C7.5,14.209139 9.290861,16 11.5,16 Z M11.5,14 C12.6045695,14 13.5,13.1045695 13.5,12 C13.5,10.8954305 12.6045695,10 11.5,10 C10.3954305,10 9.5,10.8954305 9.5,12 C9.5,13.1045695 10.3954305,14 11.5,14 Z" fill="#000000"/>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="text-success font-weight-bold font-size-h6 mt-2">Facturado</span>
                            <span class="card-title font-weight-bolder text-success font-size-h2 mb-0 d-block">'.$data['total_facturado'].'</span>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Stats-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Mixed Widget 1-->
    </div>
';
*/
?>