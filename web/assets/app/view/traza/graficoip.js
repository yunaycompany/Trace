Ext.define('UCI.view.traza.graficoip', {

    extend: 'Ext.panel.Panel',
    alias: 'widget.trazagrafip',
    bodyPadding: 10,
    itemId: 'grafip',
    id: 'mgrafip',
    layout: 'fit',
    initComponent: function() {
        Ext.apply(this, {
            items: [Ext.create('UCI.view.graficos.alliptrazas')]
        });
        this.callParent(arguments);
    }
});


