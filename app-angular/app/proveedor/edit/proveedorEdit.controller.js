// app/proveedor/proveedorEdit.controller.js

(function (){

  'use strict';

  angular
    .module('sagdApp.proveedor')
    .controller('proveedorEditController', ProveedorEditController);

  ProveedorEditController.$inject = ['$auth', '$state', '$stateParams', '$location', 'api', 'pnotify'];

  function ProveedorEditController($auth, $state, $stateParams, $location, api, pnotify){
    if (!$auth.isAuthenticated()) {
      $state.go('login', {});
    }

    var vm = this;

    vm.id = $stateParams.id;
    vm.onSubmit = onSubmit;
    vm.model = {};

    vm.fields = [
      {
        type: 'input',
        key: 'clave',
        templateOptions: {
          label: 'Clave:',
          placeholder: 'Introduzca la clave',
          required: true
        },
        validators: {
          notEquals: '$viewValue != "Prov"'
        }
      }, {
        type: 'input',
        key: 'razon_social',
        templateOptions: {
          label: 'Razón social',
          placeholder: 'Introduzca la razón social',
          required: true
        }
      },{
        type: 'input',
        key: 'pagina_web',
        templateOptions: {
          label: 'Sitio Web',
        }
      },{
        type: 'select',
        key: 'externo',
        templateOptions: {
          label: '¿Es Externo?',
          options:
            [
              { value: 0, name: "No" },
              { value: 1, name: "Si" }
            ]
        }
      }

    ];

    function obtenerProveedor(){
      return api.get('/proveedor/', vm.id)
          .then(function (response){
            vm.proveedor = response.data.proveedor;
            return response.data;
          })
          .catch(function (response){
            vm.error = response.data;
            return response.data;
          });
    }

    vm.proveedor = obtenerProveedor(vm.id);

    function onSubmit(){

      //console.log(api);
      //alert(JSON.stringify(vm.model), null, 2);

      return api.put('/proveedor/', vm.proveedor.id, vm.proveedor)
      .then(function (response){
            vm.message = response.data.message;
            pnotify.alert('Exito', vm.message, 'success');
            //return response;

            $location.path('sucursales/proveedor');
          })
          .catch(function (response){
            vm.error = response.data;
            pnotify.alertList('No se pudo modificar el proveedor', vm.error.error, 'error');
            return response;
         });
    }

  }

})();
