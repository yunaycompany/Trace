Ext.define('UCI.view.traza.ventana',{
    extend: 'Ext.window.Window',
    alias: 'widget.trazaventana',   
    title: 'Detalles de la traza',
    height: 200,
    width: 400,
    layout: 'fit',
    initComponent: function() {         
        this.buttons =[{
            text: 'Cerrar',
            scope: this,
            handler: this.close
        }];
        this.callParent(arguments);  
    } 
});

