// app/margen/index/margenIndex.controller.js

(function (){

  'use strict';

  angular
    .module('sagdApp.margen')
    .controller('margenIndexController', MargenIndexController);

  MargenIndexController.$inject = ['$auth', '$state', 'api', 'pnotify'];

  function MargenIndexController($auth, $state, api, pnotify){
    if (!$auth.isAuthenticated()) {
      $state.go('login', {});
    }

    var vm = this;
    vm.sort = sort;
    vm.eliminarMargen = eliminarMargen;
    vm.sortKeys = [
      {name: '#', key: 'id'},
      {name: 'Nombre', key: 'nombre'},
      {name: 'Valor', key: 'valor'},
      {name: 'Webservice-P1', key: 'valor_webservice_p1'},
      {name: 'Webservice-P8', key: 'valor_webservice_p8'}
    ];

    initialize();

    function initialize(){
      return obtenerMargenes().then(function (){
        console.log("Margenes obtenidos");
      });
    }

    function obtenerMargenes(){
      return api.get('/margen')
        .then(function (response){
          vm.margenes = response.data;
          return vm.margenes;
        });
    }

    function eliminarMargen(id){
      return api.delete('/margen/', id)
        .then(function (response){
          obtenerMargenes().then(function(){
            pnotify.alert('¡Exito!', response.data.message, 'success');
          });
        }).catch(function (response){
          pnotify.alert('¡Error!', response.data.message, 'error');
        });
    }

    function sort(keyname){
      vm.sortKey = keyname;
      vm.reverse = !vm.reverse;
    }

  }

})();
