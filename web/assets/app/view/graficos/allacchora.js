Ext.define('UCI.view.graficos.allacchora', {
    extend: 'Ext.chart.Chart',
    alias: 'widget.allacchora',
    id: 'idallacchora',
    itemId: 'allacch',
    animate: true,
    shadow: true,
    legend: {
        position: 'right'
    },
    initComponent: function() {
        var me = this;
        Ext.applyIf(me, {
            viewConfig: {
            },
            store: 'Acchoras',
            axes: [{
                type: 'Numeric',
                position: 'left',
                minimum: 0,
                minorTickSteps: 0,
                fields: ['Create','Update','Delete','Autentication'],
                title: 'Cantidad de Acciones'
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['horario'],
                title: 'Horas',
                label: {
                    rotate: {
                        degrees: 90
                    }
                }
            }],
            series: [{
                type: 'line',
                highlight: {
                    size: 7,
                    radius: 7
                },
                axis: 'left',
                xField: 'horario',
                yField: 'Create',
                fill: true,
                markerConfig: {
                    type: 'circle',
                    size: 4,
                    radius: 4,
                    'stroke-width': 0
                },
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 35,
                    renderer: function(storeItem, item) {
                        this.setTitle('Total de acciones: '+item.value[1]);
                    }
                }
            },{
                type: 'line',
                highlight: {
                    size: 7,
                    radius: 7
                },
                axis: 'left',
                xField: 'horario',
                yField: 'Update',
                markerConfig: {
                    type: 'circle',
                    size: 4,
                    radius: 4,
                    'stroke-width': 0
                },
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 35,
                    renderer: function(storeItem, item) {
                        this.setTitle('Total de acciones: '+item.value[1]);
                    }
                }
            },{
                type: 'line',
                highlight: {
                    size: 7,
                    radius: 7
                },
                axis: 'left',
                xField: 'horario',
                yField: 'Delete',
                markerConfig: {
                    type: 'circle',
                    size: 4,
                    radius: 4,
                    'stroke-width': 0
                },
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 35,
                    renderer: function(storeItem, item) {
                        this.setTitle('Total de acciones: '+item.value[1]);
                    }
                }
            },{
                type: 'line',
                highlight: {
                    size: 7,
                    radius: 7
                },
                axis: 'left',
                xField: 'horario',
                yField: 'Autentication',
                markerConfig: {
                    type: 'circle',
                    size: 4,
                    radius: 4,
                    'stroke-width': 0
                },
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 35,
                    renderer: function(storeItem, item) {
                        this.setTitle('Total de acciones: '+item.value[1]);
                    }
                }
            }]
        });
        me.callParent(arguments);
    }
});

