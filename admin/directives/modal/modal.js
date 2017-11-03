
admin.directive('modalFileuploader', ['$rootScope','Upload', function ($rootScope,Upload) {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalFileUploader.html',
		transclude: true,
        scope: {
                        onConfirm: '&onConfirm',
			ngModel:'='
        },
		link: function (scope, elem, attrs) {
			scope.modalShow=false;
			scope.hasError=false;
			scope.modalLoad=false;
			scope.orginalModel=angular.copy(scope.ngModel);
                        scope.data={};
			scope.open=function(){
				scope.orginalModel=angular.copy(scope.ngModel);
				// alert('modal.js, corOrder: open');
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";
				scope.modalShow=true;
			}
			scope.close=function(){
				scope.hasError = false;
				scope.ngModel=angular.copy(scope.orginalModel);
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
			scope.confirm=function(){
				var hasError=false;
				scope.hasError = hasError;
				
				
				scope.existEmailError=false;
					
				scope.hasError=hasError;
				if(hasError) return;
				
				scope.orginalModel=scope.ngModel;
				scope.close();
				if(attrs.onConfirm){
					scope.onConfirm();
				}

			}
                        scope.onFileSelect = function($files) {
				//$files: an array of files selected, each file has name, size, and type.
				for (var i = 0; $files && i < $files.length; i++) {
					var $file = $files[i];
					Upload.upload({
						url: '../server/file',
						file: $file,
						progress: function(e){}
					}).then(function(data, status, headers, config) {
						// file is uploaded successfully
//						if (data.data.fileUrl)
//							scope.updategGllery();
//						else if (data.data.error)
//							alert(data.data.error);
//						else
//							alert("תקלה בהעלאת קובץ");
					}); 
				}
			}
        },
    };
} ]);

admin.directive('modalConfirm', [ function () {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalConfirm.html',
		transclude: true,
        scope: {
            onConfirm: '&onConfirm',
			text:'@text'
        },		
		link: function (scope, elem, attrs) {
			scope.modalShow=false;
			
			scope.open=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";
				scope.modalShow=true;
			}
			scope.close=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
			scope.confirm=function(){
				scope.close();
				if(attrs.onConfirm){
					scope.onConfirm();
				}
			}
        },
    };
} ]);
admin.directive('modalLoader', [ function () {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalLoader.html',
		transclude: true,
        scope: {
           show: '=',
        },		
		link: function (scope, elem, attrs) {
			scope.modalShow=true;
			
			scope.open=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";
				scope.modalShow=true;
			}
			scope.close=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
        },
    };
} ]);
admin.directive('modalMinisite', [ function () {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalMinisite.html',
		
		transclude: true,
        scope: {
            onConfirm: '&onConfirm',
			error:'=',
			minisite:'='
        },		
		link: function (scope, elem, attrs) {
			scope.modalShow=false;
			scope.orginalData=angular.copy(scope.minisite);
			scope.$watch('error',function(){
				if(scope.error){
					scope.errortext=true;
					scope.open();
					scope.error=false;
					scope.orginalData=angular.copy(scope.minisite);
				}
			})
			scope.open=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";
				scope.modalShow=true;
			}
			scope.close=function(){
				for(var key in scope.orginalData)
					scope.minisite[key]=scope.orginalData[key];
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
			scope.confirm=function(){
				scope.orginalData=scope.minisite;
				scope.close();
				if(attrs.onConfirm){
					scope.onConfirm();
				}
			}
        },
    };
} ]);
admin.directive('modalSelectImage', ['Upload', '$http', function (Upload,$http) {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalSelectImage.html',
		transclude: true,
        scope: {
            onConfirm: '=onConfirm',
			text:'@text',
			ngModel: '=',
			obj:'=',
			objkey:'@objkey'
        },
		
		link: function (scope, elem, attrs) {
			scope.modalShow=false;
			scope.selectGroup=0;
			scope.selectIndex=0;
			scope.gallery=null;
			scope.updategGllery=function(){
				$http({method: 'POST', url: '../server/data.php?type=getGallery'}).
						success(function(data, status, headers, config) {
							scope.gallery=data;
						});	
			}
			scope.open=function(){
				if(scope.gallery==null) scope.updategGllery();
				scope.selectIndex=0;
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";				
				scope.modalShow=true;
			}
			scope.close=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
			scope.select=function(i){
				scope.selectIndex=i;
			}
			scope.setSelectGroup=function(i){
				scope.selectGroup=i;
			}
			scope.confirm=function(){
				scope.close();
				console.log();
				
				scope.ngModel=scope.gallery[scope.selectGroup].list[scope.gallery[scope.selectGroup].list.length-scope.selectIndex-1];
				if(scope.obj && scope.objkey)
					scope.obj[scope.objkey]=scope.ngModel;
				if(attrs.onConfirm){
					scope.onConfirm(scope.ngModel);
				}
			}
			
			scope.onFileSelect = function($files) {
				//$files: an array of files selected, each file has name, size, and type.
				for (var i = 0; $files && i < $files.length; i++) {
					var $file = $files[i];
					Upload.upload({
						url: '../server/data.php?type=uploadImageToGallery&group='+scope.selectGroup,
						file: $file,
						progress: function(e){}
					}).then(function(data, status, headers, config) {
						// file is uploaded successfully
						if (data.data.fileUrl)
							scope.updategGllery();
						else if (data.data.error)
							alert(data.data.error);
						else
							alert("תקלה בהעלאת קובץ");
					}); 
				}
			}
			
			scope.addGroup = function($files) {
				$http({method: 'POST', url: '../server/data.php?type=addGroupToGallery'}).
						success(function(data, status, headers, config) {
							scope.updategGllery();
						});	
			}
			scope.saveGroupName = function($files) {
				$http({method: 'POST', url: '../server/data.php?type=saveGroupName',data:{group:scope.selectGroup,name:scope.gallery[scope.selectGroup].name}}).
						success(function(data, status, headers, config) {
							scope.updategGllery();
						});	
			}
        },
    };
} ]);

