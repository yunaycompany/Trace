Ext.define('UCI.view.graficos.uacciones', {
    extend: 'Ext.chart.Chart',
    alias: 'widget.utrazagraf',
    id: 'chartuacc',
    animate: true,
    shadow: true,
    theme: 'Blue',
    initComponent: function() {
        var me = this;
        Ext.applyIf(me, {
            viewConfig: {
            },
            store: 'UGraficos',
            axes: [{
                    type: 'Numeric',
                    position: 'bottom',
                    fields: ['total'],
                    title: 'Cantidad de Trazas',
                    grid: true,
                    minimum: 0,
                label: {
                    renderer: Ext.util.Format.numberRenderer('0.0')
                }

                }, {
                    type: 'Category',
                    position: 'left',
                    fields: ['accion'],
                    title: 'Acciones'
                }],
            series: [{
                    type: 'bar',
                    axis: 'bottom',
                    xField: 'accion',
                    yField: 'total',
                    highlight: true,
                    tips: {
                        trackMouse: true,
                        width: 150,
                        height: 35,                        
                        renderer: function(storeItem, item) {
                            this.setTitle(storeItem.get('total') + ' de traza(s) de '+storeItem.get('accion'));
                        }
                    },
                    label: {
                        display: 'insideEnd',
                        field: 'total',
                        renderer: Ext.util.Format.numberRenderer('0'),
                        orientation: 'horizontal',
                        fill: '#fff',
                        'text-anchor': 'middle'
                    }
                }]
        });
        me.callParent(arguments);
    }
});
