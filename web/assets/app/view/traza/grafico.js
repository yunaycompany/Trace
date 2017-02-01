Ext.define('UCI.view.traza.grafico', {
    
    extend: 'Ext.panel.Panel',
    alias: 'widget.trazagraf',
    bodyPadding: 10,
    itemId: 'graf',  
    id: 'mgraf',
    layout: 'fit',
    initComponent: function() {          
        Ext.apply(this, {           
            dockedItems: {
                xtype: 'toolbar',
                items: [{
                        itemId: 'cbUser',
                        fieldLabel: 'Usuarios:',
                        xtype: 'combo',
                        id: 'cbid',
                        displayField: 'usuario',
                        labelWidth: 50,
                        triggerAction: 'all',
                        queryMode: 'local',
                        emptyText: 'Seleccione un usuario...'
                    },'-',{
                        xtype: 'checkboxfield',
                        fieldLabel: 'Todos los usuarios',
                        itemId: 'allUsers',
                        id: 'ckauser'
                    },'-',{
                        xtype: 'button',
                        text: 'Graficar',
                        iconCls: 'blist',
                        id: 'bGraf',
                        itemId: 'biGraf'
                }]             
            },        
           items: [Ext.create('UCI.view.graficos.alltrazas')]
        });
        this.callParent(arguments);
    }            
});


