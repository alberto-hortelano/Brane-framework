(function($) {
var sidebars_to_remove = [];
$('.brane_sbp_btn').click(function(){
  add_sidebar_link($(this).siblings('.sidebar_name').val());
});
$('.brane_sidebar_trash .brane_remove').click(function(){
  var brane_sidebar_row = $(this).closest('.brane_single_sidebar');
  var brane_sidebar_position = brane_sidebar_row.prevAll().length;
  if($(this).hasClass('to_remove')){
    $(this).removeClass('to_remove');
    for (var i = sidebars_to_remove.length - 1; i >= 0; i--) {
      if(sidebars_to_remove[i].num == brane_sidebar_position){
        sidebars_to_remove.splice(i, 1);
      }
    }
  }else{
    $(this).addClass('to_remove').parent().css('background','url('+ajaxurl.replace('admin-ajax.php','images/loading.gif')+') no-repeat 120px 50%');
    var brane_sidebar_name = brane_sidebar_row.children().children('.brane_sidebar_name').text();
    add_sidebar_to_remove(brane_sidebar_name,brane_sidebar_position);
  }
});
$('.brane_sbp_remove_btn').click(function(){
  if(sidebars_to_remove.length > 0) remove_sidebar();
});
function add_sidebar_to_remove(name,num){
  sidebars_to_remove.push({
    name:name,
    num:num
  });
}
function remove_sidebar_link(name,num){
answer = confirm( brane_sidebars_strings["remove_confirm"] + name + brane_sidebars_strings[ "remove_desc"]);
if(answer){
  remove_sidebar(name,num);
}else{
  return false;
}
}
function add_sidebar_link(sidebar_name){
//var sidebar_name = prompt( brane_sidebars_strings[ "sidebar_name"],"");
add_sidebar(sidebar_name);
}
function add_sidebar( sidebar_name )
{
  $('.add_sidebar').css('background','url('+ajaxurl.replace('admin-ajax.php','images/loading.gif')+') no-repeat 120px 50%');
  var data = {
    'action': 'add_sidebar',
    'sidebar_name': sidebar_name
  }
  $.post(ajaxurl, data)
  .done(function(response) {
    location.reload();
  })
  .fail(function(a,b,c) {
    alert( brane_sidebars_strings[ "alert_msg"] + ': ' + b + ' - ' + c);
    $('.add_sidebar').css('background','none');
  });
}

function remove_sidebar( ){
  if(sidebars_to_remove.length < 1) location.reload();
  $.post(ajaxurl, {
    'action': 'remove_sidebar',
    'sidebar_name': sidebars_to_remove[0].name,
    'row_number': sidebars_to_remove[0].num
  }).done(function(response) {
    sidebars_to_remove.splice(0, 1);
    setTimeout(function(){
      remove_sidebar( );
    },100);
  }).fail(function(a,b,c) {
    alert( brane_sidebars_strings[ "alert_msg"] + b + ' - ' + c);
    $('#sbg_table td').css('background','none');
  });
}
})(jQuery);
 