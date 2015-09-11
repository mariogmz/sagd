// app/margen/margen.controller.js

(function (){

  'use strict';

  angular
    .module('sagdApp.margen')
    .controller('margenController', MargenController);

  MargenController.$inject = ['$auth', '$state', '$http'];

  function MargenController($auth, $state, $http){
    if (!$auth.isAuthenticated()) {
      $state.go('login', {});
    }

    var vm = this;

    vm.obtenerMargen = function (){
      $http.get('http://api.sagd.app/api/v1/margen').
        then(function (response){
          vm.margenes = response.data;
        }, function (response){
          vm.errors = response.data;
        });
    };

    vm.obtenerMargen();
  }

})();
