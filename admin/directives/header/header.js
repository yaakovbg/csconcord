admin.directive('header', ['$state', '$stateParams', '$rootScope', '$http', 'userService', function ($state, $stateParams, $rootScope, $http, userService) {
        return {
            restrict: 'E',
            templateUrl: './directives/header/header.html',
            
            link: function (scope, el, attrs) {
                scope.state = $state.current.name;



            },
            replace: true
        };



    }]);

