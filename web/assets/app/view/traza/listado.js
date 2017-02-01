Ext.define('UCI.view.traza.listado', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.trazalist',
    title: 'Listado de Trazas',
    columnLines: true,
    store: 'Trazas',
    id: 'idListado',
    itemId: 'listartraza',
    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            viewConfig: {

            },
            tbar:[{
                itemId: 'cbUserGrid',
                fieldLabel: 'Usuarios:',
                xtype: 'combo',
                id: 'cbidgrid',
                displayField: 'usuario',
                labelWidth: 50,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                xtype: 'datefield',
                itemId: 'dpFecha',
                id: 'idFecha',
                fieldLabel: 'Desde:',
                labelWidth: 45,
                maxValue: new Date()
            },'-',{
                    xtype: 'datefield',
                    itemId: 'dpFechaF',
                    id: 'idFechaF',
                    fieldLabel: 'Hasta:',
                    labelWidth: 45,
                    maxValue: new Date()
                },'-',{
                itemId: 'cbIPGrid',
                fieldLabel: 'IPs:',
                xtype: 'combo',
                id: 'cbipgrid',
                displayField: 'ip',
                labelWidth: 35,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                itemId: 'cbAcciones',
                fieldLabel: 'Acciones:',
                xtype: 'combo',
                id: 'cbAccionesGrid',
                displayField: 'accion',
                store: 'Acciones',
                labelWidth: 50,
                triggerAction: 'all',
                queryMode: 'local',
                emptyText: 'Seleccione...'
            },'-',{
                text: 'Buscar',
                iconCls: 'blist',
                itemId: 'idbuscar',
                id: 'buscar'
            },{
                text: 'Limpiar',
                iconCls: 'reset',
                itemId: 'idClear',
                id: 'clear'
            }],
            columns: [
	        Ext.create('Ext.grid.RowNumberer'),
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'fecha',
                    flex: 1,
                    text: 'Fecha'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'hora',
                    flex: 1,
                    text: 'Hora'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'usuario',
                    flex: 1,
                    text: 'Usuario'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'ip',
                    flex: 1,
                    text: 'IP'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'accion',
                    flex: 1,
                    text: 'Acción'
                },
                {
                    xtype: 'actioncolumn',
                    width: 100,
                    text: 'Detalles',
                    itemId:'idAction',
                    id: 'idDetalles',

                    items:[{
                            icon: '../../assets/images/detalles.png',                            
                            tooltip: 'Ver Detalles',
                            action: 'detalles'
                    }]
                }
            ],
            bbar: Ext.create('Ext.PagingToolbar', {
                store: 'Trazas',
                itemId: 'gridTool',
                displayInfo: true
            })
        });

        me.callParent(arguments);
    }

});