admin.directive('modalMedia', ["Upload","$http", function (Upload,$http) {
    return {
        restrict: 'A',
        templateUrl: './directives/modal/modalMedia.html',
		transclude: true,
        scope: {
            onConfirm: '&onConfirm',
			media:'=',
			control:'='
        },		
		link: function (scope, elem, attrs) {
			scope.modalShow=false;
			scope.data=scope.media
			scope.getData=function(){
				$http({method: 'POST', url: '../server/data.php?type=getSpecificMedia', data:scope.media}).
						success(function(data, status, headers, config) {
							scope.data=data;
							console.log(scope.data);
					}).error(function(){
						alert('error');
						scope.close();
					});	
			}
			scope.open=function(){
				scope.getData();
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className + " modal-open";
				scope.modalShow=true;
			}
			if(scope.control){	
				
				scope.control.open=scope.open;	
				
			}
			
			scope.close=function(){
				var body = document.getElementsByTagName("body")[0];
				body.className = body.className.replace(/\bmodal-open\b/,'');	
				scope.modalShow=false;
			}
			scope.confirm=function(){
				scope.close();
				if(attrs.onConfirm){
					scope.onConfirm();
				}
			}
			
			
        },
    };
} ]);

admin.filter('reverse', function() {
  return function(items) {
	if(items==null) return null;
    return items.slice().reverse();
  };
});
admin.directive('datepicker', function() {
    return {
        restrict: 'A',
        require : 'ngModel',
        link : function (scope, element, attrs, ngModelCtrl) {
            $(function(){
                element.datetimepicker({
					scrollMonth: false,
					scrollTime: false,
					scrollInput: false,
                    
					format:'Y/m/d',
					timepicker:false,
                    onSelect:function (date) {
                        scope.$apply(function () {
                            ngModelCtrl.$setViewValue(date);
                        });
                    }
                });
            });
        }
    }
});

