(function() {
    
  var app = angular.module('yellowSpine', []);

  app.controller('owned', function($scope){
      
      //call AJAX to update owned.php onclick
      
      $scope.toggle = function($event,bid,u){
          //only admin can change collection
          if(u==0){
            //who got clicked
            var et = $event.currentTarget;
            //get the data-num attribute for owned
            var num = $(et).data("num");
              
            //this section switches the check and unchecked class
            //and switches the owned number 0 or 1
            if(num == 0){
                //remove glyphicon-check
                $(et).removeClass("glyphicon-check");
                //add glyphicon-unchecked
                $(et).addClass("glyphicon-unchecked");
                //change the number for data-num owned attribute
                $(et).data("num", 1);
                
            } else {
                //remove glyphicon-check
                $(et).removeClass("glyphicon-unchecked");
                //add glyphicon-unchecked
                $(et).addClass("glyphicon-check");
                //change the number for data-num owned attribute
                $(et).data("num", 0);
            }
            //update the collection
            $.ajax({
                type: "POST",
                url: "owned.php",
                data: {
                    bid: bid,
                    o: num
                }
            })
            .done(function (msg) {
                alert("Collection Updated");
            });//end AJAX call
          } else {
              alert("Only an Administrator can change the collection");
          }//end user level check
      }//end toggle function
      
  });//end owned controller

})();