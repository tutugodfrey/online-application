/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
var postsArr = new Array(),
    $postsList = $('div.posts');
 
//Create array of all posts in lists
$postsList.find('div').each(function(){
    postsArr.push($(this).html());
})
 
//Split the array at this point. The original array is altered.
var itemsLendth = postsArr.length
var firstList = postsArr.splice(0, Math.round(itemsLendth / 4)),
    secondList = postsArr.splice(0, Math.round(itemsLendth / 4)),
    thirdList = postsArr.splice(0, Math.round(itemsLendth / 4)),
    fourthList = postsArr,
    ListHTML = '';
 
function createHTML(list){
    ListHTML = '';
    for (var i = 0; i < list.length; i++) {
        ListHTML += '<div>' + list[i] + '</div>'
    };
}
//Generate HTML for the lists in reversed order but starting with the original list.
//Generate HTML for first list
createHTML(firstList);
$postsList.html(ListHTML);
 
//Generate HTML for fourth list
createHTML(fourthList);
 
//Create new list after original one
$postsList.after('<div class="posts"></div>').next().html(ListHTML);
 
//Generate HTML for third list
createHTML(thirdList);
 
//Create new list after original one
$postsList.after('<div class="posts"></div>').next().html(ListHTML);
 
//Generate HTML for second list
createHTML(secondList);
 
//Create new list after original one
$postsList.after('<div class="posts"></div>').next().html(ListHTML);

});
