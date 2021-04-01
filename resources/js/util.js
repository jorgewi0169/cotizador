export const alertasM = {
    data() {
      return {
        swalWithBootstrapButtons : Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

      }
    },
    methods: {
        //Funcion para alertar
        notify(titulo,mensaje,from, align, icon, type, animIn, animOut) {
            $.growl(
              {
                icon: icon,
                title: titulo,
                message: mensaje,
                url: "",
              },
              {
                element: "body",
                type: type,
                allow_dismiss: true,
                placement: { from: from, align: align },
                offset: { x: 30, y: 30 },
                spacing: 10,
                z_index: 999999,
                delay: 2500,
                timer: 1000,
                url_target: "_blank",
                mouse_over: false,
                animate: { enter: animIn, exit: animOut },
                icon_type: "class",
                template:
                  '<div data-growl="container" class="alert" role="alert">' +
                  '<button type="button" class="close" data-growl="dismiss">' +
                  '<span aria-hidden="true">&times;</span>' +
                  '<span class="sr-only">Close</span>' +
                  "</button>" +
                  '<span data-growl="icon"></span>' +
                  '<span data-growl="title"></span>' +
                  '<span data-growl="message"></span>' +
                  '<a href="#!" data-growl="url"></a>' +
                  "</div>",
              }
            );
        },
        
        openCargando(status=false) {//Funcion para pantalla de carga
            const loading = this.$loading({
                lock: true,
                text: 'Cargando',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            if(status){
                loading.close();
            }

        },

}
};
