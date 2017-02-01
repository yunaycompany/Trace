Ext.define('UCI.view.traza.log', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.trazalog',
    id: 'idLog',
    itemId: 'logpanel',
    html: '<b>Generar archivo de Logs</b>',
    initComponent: function(){
        var me = this;
        Ext.applyIf(me, {
            viewConfig: {

            },
            tbar:[{
                itemId: 'cbUserLog',
                fieldLabel: 'Usuarios:',
                xtype: 'combo',
                id: 'cbuserLog',
                displayField: 'usuario',
                labelWidth: 50,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                xtype: 'datefield',
                itemId: 'dpLogFecha',
                id: 'idLogFecha',
                fieldLabel: 'Desde:',
                labelWidth: 45,
                maxValue: new Date()
            },'-',{
                xtype: 'datefield',
                itemId: 'dpLogFechaF',
                id: 'idLogFechaF',
                fieldLabel: 'Hasta:',
                labelWidth: 45,
                maxValue: new Date()
            },'-',{
                itemId: 'cbIPLog',
                fieldLabel: 'IPs:',
                xtype: 'combo',
                id: 'cbipLog',
                displayField: 'ip',
                labelWidth: 35,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                itemId: 'cbLogAcciones',
                fieldLabel: 'Acciones:',
                xtype: 'combo',
                id: 'cbAccionesLog',
                displayField: 'accion',
                store: 'Acciones',
                labelWidth: 50,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                text: 'Exportar',
                iconCls: 'blist',
                itemId: 'idLogbuscar',
                id: 'logbuscar',
                url: Routing.generate('logtrace_descargar'),
                baseParams:{
                    user: '',
                    ip: '',
                    fechai: '',
                    fechaf: '',
                    acc: ''
                }

            },{
                text: 'Limpiar',
                iconCls: 'reset',
                itemId: 'idLogClear',
                id: 'logclear'
            }]

});
        me.callParent(arguments)
    }
 });
