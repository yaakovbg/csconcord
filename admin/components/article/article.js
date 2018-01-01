admin.controller('article', ['$rootScope', '$scope', '$state', '$http', 'userService', '$stateParams', '$sce', function ($rootScope, $scope, $state, $http, userService, $stateParams, $sce) {
        $scope.aid = $stateParams.aid;
        $scope.search = $stateParams.search;
        $scope.getArticle = function () {
            $http({method: 'GET', url: '../server/article/' + $scope.aid}).
                    success(function (data, status, headers, config) {
                        $scope.articleData = data;
                        if ($scope.search && $scope.search !== '') {
                            var find = $scope.search;
                            var re = new RegExp('\\b'+find+'\\b', 'g');

                            $scope.articleData.fileContent = $scope.articleData.fileContent.replace(re, '<span class="search-word">' + decodeURIComponent($scope.search) + '</span>');
                           
                        }

                    });
        }
        $scope.getArticle();
        $scope.trustHtml = function (str) {
            return $sce.trustAsHtml(str);
        }


    }]);