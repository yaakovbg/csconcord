admin.directive('datetimebtn', [ function () {
    return {
        restrict: 'A',
        templateUrl: './directives/datetimebtn/datetimebtn.html',
		transclude: true,
        scope: {
			 ngModel:'='
        },		
		link: function (scope, elem, attrs) {
			if(scope.ngModel==undefined)
				scope.ngModel=null;
			if (scope.ngModel==null){
				scope.dateObject=new Date();
			}
			else{
				scope.dateObject=new Date(scope.ngModel);
			}
			scope.parts={};
			scope.parts.year=scope.dateObject.getFullYear();
			scope.parts.month=scope.dateObject.getMonth()+1;
			scope.parts.day=scope.dateObject.getDate();
			scope.parts.hour=scope.dateObject.getHours();
			scope.parts.minits=scope.dateObject.getMinutes();
			
			scope.updateDateObject=function(){
				scope.dateObject=new Date(scope.parts.year, scope.parts.month-1, scope.parts.day, scope.parts.hour, scope.parts.minits);
			}
			
			scope.showModal=false;
			scope.ok=function(){
				scope.ngModel=new Date(scope.dateObject).getTime();
				scope.showModal=false;
			}
			scope.delete=function(){
				scope.ngModel=null;
				scope.showModal=false;
			}
			
        },
    };
} ]);


