admin.directive('header', ['$state', '$stateParams', '$rootScope', '$http', 'userService', function ($state, $stateParams, $rootScope, $http, userService) {
        return {
            restrict: 'E',
            templateUrl: './directives/header/header.html',
            
            link: function (scope, el, attrs) {
                scope.state = $state.current.name;

                scope.export = function () {
                   
                    $http({method: 'GET', url: '../server/export', headers: {'Accept': 'application/xml'}}).
                            success(function (data, status, headers, config) {
                                xmltext=data;
                                var pom = document.createElement('a');

                                var filename = "file.xml";
                                var pom = document.createElement('a');
                                var bb = new Blob([xmltext], {type: 'text/plain'});

                                pom.setAttribute('href', window.URL.createObjectURL(bb));
                                pom.setAttribute('download', filename);

                                pom.dataset.downloadurl = ['text/plain', pom.download, pom.href].join(':');
                                pom.draggable = true;
                                pom.classList.add('dragout');

                                pom.click();
                                
                            });
                }

            },
            replace: true
        };



    }]);

