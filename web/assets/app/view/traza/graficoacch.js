Ext.define('UCI.view.traza.graficoacch', {

    extend: 'Ext.panel.Panel',
    alias: 'widget.graficoacch',
    bodyPadding: 10,
    itemId: 'grafacch',
    id: 'idacch',
    layout: 'fit',
    initComponent: function() {
        Ext.apply(this, {
            dockedItems: {
                xtype: 'toolbar',
                items: [{
                    itemId: 'cbAccHora',
                    fieldLabel: 'Usuarios:',
                    xtype: 'combo',
                    id: 'cbidAccHora',
                    displayField: 'usuario',
                    labelWidth: 50,
                    triggerAction: 'all',
                    queryMode: 'local',
                    emptyText: 'Seleccione...'
                },'-',{
                    xtype: 'datefield',
                    itemId: 'dtAccFecha',
                    id: 'dtidAccFecha',
                    fieldLabel: 'Desde:',
                    labelWidth: 45,
                    maxValue: new Date()
                },'-',{
                    xtype: 'datefield',
                    itemId: 'dtAccFechaF',
                    id: 'dtidAccFechaF',
                    fieldLabel: 'Hasta:',
                    labelWidth: 45,
                    maxValue: new Date()
                },'-',{
                    itemId: 'dtAccHoraIp',
                    fieldLabel: 'IP:',
                    xtype: 'combo',
                    id: 'dtiAccHoraIp',
                    displayField: 'ip',
                    labelWidth: 35,
                    triggerAction: 'all',
                    queryMode: 'local',
                    emptyText: 'Seleccione...'
                },'-',{
                    xtype: 'button',
                    text: 'Graficar',
                    iconCls: 'blist',
                    id: 'btnIdUsuarioHora',
                    itemId: 'btnUsuarioHora'
                },{
                    text: 'Limpiar',
                    iconCls: 'reset',
                    itemId: 'AccHoraClear',
                    id: 'idAccclear'
                }]
            },
            items: [Ext.create('UCI.view.graficos.allacchora')]
        });
        this.callParent(arguments);
    }
});



