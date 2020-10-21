"use strict";
// Class definition

var KTDatatableRemoteAjaxLeads = function() {
    // Private functions

    // basic demo
    var getLeads = function() {
        var datatable = $('#kt_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: 'https://cotizadorderco.com/clients/filter',
                        headers: {
                            'Authorization': 'JWT eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJfaWQiOiI1YmRjNjU3ZjVlODc5NTJjMjI2MjBkZTciLCJmaXJzdF9uYW1lIjoiQXJtYW5kbyIsImxhc3RfbmFtZSI6IkVzcGlub3phIiwiZW1haWwiOiJhcm1hbmRvQGxpa2VzZWFzb25zLmNvbSIsInBhc3N3b3JkIjoiJDJhJDEwJHR1OEV2dzRnMkMvQjhydS5rZHMzRS41Q0R0TC5IQXN1SlltSU02QzJ5Z1gwMDhWUkpDbXVTIiwicm9sZSI6IkFkbWluaXN0cmFkb3IiLCJicmFuZCI6IkRFUkNPIiwiY3JlYXRlZF9hdCI6IjIwMTgtMTEtMDJUMTQ6NTU6NTkuNzk5WiIsInVwZGF0ZWRfYXQiOiIyMDE4LTExLTAyVDE0OjU1OjU5Ljc5OVoiLCJfX3YiOjAsImlhdCI6MTYwMDEyMTQ5M30.VkEIh1quxHuCaXLl7xUHVI_JVre1Dq4oYPDirUZchHo', 
                            'Accept': 'application/json',
                        },
                        //contentType: 'application/json',
                        params: {
                            url1_w2l: 'https://derco.com.pe/catalogo-derco/',
                            store: code,
                            date1: '2020-09-01',
                            date2: '2020-09-17'
                        },
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 50,
                serverPaging: false,
                serverFiltering: false,
                serverSorting: false,
            },

            // layout definition
            layout: {
                scroll: false,
                footer: false,
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },

            // columns definition
            columns: [{
                field: 'id',
                title: '#',
                sortable: 'asc',
                width: 30,
                type: 'number',
                selector: false,
                textAlign: 'center',
            },/*{
                field: 'url1_w2l',
                title: 'Fuente',
            },*/{
                field: 'marca2',
                title: 'Marca',
            },{
                field: 'model_w2l',
                title: 'Modelo',
            }, {
                field: 'first_name',
                title: 'Nombres',
            }, {
                field: 'last_name',
                title: 'Apellidos',
            }, {
                field: 'email',
                title: 'E-mail',
            }, {
                field: 'fone1_w2l',
                title: 'Celular',
            }, {
                field: 'created_at',
                title: 'Creado',
                //type: 'date',
                //format: 'DD/MM/YYYY HH:mm:ss',
            }, {
                field: 'estado',
                title: 'Status',
                overflow: 'visible',
                autoHide: false,
                // callback function support for column rendering
                template: function(row) {
                    var status = {
                        'Nuevo': {
                            'title': 'Nuevo',
                            'class': ' label-light-success'
                        },
                        'Contactado': {
                            'title': 'Contactado',
                            'class': ' label-light-danger'
                        },
                        'Cotizado': {
                            'title': 'Cotizado',
                            'class': ' label-light-primary'
                        },
                        'Facturado': {
                            'title': 'Facturado',
                            'class': ' label-light-success'
                        },
                        'Cancelado': {
                            'title': 'Cancelado',
                            'class': ' label-light-info'
                        },
                    };
                    return '<span class="label font-weight-bold label-lg ' + status[row.estado].class + ' label-inline">' + status[row.estado].title + '</span>';
                },
            }, {
                field: 'Actions',
                title: 'Acciones',
                sortable: false,
                //width: 125,
                overflow: 'visible',
                autoHide: false,
                template: function() {
                    return '\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                    </g>\
                                </svg>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Cambiar estado:\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-print"></i></span>\
                                            <span class="navi-text">Nuevo</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-copy"></i></span>\
                                            <span class="navi-text">Contactado</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-file-excel-o"></i></span>\
                                            <span class="navi-text">Cotizado</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-file-text-o"></i></span>\
                                            <span class="navi-text">Facturado</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href="#" class="navi-link">\
                                            <span class="navi-icon"><i class="la la-file-pdf-o"></i></span>\
                                            <span class="navi-text">Cancelado</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                    ';
                },
            }],
        });

		$('#kt_datatable_search_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'estado');
        });

        $('#kt_datatable_search_type').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
    };

    return {
        // public functions
        init: function() {
            getLeads();
        },
    };
}();

jQuery(document).ready(function() {
    KTDatatableRemoteAjaxLeads.init();
});
