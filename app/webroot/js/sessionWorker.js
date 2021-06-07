/* 
 * Developed by Oscar Mota 2021
 * 
 * Copyright Axia Med
 */


var i = 0;
var minutesRemianing = 0;

function sessionCountDown() {
    //set timeout at 15 minutes
    minutesRemianing = Math.floor((900 - i) / 60);
    i = i + 1;
    postMessage(minutesRemianing);
    setTimeout("sessionCountDown()",990);
}

sessionCountDown();