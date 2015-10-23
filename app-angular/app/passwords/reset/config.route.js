// app/passwords/reset/config.route.js

(function() {
  'use strict';

  angular
    .module('sagdApp.passwords')
    .config(configureRoutes);

  configureRoutes.$inject = ['$stateProvider'];

  function configureRoutes($stateProvider) {
    $stateProvider
      .state('passwordsReset', {
        url: 'passwords/reset',
        parent: 'passwords',
        templateUrl: 'app/passwords/reset/reset.html',
        controller: 'passwordsResetController',
        controllerAs: 'vm'
      });
  }
})();
