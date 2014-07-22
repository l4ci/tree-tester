$("span.collapse").before('<span class="toggle">+</span>');

$("li a").each(function() {
  $( this ).attr('href','javscript:;');
  $( this ).after('<button type="submit" name="result" value="'+$(this).closest('li').find('a').text()+'" class="action">Found here</button>');
});

$("span.collapse").click(function() {
  var prev = $(this).parents('li').first().find('.toggle').first(),
      nex = $(this).next('ul'),
      text = $(this).text();
  
  nex.slideToggle(600,function(){
    if (prev.text() === "+" ){
      prev.text("-");
      text = text + "> ";
    } else if (prev.text() === "-" ){
      prev.text("+");
      text = "";
    }
    $('#way').val($('#way').val() + text);
  });
});

$('span.toggle').click(function(){
  $(this).closest('li').find('.collapse').first().trigger('click');
});

// $("li a").click(function() {
//   var text = $(this).text();
//   $('#way').val($('#way').val() + text + ' ');
// });