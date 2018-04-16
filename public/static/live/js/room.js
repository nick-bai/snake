
var ob = document.querySelectorAll(".ta");
var vi = document.querySelectorAll(".vi");

for (var i = 0; i < ob.length; i++) {  
    ( ob[i].onclick = function () {
        var that = this
        for (var j = 0; j < ob.length; j++) {
            ob[j].className = "ta";
            that.className = "active";              
        }          
    })(i)  
}

// $(".input").focus(function(){
//     $(".msg").hide()
//     $(".toMsg").show()
// })
