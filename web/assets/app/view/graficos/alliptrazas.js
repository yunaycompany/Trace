Ext.define('UCI.view.graficos.alliptrazas', {
    extend: 'Ext.chart.Chart',
    alias: 'widget.alliptrazas',
    id: 'alliptrac',
    itemId: 'iptraza',
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
            store: 'IpTrazas',
            axes: [{
                type: 'Numeric',
                position: 'bottom',
                fields: ['Create','Update','Delete','Autentication'],
                title:  'Listado de IP',
                grid: true
            }, {
                type: 'Category',
                position: 'left',
                fields: ['ip'],
                title: 'Cantidad de Accciones'
            }],
            series: [{
                type: 'bar',
                axis: 'bottom',
                gutter: 80,
                xField: 'ip',
                yField: ['Create','Update','Delete','Autentication'],
                stacked: true,
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 35,
                    renderer: function(storeItem, item) {
                        this.setTitle(item.value[1]+ ' Trazas');
                    }
                }
            }]
        });
        me.callParent(arguments);
    }
});
