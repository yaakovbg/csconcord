admin.controller('export', ['$rootScope', '$scope', '$state', '$http', 'userService', '$stateParams', '$sce','Upload', function ($rootScope, $scope, $state, $http, userService, $stateParams, $sce,Upload) {
        $scope.export = function () {

            $http({method: 'GET', url: '../server/export', headers: {'Accept': 'application/xml'}}).
                    success(function (data, status, headers, config) {
                        xmltext = data;
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
        $scope.onFileSelect = function ($files) {
                    //$files: an array of files selected, each file has name, size, and type.
                    for (var i = 0; $files && i < $files.length; i++) {
                        var $file = $files[i];
                        Upload.upload({
                            url: '../server/import',
                            file: $file,
                            progress: function (e) {}
                        }).then(function (data, status, headers, config) {

                           
//						else if (data.data.error)
//							alert(data.data.error);
//						else
//							alert("תקלה בהעלאת קובץ");
                        });
                    }
                }

    }]);